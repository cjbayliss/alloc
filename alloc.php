<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

// The order of file processing usually goes:
// requested_script.php -> alloc.php -> alloc_config.php -> more includes
// -> back to requested_script.php

require_once __DIR__ . '/vendor/autoload.php';
use ZendSearch\Lucene\Analysis\Analyzer\Analyzer;
use ZendSearch\Lucene\Analysis\Analyzer\Common\Utf8Num\CaseInsensitive;
use ZendSearch\Lucene\Lucene;
use ZendSearch\Lucene\Search\Query\Wildcard;

function &singleton($name, $thing = null)
{
    static $instances;
    isset($name) && isset($thing) and $instances[$name] = &$thing;
    return $instances[$name];
}

singleton("errors_fatal", false);
singleton("errors_format", "html");
singleton("errors_logged", false);
singleton("errors_thrown", false);
singleton("errors_haltdb", false);

// Set the charset for Zend Lucene search indexer
// http://framework.zend.com/manual/en/zend.search.lucene.charset.html
Analyzer::setDefault(
    new CaseInsensitive()
);
Wildcard::setMinPrefixLength(0);

// Get the alloc directory
$currentDirectory = trim(__DIR__);
substr($currentDirectory, -1, 1) != DIRECTORY_SEPARATOR and $currentDirectory .= DIRECTORY_SEPARATOR;
define("ALLOC_MOD_DIR", $currentDirectory);
unset($currentDirectory);

define("APPLICATION_NAME", "allocPSA");
define("APPLICATION_VERSION", "2.0.0_alpha");
define("ALLOC_GD_IMAGE_TYPE", "PNG");

define("DATE_FORMAT", "d/m/Y");

// Source and destination modifiers for various values
define("SRC_DATABASE", 1);  // Reading the value from the database
define("SRC_VARIABLE", 2);  // Reading the value from a PHP variable (except a form variable)
define("SRC_REQUEST", 3);  // Reading the value from a get or post variable
define("DST_DATABASE", 1);  // For writing to a database
define("DST_VARIABLE", 2);  // For use within the PHP script itself
define("DST_HTML_DISPLAY", 4);  // For display to the user as non-editable HTML text

// The list of all the modules that are enabled for this install of alloc
$moduleNames = [
    "shared",
    "home",
    "project",
    "task",
    "time",
    "finance",
    "invoice",
    "client",
    "comment",
    "item",
    "person",
    "announcement",
    "reminder",
    "security",
    "config",
    "search",
    "tools",
    "report",
    "login",
    "services",
    "installation",
    "help",
    "email",
    "sale",
    "audit",
    "calendar",
];

$external_storage_directories = [
    "task",
    "client",
    "project",
    "invoice",
    "comment",
    "whatsnew",
    "logos",
    "search",
    "tmp",
];

// Helper functions
require_once(ALLOC_MOD_DIR . "shared" . DIRECTORY_SEPARATOR . "util.inc.php");

foreach ($moduleNames as $moduleName) {
    $modulePath = ALLOC_MOD_DIR . $moduleName .
        DIRECTORY_SEPARATOR . "lib" .
        DIRECTORY_SEPARATOR . "init.php";
    if (file_exists($modulePath)) {
        require_once($modulePath);
        $moduleClass = $moduleName . "_module";
        $module = new $moduleClass();
        $modules[$moduleName] = $module;
    }
}
singleton("modules", $modules);

// Get the web base url SCRIPT_PATH for the alloc site
$path = dirname($_SERVER["SCRIPT_NAME"]);
$bits = explode("/", $path);
is_array($moduleNames) && in_array(end($bits), $moduleNames) && array_pop($bits);
is_array($bits) and $path = implode("/", $bits);
(empty($path[0]) || $path[0] != "/") and $path = "/" . $path;
(empty($path[strlen($path) - 1]) || $path[strlen($path) - 1] != "/") and $path .= "/";
define("SCRIPT_PATH", $path);

unset($moduleNames);

$allocHelpLinkName = array_slice(explode("/", $_SERVER["PHP_SELF"]), -2, 1);
$mainAllocTitle = explode("/", $_SERVER["SCRIPT_NAME"]);
$TPL = [
    "url_alloc_index"        => SCRIPT_PATH . "index.php",
    "url_alloc_login"        => SCRIPT_PATH . "login/login.php",
    "url_alloc_installation" => SCRIPT_PATH . "installation/install.php",
    "url_alloc_styles"       => ALLOC_MOD_DIR . "css/",
    "url_alloc_images"       => SCRIPT_PATH . "images/",
    "url_alloc_help"         => ALLOC_MOD_DIR . "help" . DIRECTORY_SEPARATOR,
    "alloc_help_link_name"   => end($allocHelpLinkName),
    "script_path"            => SCRIPT_PATH,
    "main_alloc_title"       => end($mainAllocTitle),
];

if (file_exists(ALLOC_MOD_DIR . "alloc_config.php")) {
    require_once(ALLOC_MOD_DIR . "alloc_config.php");
} else {
    // assume we are in development mode
    define('ATTACHMENTS_DIR', '/var/local/alloc/');
    define("ALLOC_DB_NAME", "alloc");
    define("ALLOC_DB_USER", "alloc");
    define("ALLOC_DB_PASS", "changeme");
    define("ALLOC_DB_HOST", "database");
}

$db = new AllocDatabase();
singleton("db", $db);

// ATTACHMENTS_DIR is defined above in alloc_config.php
define("ALLOC_LOGO", ATTACHMENTS_DIR . "logos/logo.jpg");
define("ALLOC_LOGO_SMALL", ATTACHMENTS_DIR . "logos/logo_small.jpg");

// The timezone must be dealt with before anything else uses it or php will emit
// a warning
$timezone = config::get_config_item("allocTimezone");
date_default_timezone_set($timezone);

// Now the timezone is set, replace the missing stuff from the template
$TPL["current_date"] = date("Y-m-d H:i:s");
$TPL["today"] = date("Y-m-d");

// The default From: email address
if (config::get_config_item("AllocFromEmailAddress")) {
    define(
        "ALLOC_DEFAULT_FROM_ADDRESS",
        add_brackets(config::get_config_item("AllocFromEmailAddress"))
    );
}

// The default email bounce address
define("ALLOC_DEFAULT_RETURN_PATH_ADDRESS", config::get_config_item("allocEmailAdmin"));

// If a script has NO_AUTH enabled, then it will perform its own authentication.
// And will be responsible for setting up any of: $current_user and $sess.
$sess = false;
if (!defined("NO_AUTH")) {
    $current_user = &singleton("current_user", new person());
    $sess = new Session();

    // If session hasn't been started re-direct to login page
    if (!$sess->Started()) {
        defined("NO_REDIRECT") &&
            exit("Session expired. Please <a href='" .
                $TPL["url_alloc_login"] .
                "'>log in</a> again.");
        alloc_redirect($TPL["url_alloc_login"] . ($_SERVER['REQUEST_URI'] != '/'
            ? '?forward=' . urlencode($_SERVER['REQUEST_URI'])
            : ''));

        // Else load up the current_user and continue
    } elseif ($sess->Get("personID")) {
        $current_user->load_current_user($sess->Get("personID"));
    }
}

// Setup all the urls
require_once(ALLOC_MOD_DIR . "shared" . DIRECTORY_SEPARATOR . "global_tpl_values.inc.php");
$TPL = get_alloc_urls($TPL, $sess);

// Add user's navigation to quick list dropdown
if (
    !empty($current_user)
    && is_object($current_user)
    && $current_user->get_id()
) {
    $history = new History();
    $history->save_history();
    $TPL["current_user"] = $current_user;
}

// Setup search indices if they don't already exist
if (!file_exists(ATTACHMENTS_DIR . "search/task")) {
    $search_item_indexes = [
        "client",
        "comment",
        "item",
        "project",
        "task",
        "timeSheet",
    ];
    foreach ($search_item_indexes as $i) {
        $index = Lucene::create(ATTACHMENTS_DIR . 'search' . DIRECTORY_SEPARATOR . $i);
        $index->commit();
    }
}

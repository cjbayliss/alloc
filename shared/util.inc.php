<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

// Format a time offset in seconds to (+|-)HH:MM
function format_offset($secs)
{
    // sign will be included in the hours
    $sign = $secs < 0 ? '' : '+';
    $h = $secs / 3600;
    $m = $secs % 3600 / 60;

    return sprintf('%s%2d:%02d', $sign, $h, $m);
}

// List of Timezone => Offset Timezone
// i.e. Australia/Melbourne => +11:00 Australia/Melbourne
// Ordered by GMT offset
function get_timezone_array()
{
    $zones = timezone_identifiers_list();
    $zonelist = [];

    // List format suitable for sorting
    $now = new DateTime();

    $idx = 0; // to distinguish timezones on the same offset
    foreach ($zones as $zone) {
        $tz = new DateTimeZone($zone);
        $offset = $tz->getOffset($now);
        // Index is [actual offset]+[arbitrary index]{3}
        $zonelist[$offset * 10000 + $idx++] = [$zone, format_offset($offset) . " " . $zone];
    }

    // Sort and unpack
    $list = [];
    ksort($zonelist);
    foreach ($zonelist as $zone) {
        $list[$zone[0]] = $zone[1];
    }

    return $list;
}

function format_date($format = "Y/m/d", $date = "")
{

    $t = null;
    // If looks like this: 2003-07-07 21:37:01
    if (preg_match("/^[\d]{4}-[\d]{1,2}-[\d]{1,2} [\d]{2}:[\d]{2}:[\d]{2}$/", $date)) {
        list($d, $t) = explode(" ", $date);

    // If looks like this: 2003-07-07
    } else if (preg_match("/^[\d]{4}-[\d]{1,2}-[\d]{1,2}$/", $date)) {
        $d = $date;

    // If looks like this: 12:01:01
    } else if (preg_match("/^[\d]{2}:[\d]{2}:[\d]{2}$/", $date)) {
        $d = "2000-01-01";
        $t = $date;

    // Nasty hobbitses!
    } else if ($date) {
        return "Date unrecognized: " . $date;
    } else {
        return;
    }
    list($y, $m, $d) = explode("-", $d);
    list($h, $i, $s) = explode(":", $t);
    list($y, $m, $d, $h, $i, $s) = [sprintf("%d", $y), sprintf("%d", $m), sprintf("%d", $d), sprintf("%d", $h), sprintf("%d", $i), sprintf("%d", $s)];
    return date($format, mktime(date($h), date($i), date($s), date($m), date($d), date($y)));
}

function add_brackets($email = "")
{
    if ($email) {
        $l = strpos($email, "<");
        $r = strpos($email, ">");
        $l === false and $email = "<" . $email;
        $r === false and $email .= ">";
        return $email;
    }
}

function seconds_to_display_format($seconds)
{
    $day = config::get_config_item("hoursInDay");

    $day_in_seconds = $day * 60 * 60;
    $hours = $seconds / 60 / 60;
    if ($seconds != "") {
        return sprintf("%0.2f hrs", $hours);
    }
    return;

    if ($seconds < $day_in_seconds) {
        return sprintf("%0.2f hrs", $hours);
    } else {
        $days = $seconds / $day_in_seconds;
        // return sprintf("%0.1f days", $days);
        return sprintf("%0.2f hrs (%0.1f days)", $hours, $days);
    }
}

function get_all_form_data($array = [], $defaults = [])
{
    // Load up $_FORM with $_GET and $_POST
    $_FORM = [];
    foreach ($array as $name) {
        $_FORM[$name] = $_POST[$name] or $_FORM[$name] = $_GET[$name] or $_FORM[$name] = $defaults[$name];
    }
    return $_FORM;
}

function timetook($start, $friendly_output = true)
{
    $end = microtime();
    list($start_micro, $start_epoch, $end_micro, $end_epoch) = explode(" ", $start . " " . $end);
    $started = (substr($start_epoch, -4) + $start_micro);
    $finished = (substr($end_epoch, -4) + $end_micro);
    $dur = $finished - $started;
    if ($friendly_output) {
        $unit = " seconds.";
        if ($dur > 60) {
            $unit = " mins.";
            $dur = $dur / 60;
        }
        return sprintf("%0.5f", $dur) . $unit;
    }
    return sprintf("%0.5f", $dur);
}

function sort_by_name($a, $b)
{
    return strtolower($a["name"]) >= strtolower($b["name"]);
}

function rebuild_cache($table)
{
    $cache = &singleton("cache");

    if (meta::$tables[$table]) {
        $m = new meta($table);
        $cache[$table] = $m->get_list();
    } else {
        $db = new db_alloc();
        $db->query("SELECT * FROM " . $table);
        while ($row = $db->row()) {
            $cache[$table][$db->f($table . "ID")] = $row;
        }
    }

    // Special processing for person and config tables
    if ($table == "person") {
        $people = $cache["person"];
        foreach ($people as $id => $row) {
            if ($people[$id]["firstName"] && $people[$id]["surname"]) {
                $people[$id]["name"] = $people[$id]["firstName"] . " " . $people[$id]["surname"];
            } else {
                $people[$id]["name"] = $people[$id]["username"];
            }
        }
        uasort($people, "sort_by_name");
        $cache["person"] = $people;
    } else if ($table == "config") {
        // Special processing for config table
        $config = $cache["config"];
        foreach ($config as $id => $row) {
            $rows_config[$row["name"]] = $row;
        }
        $cache["config"] = $rows_config;
    }
    singleton("cache", $cache);
}

function &get_cached_table($table, $anew = false)
{
    $cache = &singleton("cache");
    if ($anew || !$cache[$table]) {
        rebuild_cache($table);
    }
    return $cache[$table];
}

function sort_by_mtime($a, $b)
{
    return $a["mtime"] >= $b["mtime"];
}

function util_show_attachments($entity, $id, $options = [])
{
    global $TPL;
    $TPL["entity_url"] = $TPL["url_alloc_" . $entity];
    $TPL["entity_key_name"] = $entity . "ID";
    $TPL["entity_key_value"] = $id;
    $TPL["bottom_button"] = $options["bottom_button"];
    $TPL["show_buttons"] = !$options["hide_buttons"];

    $rows = get_attachments($entity, $id);
    if (!$rows && $options["hide_buttons"]) {
        return; // no rows, and no buttons, leave the whole thing out.
    }
    $rows or $rows = [];
    foreach ($rows as $row) {
        $TPL["attachments"] .= "<tr><td>" . $row["file"] . "</td><td class=\"nobr\">" . $row["mtime"] . "</td><td>" . $row["size"] . "</td>";
        $TPL["attachments"] .= "<td align=\"right\" width=\"1%\" style=\"padding:5px;\">" . $row["delete"] . "</td></tr>";
    }
    include_template("../shared/templates/attachmentM.tpl");
}

function get_filesize_label($file)
{
    $size = filesize($file);
    return get_size_label($size);
}

function get_size_label($size)
{
    $rtn = null;
    $size > 1023 and $rtn = sprintf("%dK", $size / 1024);
    $size < 1024 and $rtn = sprintf("%d", $size);
    $size > (1024 * 1024) and $rtn = sprintf("%0.1fM", $size / (1024 * 1024));
    return $rtn;
}

function get_file_type_image($file)
{
    $types = [];
    global $TPL;
    // hardcoded types ...
    $types["pdf"] = "pdf.gif";
    $types["xls"] = "xls.gif";
    $types["csv"] = "xls.gif";
    $types["zip"] = "zip.gif";
    $types[".gz"] = "zip.gif";
    $types["doc"] = "doc.gif";
    $types["sxw"] = "doc.gif";
    // $types["odf"] = "doc.gif";

    $type = strtolower(substr($file, -3));
    $icon_dir = ALLOC_MOD_DIR . "images" . DIRECTORY_SEPARATOR . "fileicons" . DIRECTORY_SEPARATOR;
    if ($types[$type]) {
        $t = $types[$type];
    } else if (file_exists($icon_dir . $type . ".gif")) {
        $t = $type . ".gif";
    } else if (file_exists($icon_dir . $type . ".png")) {
        $t = $type . ".png";
    } else {
        $t = "unknown.gif";
    }
    return "<img border=\"0\" alt=\"icon\" src=\"" . $TPL["url_alloc_images"] . "/fileicons/" . $t . "\">";
}

function get_attachments($entity, $id, $ops = [])
{

    $row = [];
    $sessID = null;
    global $TPL;
    $rows = [];
    $dir = ATTACHMENTS_DIR . $entity . DIRECTORY_SEPARATOR . $id;

    if (isset($id)) {
        // if (!is_dir($dir)) {
        // mkdir($dir, 0777);
        // }

        if (is_dir($dir)) {
            $handle = opendir($dir);

            // TODO add icons to files attachaments in general
            while (false !== ($file = readdir($handle))) {
                clearstatcache();

                if ($file != "." && $file != "..") {
                    $image = get_file_type_image($dir . DIRECTORY_SEPARATOR . $file);

                    $row["size"] = get_filesize_label($dir . DIRECTORY_SEPARATOR . $file);
                    $row["path"] = $dir . DIRECTORY_SEPARATOR . $file;
                    $row["file"] = "<a href=\"" . $TPL["url_alloc_getDoc"] . "id=" . $id . "&entity=" . $entity . "&file=" . urlencode($file) . "\">" . $image . $ops["sep"] . page::htmlentities($file) . "</a>";
                    $row["text"] = page::htmlentities($file);
                    // $row["delete"] = "<a href=\"".$TPL["url_alloc_delDoc"]."id=".$id."&entity=".$entity."&file=".urlencode($file)."\">Delete</a>";
                    $row["delete"] = "<form action=\"" . $TPL["url_alloc_delDoc"] . "\" method=\"post\">
                            <input type=\"hidden\" name=\"id\" value=\"" . $id . "\">
                            <input type=\"hidden\" name=\"file\" value=\"" . $file . "\">
                            <input type=\"hidden\" name=\"entity\" value=\"" . $entity . "\">
                            <input type=\"hidden\" name=\"sbs_link\" value=\"attachments\">
                            <input type=\"hidden\" name=\"sessID\" value=\"{$sessID}\">"
                        . '<button type="submit" name="delete_file_attachment" value="1" class="delete_button">Delete<i class="icon-trash"></i></button>' . "</form>";

                    $row["mtime"] = date("Y-m-d H:i:s", filemtime($dir . DIRECTORY_SEPARATOR . $file));
                    $row["restore_name"] = $file;

                    $rows[] = $row;
                }
            }
            closedir($handle);
        }
        is_array($rows) && usort($rows, "sort_by_mtime");
    }
    return $rows;
}

function rejig_files_array($f)
{
    $files = [];
    // Re-jig the $_FILES array so that it can handle <input type="file" name="many_files[]">
    if ($f) {
        foreach ($f as $key => $thing) {
            if (is_array($f[$key]["tmp_name"])) {
                foreach ($f[$key]["tmp_name"] as $k => $v) {
                    if ($f[$key]["tmp_name"][$k]) {
                        $files[] = [
                            "name"     => $f[$key]["name"][$k],
                            "tmp_name" => $f[$key]["tmp_name"][$k],
                            "type"     => $f[$key]["type"][$k],
                            "error"    => $f[$key]["error"][$k],
                            "size"     => $f[$key]["size"][$k],
                        ];
                    }
                }
            } else if ($f[$key]["tmp_name"]) {
                $files[] = [
                    "name"     => $f[$key]["name"],
                    "tmp_name" => $f[$key]["tmp_name"],
                    "type"     => $f[$key]["type"],
                    "error"    => $f[$key]["error"],
                    "size"     => $f[$key]["size"],
                ];
            }
        }
    }
    return (array)$files;
}

function move_attachment($entity, $id = false)
{
    global $TPL;

    $id = sprintf("%d", $id);
    $files = rejig_files_array($_FILES);

    if (is_array($files) && count($files)) {
        foreach ($files as $file) {
            if (is_uploaded_file($file["tmp_name"])) {
                $dir = ATTACHMENTS_DIR . $entity . DIRECTORY_SEPARATOR . $id;
                if (!is_dir($dir)) {
                    mkdir($dir, 0777);
                }
                $newname = basename($file["name"]);

                if (!move_uploaded_file($file["tmp_name"], $dir . DIRECTORY_SEPARATOR . $newname)) {
                    alloc_error("Could not move attachment to: " . $dir . DIRECTORY_SEPARATOR . $newname);
                } else {
                    chmod($dir . DIRECTORY_SEPARATOR . $newname, 0777);
                }
            } else {
                switch ($file['error']) {
                    case 0:
                        alloc_error("There was a problem with your upload.");
                        break;
                    case 1: // upload_max_filesize in php.ini
                        alloc_error("The file you are trying to upload is too big(1).");
                        break;
                    case 2: // MAX_FILE_SIZE
                        alloc_error("The file you are trying to upload is too big(2).");
                        break;
                    case 3:
                        alloc_error("The file you are trying upload was only partially uploaded.");
                        break;
                    case 4:
                        alloc_error("You must select a file for upload.");
                        break;
                    default:
                        alloc_error("There was a problem with your upload.");
                        break;
                }
            }
        }
    }
}

function db_esc($str = "")
{
    $db = &singleton("db");
    return $db->esc($str);
}

function bad_filename($filename)
{
    return preg_match("@[/\\\]@", $filename);
}

function parse_email_address($email = "")
{
    // Takes Alex Lance <alla@cyber.com.au> and returns array("alla@cyber.com.au", "Alex Lance");
    if ($email) {
        $structure = Mail_RFC822::parseAddressList($email);
        $name = (string)$structure[0]->personal;
        if ($structure[0]->mailbox && $structure[0]->host) {
            $addr = (string)$structure[0]->mailbox . "@" . (string)$structure[0]->host;
        }
        return [$addr, $name];
    }
    return [];
}

function same_email_address($addy1, $addy2)
{
    list($from_address1, $from_name1) = parse_email_address($addy1);
    list($from_address2, $from_name2) = parse_email_address($addy2);
    if ($from_address1 == $from_address2) {
        return true;
    }
}

function alloc_redirect($target_url)
{
    $params = [];
    global $TPL;

    $seperator = "&";
    if (strpos($target_url, "?") === false) {
        $seperator = "?";
    }

    foreach ([
        "message",
        "message_good",
        "message_help",
        "message_help_no_esc",
        "message_good_no_esc",
    ] as $type) {
        if ($TPL[$type]) {
            if (is_array($TPL[$type])) {
                $TPL[$type] = implode("<br>", $TPL[$type]);
            }
            if (is_string($TPL[$type]) && strlen($TPL[$type])) {
                $params[] = $type . "=" . urlencode($TPL[$type]);
            }
        }
    }

    // the first argument of header() must be a string
    if (!empty($params)) {
        $params = $seperator . implode("&", $params);
    } else {
        $params = '';
    }
    header("Location: " . $target_url . $params);
    exit();
}

function obj2array($input)
{
    if (is_object($input)) {
        $input = get_object_vars($input);
    }

    // https://www.php.net/manual/en/language.constants.magic.php
    if (is_array($input)) {
        return array_map(__FUNCTION__, $input);
    }

    return $input;
}

function image_create_from_file($path)
{
    $info = getimagesize($path);
    if (!$info) {
        echo "unable to determine getimagesize($path)";
        return false;
    }
    $functions = [
        IMAGETYPE_GIF  => 'imagecreatefromgif',
        IMAGETYPE_JPEG => 'imagecreatefromjpeg',
        IMAGETYPE_PNG  => 'imagecreatefrompng',
        IMAGETYPE_WBMP => 'imagecreatefromwbmp',
        IMAGETYPE_XBM  => 'imagecreatefromwxbm',
    ];

    if (!$functions[$info[2]]) {
        echo "no function to handle $info[2]";
        return false;
    }
    if (!function_exists($functions[$info[2]])) {
        echo "no function exists to handle " . $functions[$info[2]];
        return false;
    }
    $f = $functions[$info[2]];
    return $f($path);
}

function imp($var)
{
    // This function exists because php equates zeroes to false values.
    // imp == important == is this variable important == if imp($var)
    return $var !== [] && trim((string)$var) !== '' && $var !== null && $var !== false;
}

function get_exchange_rate($from, $to)
{

    // eg: AUD to AUD == 1
    if ($from == $to) {
        return 1;
    }

    $debug = $_REQUEST["debug"];

    usleep(500000); // So we don't hit their servers too hard
    $debug and print "<br>";

    $url = 'http://finance.yahoo.com/d/quotes.csv?f=l1d1t1&s=' . $from . $to . '=X';
    $data = file_get_contents($url);
    $debug and print "<br>Y: " . htmlentities($data);
    $results = explode(",", $data);
    $rate = $results[0];
    $debug and print "<br>Yahoo says 5 " . $from . " is worth " . ($rate * 5) . " " . $to . " at this exchange rate: " . $rate;

    if (!$rate) {
        $url = 'http://www.google.com/ig/calculator?hl=en&q=' . urlencode('1' . $from . '=?' . $to);
        $data = file_get_contents($url);
        $debug and print "<br>G: " . htmlentities($data);
        $arr = json_decode($data, true);
        $rate = current(explode(" ", $arr["rhs"]));
        $debug and print "<br>Google says 5 " . $from . " is worth " . ($rate * 5) . " " . $to . " at this exchange rate: " . $rate;
    }

    return trim($rate);
}

function array_kv($arr, $k, $v)
{
    $rtn = [];
    foreach ((array)$arr as $key => $value) {
        if (is_array($v)) {
            $sep = '';
            foreach ($v as $i) {
                $rtn[$value[$k]] .= $sep . $value[$i];
                $sep = " ";
            }
        } else if ($k) {
            $rtn[$value[$k]] = $value[$v];
        } else {
            $rtn[$key] = $value[$v];
        }
    }
    return $rtn;
}

function in_str($in, $str)
{
    return strpos($str, $in) !== false;
}

function rmdir_if_empty($dir)
{
    if (is_dir($dir)) {
        // Nuke dir if empty
        if (dir_is_empty($dir)) {
            rmdir($dir);
        }
    }
}

function dir_is_empty($dir)
{
    $num_files = null;
    if (is_dir($dir)) {
        $handle = opendir($dir);
        clearstatcache();
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                $num_files++;
                clearstatcache();
            }
        }
        return !$num_files;
    }
}

function tax($amount, $taxPercent = null)
{
    // take a tax included amount and return the untaxed amount, and the amount of tax
    // eg: 500 including 10% tax, returns array(454.54, 45.45)
    imp($taxPercent) or $taxPercent = config::get_config_item("taxPercent");
    $amount_minus_tax = $amount / (($taxPercent / 100) + 1);
    $amount_of_tax = $amount / ((100 / $taxPercent) + 1);
    return [$amount_minus_tax, $amount_of_tax];
}

function add_tax($amount = 0)
{
    return $amount * (config::get_config_item("taxPercent") / 100 + 1);
}

function alloc_error($str = "", $force = null)
{
    $errors_logged = &singleton("errors_logged");
    $errors_thrown = &singleton("errors_thrown");
    $errors_fatal = &singleton("errors_fatal");
    isset($force) and $errors_fatal = $force; // permit override
    $errors_format = &singleton("errors_format");
    $errors_format or $errors_format = "html";
    $errors_haltdb = &singleton("errors_haltdb");

    // Load up a nicely rendered html error
    if ($errors_format == "html") {
        global $TPL;
        $TPL["message"][] = $str;
    }

    // Output a plain-text error suitable for logfiles and CLI
    if ($errors_format == "text" && ini_get('display_errors')) {
        echo(strip_tags($str));
    }

    // Log the error message
    if ($errors_logged) {
        error_log(strip_tags($str));
    }

    // Prevent further db queries
    if ($errors_haltdb) {
        db_alloc::$stop_doing_queries = true;
    }

    // Throw an exception, that can be caught and handled (eg receiveEmail.php)
    if ($errors_thrown) {
        throw new Exception(strip_tags($str));
    }

    // Print message to a blank webpage (eg tools/backup.php)
    if ($force) {
        echo $str;
    }

    // If it was a serious error, then halt
    if ($errors_fatal) {
        exit(1); // exit status matters to pipeEmail.php
    }
}

/**
 * Undocumented function
 *
 * @deprecated just don't use this please 😭
 *
 * @return void
 */
function sprintf_implode()
{
    $f = [];
    $rtn = null;
    $comma = null;
    // I am crippling this function to make its purpose clearer, max 6 arguments.
    //
    // $numbers = array(20,21,22);
    // $words = array("howdy","hello","goodbye");
    //
    // sprintf_implode("(name = '%s')", $words);
    // Returns: ((name = 'howdy') OR (name = 'hello') OR (name = 'goodbye'))
    //
    // sprintf_implode("(id = %d AND name = '%s')", $numbers, $words);
    // Returns: ((id = 20 AND name = 'howdy') OR (id = 21 AND name = 'hello') OR (id = 22 AND name = 'goodbye'))
    //
    // We default to joining by OR, but if the first argument passed doesn't contain
    // a percentage (ie the sprintf marker), then we assume the first arg is the glue,
    // and we bump all the args along one. This changes the usage of the function to:
    //
    // sprintf_implode(" AND ", "(name != '%s')", $words);
    // Returns: ((name != 'howdy') AND (name != 'hello') AND (name != 'goodbye'))
    //
    $args = func_get_args();
    $glue = " OR ";
    if (!in_str("%", $args[0])) {
        $glue = array_shift($args);
    }

    $str = array_shift($args);

    $f["arg1"] = $args[0];
    $f["arg2"] = $args[1];
    $f["arg3"] = $args[2];
    $f["arg4"] = $args[3];
    $f["arg5"] = $args[4];
    $f["arg6"] = $args[5];
    $length = count($f["arg1"]);

    foreach ($f as $k => $v) {
        if ($v && count($v) != $length) {
            alloc_error("One of the values passed to sprintf_implode was the wrong length: " . $str . " " . print_r($args, 1));
        }
        if ($v && !is_array($v)) {
            $f[$k] = [$v];
        }
    }

    $x = 0;
    while ($x < $length) {
        $rtn .= $comma . sprintf(
            $str,
            db_esc($f["arg1"][$x]),
            db_esc($f["arg2"][$x]),
            db_esc($f["arg3"][$x]),
            db_esc($f["arg4"][$x]),
            db_esc($f["arg5"][$x]),
            db_esc($f["arg6"][$x])
        );
        $comma = $glue;
        $x++;
    }
    return "(" . $rtn . ")";
}

/**
 * This function should NOT be used. It is unsafe.
 *
 * FIXME: delete this function.
 *
 * @deprecated
 *
 * @param mixed $args
 * @return void
 */
function unsafe_prepare(...$args)
{
    $clean_args = [];

    if (count($args) == 1) {
        return $args[0];
    }

    // First element of $args get assigned to zero index of $clean_args
    // Array_shift removes the first value and returns it..
    $clean_args[] = array_shift($args);

    // The rest of $args are escaped and then assigned to $clean_args
    foreach ($args as $arg) {
        if (is_array($arg)) {
            foreach ((array)$arg as $v) {
                $str .= $comma . "'" . db_esc($v) . "'";
                $comma = ",";
            }
            $clean_args[] = $str;
        } else {
            $clean_args[] = db_esc($arg);
        }
    }

    // Have to use this coz we don't know how many args we're gonna pass to sprintf..
    $query = @call_user_func_array("sprintf", $clean_args);

    // Trackdown poorly formulated queries
    $err = error_get_last();
    if ($err["type"] == 2 && in_str("sprintf", $err["message"])) {
        $e = new Exception();
        alloc_error("Error in prepared query: \n" . $e->getTraceAsString() . "\n" . print_r($err, 1) . "\n" . print_r($clean_args, 1));
    }

    return $query;
}

function has($module)
{
    $modules = &singleton("modules");
    return (isset($modules[$module]) && $modules[$module]) || class_exists($module);
}

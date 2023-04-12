<?php

define("NO_AUTH", 1);
require_once("../alloc.php");
singleton("errors_fatal", true);
singleton("errors_format", "text");
singleton("errors_logged", false);
singleton("errors_thrown", false);
singleton("errors_haltdb", true);

function getRequestVariable($variableName)
{
    $value = null;

    if (isset($_GET[$variableName])) {
        $value = $_GET[$variableName];
    } else if (isset($_POST[$variableName])) {
        $value = $_POST[$variableName];
    } else if (isset($_REQUEST[$variableName])) {
        $value = $_REQUEST[$variableName];
    }

    if ($variableName === "options" && isset($_POST[$variableName])) {
        $value = json_decode($_POST[$variableName], true);
    }

    return $value;
}

$sessID = getRequestVariable("sessID");

if (
    getRequestVariable("authenticate") &&
    getRequestVariable("username") &&
    getRequestVariable("password")
) {
    $sessID = services::authenticate(
        getRequestVariable("username"),
        getRequestVariable("password")
    );
    die(json_encode(["sessID" => $sessID]));
}

$services = new services($sessID);
$current_user = &singleton("current_user");
if (
    !$current_user ||
    !is_object($current_user) ||
    !$current_user->get_id()
) {
    die(json_encode(["reauthenticate" => "true"]));
}

if ($sessID) {
    $methodRequested = getRequestVariable("method");
    if (method_exists($services, $methodRequested)) {
        $modelReflector = new ReflectionClass('services');
        $method = $modelReflector->getMethod($methodRequested);

        $parameters = $method->getParameters();
        $args = [];
        foreach ($parameters as $parameter) {
            $args[] = getRequestVariable($parameter->name);
        }

        $result = call_user_func_array([$services, $methodRequested], $args);
        echo json_encode($result);
    }
}

<?php

define('NO_AUTH', 1);
require_once __DIR__ . '/../alloc.php';
singleton('errors_fatal', true);
singleton('errors_format', 'text');
singleton('errors_logged', false);
singleton('errors_thrown', false);
singleton('errors_haltdb', true);

function getRequestVariable($variableName)
{
    if (isset($_GET[$variableName])) {
        $value = $_GET[$variableName];
    } elseif (isset($_POST[$variableName])) {
        $value = $_POST[$variableName];
    } elseif (isset($_REQUEST[$variableName])) {
        $value = $_REQUEST[$variableName];
    }

    if ('options' !== $variableName) {
        return null;
    }

    if (!isset($_POST[$variableName])) {
        return null;
    }

    return json_decode($_POST[$variableName], true, 512, JSON_THROW_ON_ERROR);
}

$sessID = getRequestVariable('sessID');

if (
    getRequestVariable('authenticate')
    && getRequestVariable('username')
    && getRequestVariable('password')
) {
    $sessID = services::authenticate(
        getRequestVariable('username'),
        getRequestVariable('password')
    );
    exit(json_encode(['sessID' => $sessID], JSON_THROW_ON_ERROR));
}

$services = new services($sessID);
$current_user = &singleton('current_user');
if (
    !$current_user
    || !is_object($current_user)
    || !$current_user->get_id()
) {
    exit(json_encode(['reauthenticate' => 'true']));
}

if ($sessID) {
    $methodRequested = getRequestVariable('method');
    if (method_exists($services, $methodRequested)) {
        $modelReflector = new ReflectionClass('services');
        $method = $modelReflector->getMethod($methodRequested);

        $parameters = $method->getParameters();
        $args = [];
        foreach ($parameters as $parameter) {
            $args[] = getRequestVariable($parameter->name);
        }

        $result = call_user_func_array([$services, $methodRequested], $args);
        echo json_encode($result, JSON_THROW_ON_ERROR);
    }
}

<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

// This is a callback function that examins the curly braces inside inline
// javascript/css blocks, and ensures that we can have both PHP template
// variables and javascript/css syntax playing together nicely. Basically we're
// relying on braces within css or javascript to always have nice readable
// white space around them. Where as PHP curly braces usually won't. m =
// multiline, need it for the ^$ anchor matching.
function fix_curly_braces($matches)
{
    $str = $matches[0];
    $str = preg_replace('/{ /', 'TPL_START_BRACE ', $str);
    $str = preg_replace('/{$/m', 'TPL_START_BRACE', $str);
    $str = preg_replace('/ }/', ' TPL_END_BRACE', $str);
    $str = preg_replace('/^}/m', 'TPL_END_BRACE', $str);

    // added because windows doesn't always respect $
    return preg_replace('/{' . PHP_EOL . '/m', 'TPL_START_BRACE', $str);
}

// This function basically returns: echo $var; $var can be a multi-dimensional
// array. $var can also be html entity protected if prefixed with the equals sign.
function echo_var($matches)
{
    $starts_with_equals = null;
    $str = $matches[1];
    if ('=' == substr($str, 0, 1)) {
        $str = preg_replace('/^=/', '', $str);
        $starts_with_equals = true;
    }

    // If it doesn't have periods in it, then explode will
    // return an array with one element, the entire string
    $bits = explode('.', $str);

    // Build up something like $var["little"][$colour]["riding"]["hood"]
    // array_shift returns the 0th element, and shortens the array by one
    $var = array_shift($bits);
    foreach ($bits as $bit) {
        $var .= '$' == substr($bit, 0, 1) ? '[' . $bit . ']' : '["' . $bit . '"]';
    }

    if ($var && $starts_with_equals) {
        return '<?php echo Page::htmlentities(' . $var . '); ?>';
    }

    if ('' === $var) {
        return;
    }

    if ('0' === $var) {
        return;
    }

    return '<?php echo ' . $var . '; ?>';
}

// Read a template and return the mixed php/html, ready for processing
function get_template($filename): string
{
    $template = implode('', @file($filename));
    if (!$template || '' === $template) {
        echo sprintf('get_template() failure: [%s]', $filename);
    }

    // This allows us to use curly braces in our templates for javascript and CSS rules
    // The TPL_*_BRACE keyword gets replaces by an actual curly brace later
    // on in this template function. Uis means: ungreedy, case-insensitive and
    // include newlines for dot matches.
    $pattern = '/<script.*<\\/script>/Uis';
    $template = preg_replace_callback($pattern, 'fix_curly_braces', $template);

    $pattern = '/<style.*<\\/style>/Uis';
    $template = preg_replace_callback($pattern, 'fix_curly_braces', $template);

    // Replace {$hello}           with: echo $hello
    // Replace {=$hello}          with: echo Page::htmlentities($hello)
    // Replace {$arr.here.we.go}  with: echo $arr["here"]["we"]["go"]
    // Replace {$arr.here.$we.go} with: echo $arr["here"][$we]["go"]
    $pattern = '/{(=?\$[\w\d_\.\$]+)}/i';
    $template = preg_replace_callback($pattern, 'echo_var', $template);

    // Replace {if hey}    with if (hey) {
    // Replace {while hey} with while (hey) {
    // Replace {foreach hey} with foreach (hey) {
    $pattern = '/{(if|while|foreach){1} ([^}]*)}/i';
    $replace = '<?php ${1} (${2}) TPL_START_BRACE ?>';
    $template = preg_replace($pattern, $replace, $template);

    // Replace {else with {TPL_END_BRACE else
    $pattern = '{else';
    $replace = '{TPL_END_BRACE else';
    $template = str_replace($pattern, $replace, $template);

    // Replace {TPL_END_BRACE else} with {TPL_END_BRACE else TPL_START_BRACE
    $pattern = '{TPL_END_BRACE else}';
    $replace = '{TPL_END_BRACE else TPL_START_BRACE}';
    $template = str_replace($pattern, $replace, $template);

    // Replace {TPL_END_BRACE else if hey} with {TPL_END_BRACE else if (hey) TPL_START_BRACE}
    $pattern = '/{TPL_END_BRACE else\s?if\s?([^}]+)}/i';
    $replace = '{TPL_END_BRACE else if (${1}) TPL_START_BRACE}';
    $template = preg_replace($pattern, $replace, $template);

    $sr = [
        '{/}'             => '<?php TPL_END_BRACE ?>',
        '{Page::'         => '<?php echo Page::',
        '{'               => '<?php ',
        '}'               => ' ?>',
        'TPL_END_BRACE'   => '}',
        'TPL_START_BRACE' => '{',
        '.php&'           => '.php?',
    ];

    foreach ($sr as $s => $r) {
        $searches[] = $s;
        $replaces[] = $r;
    }

    $template = str_replace($searches, $replaces, $template);

    return sprintf('?>%s<?php ', $template);
}

// This is the publically callable function, used to include template files
function include_template($filename, $getString = false)
{
    global $TPL;
    $current_user = &singleton('current_user');
    $TPL['current_user'] = $current_user;
    $template = get_template($filename);
    // echo "<pre>".htmlspecialchars($template)."</pre>";

    // Make all variables available via $var
    if (is_array($TPL)) {
        extract($TPL, EXTR_OVERWRITE);
    }

    if ($getString) {
        // Begin buffering output to halt anything being sent to the web browser.
        ob_start();
    }

    $rtn = eval($template);

    if (false === $rtn && ($error = error_get_last())) {
        $s = DIRECTORY_SEPARATOR;
        $f = $filename;
        echo "<b style='color:red'>Error line " . $error['line'] . ' in template: ';
        echo basename(dirname($f, 2)) . $s . basename(dirname($f)) . $s . basename($f) . '</b>';

        $bits = explode("\n", $template);

        foreach ($bits as $k => $bit) {
            echo '<br>' . $k . '&nbsp;&nbsp;&nbsp;&nbsp;' . Page::htmlentities($bit);
        }

        exit;
    }

    if ($getString) {
        // Grab everything that was captured in the output buffer and return
        // it as a string.
        return (string) ob_get_clean();
    }
}

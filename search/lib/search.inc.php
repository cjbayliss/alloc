<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define('PERM_PROJECT_READ_TASK_DETAIL', 256);

class search
{
    public function by_file($file, $needle)
    {
        $rtn = [];
        if (file_exists($file) && is_readable($file) && !is_dir($file)) {
            $rtn = [];
            $fp = fopen($file, 'r');
            if ($fp) {
                while (!feof($fp)) {
                    $line = stream_get_line($fp, 65535, "\n"); // faster than fgets
                    if (false !== stripos($line, strtolower($needle))) {
                        $rtn[] = $line;
                    }
                }

                fclose($fp);
            }
        }

        return $rtn;
    }

    public function get_trimmed_description($haystack, $needle, $category)
    {
        $position = stripos($haystack, strtolower($needle));
        if (false !== $position) {
            $prefix = '...';
            $suffix = '...';

            // Nuke trailing ellipses if the string ends in the match
            if (strlen(substr($haystack, $position)) === strlen($needle)) {
                $suffix = '';
            }

            $buffer = 30;
            $position -= $buffer;

            // Reset position to zero cause negative number will make it wrap around,
            // and nuke ellipses prefix because the string begins with the match
            if ($position < 0) {
                $position = 0;
                $prefix = '';
            }

            $str = substr($haystack, $position, strlen($needle) + 100);
            $str = str_replace($needle, '[[[' . $needle . ']]]', $str);

            return $prefix . $str . $suffix;
        }

        if ('Clients' == $category) {
            return substr($haystack, 0, 100);
        }
    }

    public function get_recursive_dir_list($dir)
    {
        $rtn = [];
        $dir = realpath($dir) . DIRECTORY_SEPARATOR;
        $dont_search_these_dirs = ['.', '..', 'CVS', '.hg', '.bzr', '_darcs', '.git'];
        $files = scandir($dir);
        foreach ($files as $file) {
            if (!in_array($file, $dont_search_these_dirs)) {
                if (is_file($dir . $file) && !is_dir($dir . $file)) {
                    $rtn[] = $dir . $file;
                } elseif (is_dir($dir . $file)) {
                    $rtn = array_merge((array) $rtn, (array) (new search())->get_recursive_dir_list($dir . $file));
                }
            }
        }

        return $rtn;
    }
}

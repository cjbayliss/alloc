<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class Session
{
    private $key;

    private \AllocDatabase $allocDatabase;

    private $session_data;

    private $session_life;

    private $mode;

    // Constructor
    public function __construct($key = '')
    {
        global $TPL;
        if ('' !== $key) {
            $this->key = $key;
        } elseif (!empty($_COOKIE['alloc_cookie'])) {
            $this->key = $_COOKIE['alloc_cookie'];
        } elseif (!empty($_GET['sess'])) {
            $this->key = $_GET['sess'];
        } elseif (!empty($_REQUEST['sessID'])) {
            $this->key = $_REQUEST['sessID'];
        }

        $TPL['sessID'] = $_GET['sess'] ?? false;
        $this->allocDatabase = new AllocDatabase();
        $this->session_life = (config::get_config_item('allocSessionMinutes') * 60);
        if ($this->session_life < 1) {
            $this->session_life = 10000;
        }

        // just in case.
        $this->session_data = $this->UnEncode($this->GetSessionData());
        $this->mode = $this->Get('session_mode');

        if ($this->Expired()) {
            $this->Destroy();
        }

        return $this;
    }

    // Call this in a login page to start session
    public function Start($row, $nuke_prev_sessions = true)
    {
        $this->key = md5($row['personID'] . 'mix it up#@!' . md5(time() . md5(microtime())));
        $this->Put('session_started', time());
        if ($nuke_prev_sessions && config::get_config_item('singleSession')) {
            $this->allocDatabase->query('DELETE FROM sess WHERE personID = %d', $row['personID']);
        }

        $this->allocDatabase->query(
            "INSERT INTO sess (sessID,sessData,personID) VALUES ('%s','%s',%d)",
            $this->key,
            $this->Encode($this->session_data),
            $row['personID']
        );
        $this->Put('username', strtolower($row['username']));
        $this->Put('perms', $row['perms']);
        $this->Put('personID', $row['personID']);
    }

    // Test whether session has started
    public function Started()
    {
        if (!$this->Get('session_started')) {
            return;
        }

        if ($this->Expired()) {
            return;
        }

        return true;
    }

    public function Save()
    {
        if ($this->Expired()) {
            $this->Destroy();
        } elseif ($this->Started()) {
            $this->Put('session_started', time());
            $this->allocDatabase->query(
                "UPDATE sess SET sessData = '%s' WHERE sessID = '%s'",
                $this->Encode($this->session_data),
                $this->key
            );
        }
    }

    public function Destroy()
    {
        if ($this->Started() && $this->key) {
            $this->allocDatabase->query("DELETE FROM sess WHERE sessID = '%s'", $this->key);
        }

        $this->DestroyCookie();
        $this->key = '';
    }

    public function Put($name, $value)
    {
        $this->session_data[$name] = $value;
    }

    public function Get($name)
    {
        return $this->session_data[$name] ?? false;
    }

    public function GetKey()
    {
        return $this->key;
    }

    public function MakeCookie()
    {
        // Set the session cookie
        $rtn = setcookie('alloc_cookie', $this->key, ['expires' => 0, 'path' => '/', 'domain' => '']);
        if (!$rtn) {
            $this->mode = 'get';
        } elseif (!isset($_COOKIE['alloc_cookie'])) {
            $_COOKIE['alloc_cookie'] = $this->key;
        }
    }

    public function DestroyCookie()
    {
        setcookie('alloc_cookie', false, ['expires' => 0, 'path' => '/', 'domain' => '']);
        unset($_COOKIE['alloc_cookie']);
    }

    public function SetTestCookie($val = 'alloc_test_cookie')
    {
        setcookie('alloc_test_cookie', $val, ['expires' => 0, 'path' => '/', 'domain' => '']);
    }

    public function TestCookie()
    {
        return $_COOKIE['alloc_test_cookie'] ?? '';
    }

    public function GetUrl($url = '')
    {
        return $this->url($url);
    }

    public function url($url = ''): string
    {
        $extra = null;
        $url = preg_replace('/[&?]+$/', '', $url);

        if ('get' == $this->mode && (!strpos($url, 'sess=') && $this->key)) {
            $extra = 'sess=' . $this->key . '&';
        }

        if (strpos($url, '?')) {
            $url .= '&';
        } else {
            $url .= '?';
        }

        return $url . $extra;
    }

    public function UseGet()
    {
        $this->mode = 'get';
        $this->DestroyCookie();
        $this->Put('session_mode', $this->mode);
    }

    public function UseCookie()
    {
        $this->mode = 'cookie';
        $this->MakeCookie();
        $this->Put('session_mode', $this->mode);
    }

    // Fetches data given a key
    private function GetSessionData()
    {
        if ($this->key) {
            $row = $this->allocDatabase->qr("SELECT sessData FROM sess WHERE sessID = '%s'", $this->key);

            return $row['sessData'] ?? '';
        }
    }

    // if $this->session_life seconds have passed then session has expired
    private function Expired()
    {
        if (!$this->Get('session_started')) {
            return;
        }

        if (time() <= $this->Get('session_started') + $this->session_life) {
            return;
        }

        return true;
    }

    // add encryption for session_data here
    private function Encode($data): string
    {
        return serialize($data);
    }

    // and add unencryption for session_data here
    private function UnEncode($data)
    {
        return unserialize($data);
    }
}

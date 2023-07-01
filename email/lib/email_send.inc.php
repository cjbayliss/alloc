<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

// Class to handle all emails that alloc sends. Will log emails sent, and will
// not attempt to send email when the server is a dev boxes

class email_send
{
    // If URL has any of these strings in it then the email won't be sent.
    // var $no_email_urls = array("alloc_dev");
    public $no_email_urls = [];

    // If alloc is running on any of these boxes then no emails will be sent!
    public $no_email_hosts = [];

    // Set to true to skip host and url checking
    public $ignore_no_email_hosts = false;

    public $ignore_no_email_urls = false;

    // Actual email variables
    public $body = '';

    public $body_without_attachments = '';

    public $default_headers = '';

    public $from = '';

    public $headers = '';

    public $message_type = '';

    public $subject = '';

    public $to_address = '';

    public $done_top_mime_header = false;

    public $mime_boundary;

    public function __construct($to_address = '', $subject = '', $body = '', $message_type = '')
    {
        $this->default_headers = 'X-Mailer: ' . APPLICATION_NAME . ' ' . APPLICATION_VERSION;
        $to_address && $this->set_to_address($to_address);
        $subject && $this->set_subject($subject);
        $body && $this->set_body($body);
        $message_type && $this->set_message_type($message_type);
    }

    public function set_to_address($to = false)
    {
        $to || ($to = $this->to_address);
        // $to or $to = ALLOC_DEFAULT_TO_ADDRESS; // no
        $this->to_address = $to;
        $this->del_header('to');
    }

    public function set_body($body = false, $body_without_attachments = '')
    {
        $body || ($body = $this->body);
        $this->body = $body;
        $this->body_without_attachments = $body_without_attachments;
    }

    public function set_message_type($message_type = false)
    {
        $message_type || ($message_type = $this->message_type);
        $this->message_type = $message_type;
    }

    public function set_from($from = false)
    {
        $from || ($from = $this->from);
        $from || ($from = APPLICATION_NAME . ' ' . ALLOC_DEFAULT_FROM_ADDRESS);
        $this->add_header('From', $from);
        $this->from = $from;
    }

    public function set_content_type($type = false)
    {
        $type || ($type = 'text/plain; charset=utf-8; format=flowed');
        $this->add_header('Content-Type', $type);
    }

    public function set_subject($subject = false)
    {
        $subject || ($subject = $this->subject);
        $this->subject = $subject;
        $this->del_header('subject');
    }

    public function set_reply_to($email = false)
    {
        $email || ($email = ALLOC_DEFAULT_FROM_ADDRESS);
        $this->add_header('Reply-To', $email);
    }

    public function set_date($date = false)
    {
        // Date: Tue, 07 Jun 2011 15:37:32 +1000
        $date || ($date = date('D, d M Y H:i:s O'));
        $this->add_header('Date', $date);
    }

    public function set_message_id($hash = false)
    {
        $hash && ($hash = '.alloc.key.' . $hash);
        [$usec, $sec] = explode(' ', microtime());
        $time = $sec . $usec;
        $time = preg_replace('~\D~', '', $time); // base_convert only takes nums
        $time = base_convert($time, 10, 36);

        $rand = md5(microtime() . getmypid() . md5(microtime()));
        $rand = base_convert($rand, 16, 36);

        $bits = explode('@', ALLOC_DEFAULT_FROM_ADDRESS);
        $host = str_replace('>', '', $bits[1] ?? '');
        $h = '<' . $time . '.' . $rand . $hash . '@' . $host . '>';
        $this->add_header('Message-ID', $h);

        return $h;
    }

    public function send($use_default_headers = true): bool
    {
        $return_path = null;
        if ($use_default_headers) {
            $this->set_to_address();
            $this->set_body();
            $this->set_message_type();
            $this->set_from();
            $this->set_content_type();
            $this->set_subject();
            $this->set_reply_to();
            $this->set_date();
            $this->set_message_id();
        }

        if ($this->is_valid_url()) {
            // if we've added attachments to the email, end the mime boundary
            if ($this->done_top_mime_header) {
                $this->body .= $this->get_bottom_mime_header();
            }

            $this->to_address || ($this->to_address = null);
            $this->headers = trim($this->headers) . "\n" . trim($this->default_headers);
            $this->headers = str_replace("\r\n", "\n", $this->headers);
            $this->headers = str_replace("\n", PHP_EOL, $this->headers); // according to php.net/mail

            // echo "<pre><br>HEADERS:\n".Page::htmlentities($this->headers)."</pre>";
            // echo "<pre><br>TO:\n".Page::htmlentities($this->to_address)."</pre>";
            // echo "<pre><br>SUBJECT:\n".Page::htmlentities($this->subject)."</pre>";
            // echo "<pre><br>BODY:\n".Page::htmlentities($this->body)."</pre>";

            if (defined('ALLOC_DEFAULT_RETURN_PATH_ADDRESS') && ALLOC_DEFAULT_RETURN_PATH_ADDRESS) {
                $return_path = '-f' . ALLOC_DEFAULT_RETURN_PATH_ADDRESS;
            }

            $result = mail((string) $this->to_address, $this->subject, $this->body, $this->headers, $return_path);
            if ($result) {
                $this->log();

                return true;
            }
        }

        return false;
    }

    public function set_headers($headers = '')
    {
        $headers || ($headers = $this->headers);
        $headers = preg_replace("/\r?\n\\s+/", ' ', $headers);
        $this->headers = $headers;
    }

    public function get_headers()
    {
        return $this->headers;
    }

    public function add_header($header, $value = '', $replace = 1)
    {
        if ($replace) {
            $this->del_header($header);
        }

        $this->headers = trim($this->headers) . "\n" . $header . ': ' . $value;
    }

    public function del_header($header)
    {
        $this->headers = preg_replace("/\r?\n" . $header . ':\\s*.*/i', '', $this->headers);
    }

    public function get_header($header)
    {
        preg_match("/\r?\n" . $header . ':(.*)/i', $this->headers, $matches);

        return $matches[1];
    }

    public function header_exists($header)
    {
        return preg_match("/\r?\n" . $header . ':(.*)/i', $this->headers);
    }

    public function is_valid_url()
    {
        $dont_send = null;
        // Validate against particular hosts
        if (in_array($_SERVER['SERVER_NAME'], $this->no_email_hosts)) {
            $dont_send = true;
        }

        $this->ignore_no_email_hosts && ($dont_send = false);

        // Validate against particular bits in the url
        foreach ($this->no_email_urls as $no_email_url) {
            preg_match('/' . $no_email_url . '/', $_SERVER['SCRIPT_FILENAME']) && ($dont_send = true);
        }

        $this->ignore_no_email_urls && ($dont_send = false);

        // Invert return
        return !$dont_send;
    }

    public function log()
    {
        $current_user = &singleton('current_user');
        $sentEmailLog = new sentEmailLog();
        if (!($to = $this->to_address) && !($to = $this->get_header('Cc'))) {
            $to = $this->get_header('Bcc');
        }

        $sentEmailLog->set_value('sentEmailTo', $to);
        $sentEmailLog->set_value('sentEmailSubject', $this->subject);
        $sentEmailLog->set_value('sentEmailBody', substr($this->body_without_attachments, 0, 65000)); // length of a TEXT column
        $sentEmailLog->set_value('sentEmailHeader', $this->headers);
        $sentEmailLog->set_value('sentEmailType', $this->message_type);
        $sentEmailLog->save();
    }

    public function get_mime_boundary()
    {
        // This function will generate a new mime boundary
        if (!$this->mime_boundary) {
            $rand = md5(time() . microtime());
            $this->mime_boundary = 'alloc' . time() . $rand;
        }

        return $this->mime_boundary;
    }

    public function get_top_mime_header()
    {
        if (!$this->done_top_mime_header) {
            $mime_boundary = $this->get_mime_boundary();
            $header = '--' . $mime_boundary;
            $header .= "\nContent-Type: text/plain; charset=utf-8; format=flowed";
            $header .= "\nContent-Disposition: inline";
            $header .= "\n";
            $header .= "\n";
            $this->done_top_mime_header = true;

            return $header;
        }
    }

    public function get_bottom_mime_header(): string
    {
        return "\n\n--" . $this->get_mime_boundary() . '--';
    }

    public function add_attachment($file)
    {
        if (file_exists($file) && is_readable($file) && filesize($file)) {
            $mime_boundary = $this->get_mime_boundary();
            $this->add_header('MIME-Version', '1.0');
            $this->add_header('Content-Type', 'multipart/mixed; boundary="' . $mime_boundary . '"');
            $this->add_header('Content-Disposition', 'inline');

            // Read the file to be attached ('rb' = read binary)
            $fh = fopen($file, 'rb');
            $data = fread($fh, filesize($file));
            fclose($fh);

            $mimetype = mime_content_type($file);

            // Base64 encode the file data
            $data = chunk_split(base64_encode($data));
            $name = basename($file);

            $this->body = $this->get_top_mime_header() . $this->body;
            $this->body .= "\n\n--" . $mime_boundary;
            $this->body .= "\nContent-Type: " . $mimetype . '; name="' . $name . '"';
            $this->body .= "\nContent-Disposition: attachment; filename=\"" . $name . '"';
            $this->body .= "\nContent-Transfer-Encoding: base64";
            $this->body .= "\n\n" . $data;
        }
    }

    public function get_header_mime_boundary()
    {
        // This function will parse the header for a mime boundary
        $content_type = $this->get_header('Content-Type');
        // If the email is a multipart, ie has attachments
        if (preg_match('/multipart/i', $content_type) && preg_match('/boundary/i', $content_type)) {
            // Suck out the mime boundary
            preg_match('/boundary="?([^"]*)"?/i', $content_type, $matches);

            return $matches[1];
        }
    }
}

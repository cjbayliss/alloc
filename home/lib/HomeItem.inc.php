<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class HomeItem
{
    private $has_config;

    private $help_topic;

    private $label;

    private $module;

    private string $name;

    private $seq;

    private string $width = 'standard';

    public bool $print;

    public function __construct($name, $label, $module, $width = 'standard', $seq = 0, $print = true)
    {
        $this->name = $name;

        $this->label = $label;

        $this->module = $module;

        $this->width = $width;

        $this->seq = $seq;

        $this->print = $print;
    }

    public function get_template_dir(): string
    {
        return ALLOC_MOD_DIR . $this->module . '/templates/';
    }

    public function get_seq()
    {
        return $this->seq;
    }

    public function show()
    {
        return $this->getHTML();
    }

    public function visible(): bool
    {
        return true;
    }

    public function render(): bool
    {
        return false;
    }

    public function get_label()
    {
        return $this->label;
    }

    public function get_title()
    {
        return $this->get_label();
    }

    public function get_width()
    {
        return $this->width;
    }

    public function get_help()
    {
        if ($this->help_topic) {
            Page::help($this->help_topic);
        }
    }

    public function get_config()
    {
        return $this->has_config;
    }

    public function get_name(): string
    {
        return $this->name;
    }

    public function getHTML(): string
    {
        return (string) null;
    }
}

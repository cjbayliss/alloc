<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class DatabaseField
{
    public $classname = 'DatabaseField';

    public $name;

    public $value;

    public $label;

    public $empty_to_null = true;

    public $currency;

    public $type;

    public $audit = false;

    public $write_perm_name = 0;

    // Name of a permission a user must have to write to this field, if any.  E.g. "admin"
    public $read_perm_name = 0;      // Name of the permission a user must have to read this field, if any.  E.g. "read details"

    public function __construct($name = '', $options = [])
    {
        $this->name = $name;
        $this->label = $name;

        if (!is_array($options)) {
            $options = [];
            // echo "<br>".$this->name;
        }

        reset($options);
        foreach ($options as $option_name => $option_value) {
            $this->{$option_name} = $option_value;
        }
    }

    public function set_value($value, $source = SRC_VARIABLE)
    {
        if (isset($value) || false == $this->empty_to_null) {
            $this->value = $value;
        }
    }

    public function has_value()
    {
        return null !== $this->value && (bool) strlen($this->value);
    }

    public function get_name()
    {
        return $this->name;
    }

    public function is_audited()
    {
        return $this->audit;
    }

    public function get_value($dest = DST_VARIABLE, $parent = null)
    {
        if (DST_DATABASE == $dest) {
            if ((null !== $this->value && (bool) strlen($this->value)) || false == $this->empty_to_null) {
                return "'" . db_esc($this->value) . "'";
            }

            return 'NULL';
        }

        if (DST_HTML_DISPLAY == $dest) {
            if ('money' == $this->type && (null !== $this->value && (bool) strlen($this->value))) {
                $c = $parent->currency;
                if ($this->currency && isset($parent->data_fields[$this->currency])) {
                    $c = $parent->get_value($this->currency);
                }

                if (!$c) {
                    alloc_error('db_field::get_value(): No currency specified for ' . $parent->classname . '.' . $this->name . ' (currency:' . $c . ')');
                } elseif ($this->value == $parent->all_row_fields[$this->name]) {
                    return Page::money($c, $this->value, '%mo');
                }
            }

            return Page::htmlentities($this->value);
        }

        return $this->value;
    }

    public function clear_value()
    {
        unset($this->value);
    }

    public function validate($parent)
    {
        global $TPL;
        if ($parent->doMoney && 'money' == $this->type) {
            $c = $parent->currency;
            if ($this->currency && isset($parent->data_fields[$this->currency])) {
                $c = $parent->get_value($this->currency);
            }

            if (!$c) {
                return 'db_field::validate(): No currency specified for ' . $parent->classname . '.' . $this->name . ' (currency:' . $c . ')';
            }

            if (
                isset($parent->all_row_fields)
                && null !== $this->value
                && !in_array($this->value, $parent->all_row_fields)
            ) {
                $this->set_value(Page::money($c, $this->value, '%mi'));
            }
        }
    }
}

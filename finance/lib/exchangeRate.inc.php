<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class exchangeRate extends db_entity
{
    public $data_table = "exchangeRate";
    public $display_field_name = "exchangeRate";
    public $key_field = "exchangeRateID";
    public $data_fields = [
        "exchangeRateCreatedDate",
        "exchangeRateCreatedTime",
        "fromCurrency",
        "toCurrency",
        "exchangeRate",
    ];

    public static function get_er($from, $to, $date = "")
    {
        $row = [];
        static $cache;
        if (isset($cache[$from][$to][$date]) && (bool)strlen($cache[$from][$to][$date])) {
            return $cache[$from][$to][$date];
        }
        $dballoc = new db_alloc();
        if ($date) {
            $q = unsafe_prepare(
                "SELECT *
                   FROM exchangeRate
                  WHERE exchangeRateCreatedDate = '%s'
                    AND fromCurrency = '%s'
                    AND toCurrency = '%s'
                ",
                $date,
                $from,
                $to
            );
            $dballoc->query($q);
            $row = $dballoc->row();
        }

        if (!$row) {
            $q = unsafe_prepare(
                "SELECT *
                   FROM exchangeRate
                  WHERE fromCurrency = '%s'
                    AND toCurrency = '%s'
               ORDER BY exchangeRateCreatedTime DESC
                  LIMIT 1
                ",
                $from,
                $to
            );
            $dballoc->query($q);
            $row = $dballoc->row();
        }
        $cache[$from][$to][$date] = $row["exchangeRate"];
        return $row["exchangeRate"];
    }

    public static function convert($currency, $amount, $destCurrency = false, $date = false, $format = "%m")
    {
        $date or $date = date("Y-m-d");
        $destCurrency or $destCurrency = config::get_config_item("currency");
        $er = exchangeRate::get_er($currency, $destCurrency, $date);
        return page::money($destCurrency, $amount * $er, $format);
    }

    public static function update_rate($from, $to)
    {
        $rate = get_exchange_rate($from, $to);
        if ($rate) {
            $exchangeRate = new exchangeRate();
            $exchangeRate->set_value("exchangeRateCreatedDate", date("Y-m-d"));
            $exchangeRate->set_value("fromCurrency", $from);
            $exchangeRate->set_value("toCurrency", $to);
            $exchangeRate->set_value("exchangeRate", $rate);
            $exchangeRate->save();
            return $from . " -> " . $to . ":" . $rate . " ";
        } else {
            echo date("Y-m-d H:i:s") . "Unable to obtain exchange rate information for " . $from . " to " . $to . "!";
        }
    }

    public static function download()
    {
        $rtn = [];
        // Get default currency
        $default_currency = config::get_config_item("currency");

        // Get list of active currencies
        $meta = new meta("currencyType");
        $currencies = $meta->get_list();

        foreach ((array)$currencies as $code => $currency) {
            if ($code == $default_currency) {
                continue;
            }
            if ($ret = exchangeRate::update_rate($code, $default_currency)) {
                $rtn[] = $ret;
            }
            if ($ret = exchangeRate::update_rate($default_currency, $code)) {
                $rtn[] = $ret;
            }
        }
        return $rtn;
    }
}

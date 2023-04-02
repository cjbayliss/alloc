<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="Expires" content="Tue, 27 Jul 1997 05:00:00 GMT"> 
    <meta http-equiv="Pragma" content="no-cache">
    <title>{=$main_alloc_title}</title>
    <style type="text/css" media="screen">body { font-size:{page::default_font_size()}px }</style>
    <link rel="StyleSheet" href="/css/{page::stylesheet()}" type="text/css" media="screen">
    <link rel="StyleSheet" href="/css/calendar.css" type="text/css" media="screen">
    <link rel="StyleSheet" href="/css/jqplot.css" type="text/css" media="screen">
    <link rel="StyleSheet" href="/css/font.css" type="text/css" media="screen">
    <link rel="StyleSheet" href="/css/print.css" type="text/css" media="print">
    <script type="text/javascript" src="/javascript/jumbo.js"></script>
    <script type="text/javascript">
      // return a value to be used in javascript, that is set from PHP
      function get_alloc_var(key) {
      var values = {
                    "url"               : "{$script_path}"
                   ,"side_by_side_link" : "{$_REQUEST.sbs_link}"
                   ,"tax_percent"       : "{echo config::get_config_item('taxPercent')}"
                   ,"cal_first_day"     : "{echo config::get_config_item('calendarFirstDay')}"
                   ,"show_filters"      : "{print is_object($current_user) ? $current_user->prefs["showFilters"] : ""}"
                   }
      return values[key];
    }
    </script>
  </head>
  <body id="{$body_id}" class="{$current_user->prefs["privateMode"] and print "obfus"}">

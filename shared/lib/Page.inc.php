<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class Page
{
    private array $urls = [
        'url_alloc_absence'                        => 'person/absence.php',
        'url_alloc_addItem'                        => 'item/addItem.php',
        'url_alloc_announcement'                   => 'announcement/announcement.php',
        'url_alloc_announcementList'               => 'announcement/announcementList.php',
        'url_alloc_checkRepeat'                    => 'finance/checkRepeat.php',
        'url_alloc_client'                         => 'client/client.php',
        'url_alloc_clientList'                     => 'client/clientList.php',
        'url_alloc_comment'                        => 'comment/comment.php',
        'url_alloc_commentSummary'                 => 'comment/summary.php',
        'url_alloc_commentTemplate'                => 'comment/commentTemplate.php',
        'url_alloc_commentTemplateList'            => 'comment/commentTemplateList.php',
        'url_alloc_config'                         => 'config/config.php',
        'url_alloc_configEdit'                     => 'config/configEdit.php',
        'url_alloc_configHtml'                     => 'config/configHtml.php',
        'url_alloc_configHtmlList'                 => 'config/configHtmlList.php',
        'url_alloc_costtime'                       => 'tools/costtime.php',
        'url_alloc_delDoc'                         => 'shared/del_attachment.php',
        'url_alloc_downloadComments'               => 'email/downloadComments.php',
        'url_alloc_downloadEmail'                  => 'email/downloadEmail.php',
        'url_alloc_expenseForm'                    => 'finance/expenseForm.php',
        'url_alloc_expenseFormList'                => 'finance/expenseFormList.php',
        'url_alloc_expenseUpload'                  => 'finance/expenseUpload.php',
        'url_alloc_expenseUploadResults'           => 'finance/expenseUploadResults.php',
        'url_alloc_exportDoc'                      => 'shared/get_export.php',
        'url_alloc_fetchBody'                      => 'email/fetchBody.php',
        'url_alloc_financeMenu'                    => 'finance/menu.php',
        'url_alloc_getDoc'                         => 'shared/get_attachment.php',
        'url_alloc_getHelp'                        => 'help/getHelp.php',
        'url_alloc_getMimePart'                    => 'shared/get_mime_part.php',
        'url_alloc_help'                           => ALLOC_MOD_DIR . 'help' . DIRECTORY_SEPARATOR,
        'url_alloc_helpfile'                       => 'help/help.html',
        'url_alloc_history'                        => 'home/history.php',
        'url_alloc_home'                           => 'home/home.php',
        'url_alloc_images'                         => 'images/',
        'url_alloc_importCSV'                      => 'project/parseCSV.php',
        'url_alloc_inbox'                          => 'email/inbox.php',
        'url_alloc_index'                          => 'index.php',
        'url_alloc_invoice'                        => 'invoice/invoice.php',
        'url_alloc_invoiceList'                    => 'invoice/invoiceList.php',
        'url_alloc_invoicePrint'                   => 'invoice/invoicePrint.php',
        'url_alloc_invoiceRepeat'                  => 'invoice/invoiceRepeat.php',
        'url_alloc_item'                           => 'item/item.php',
        'url_alloc_loanAndReturn'                  => 'item/loanAndReturn.php',
        'url_alloc_loans'                          => 'item/itemLoan.php',
        'url_alloc_login'                          => 'login/login.php',
        'url_alloc_logo'                           => 'shared/logo.php',
        'url_alloc_logout'                         => 'login/logout.php',
        'url_alloc_menuSubmit'                     => 'shared/menuSubmit.php',
        'url_alloc_metaEdit'                       => 'config/metaEdit.php',
        'url_alloc_permission'                     => 'security/permission.php',
        'url_alloc_permissionList'                 => 'security/permissionList.php',
        'url_alloc_person'                         => 'person/person.php',
        'url_alloc_personGraph'                    => 'project/personGraph.php',
        'url_alloc_personGraphImage'               => 'project/personGraphImage.php',
        'url_alloc_personList'                     => 'person/personList.php',
        'url_alloc_personSkillAdd'                 => 'person/personSkillAdd.php',
        'url_alloc_personSkillMatrix'              => 'person/personSkillMatrix.php',
        'url_alloc_product'                        => 'sale/product.php',
        'url_alloc_productList'                    => 'sale/productList.php',
        'url_alloc_productSale'                    => 'sale/productSale.php',
        'url_alloc_productSaleList'                => 'sale/productSaleList.php',
        'url_alloc_project'                        => 'project/project.php',
        'url_alloc_projectGraph'                   => 'project/projectGraph.php',
        'url_alloc_projectGraphImage'              => 'project/projectGraphImage.php',
        'url_alloc_projectList'                    => 'project/projectList.php',
        'url_alloc_projectPerson'                  => 'project/projectPerson.php',
        'url_alloc_reconciliationReport'           => 'finance/reconciliationReport.php',
        'url_alloc_reminder'                       => 'reminder/reminder.php',
        'url_alloc_reminderList'                   => 'reminder/reminderList.php',
        'url_alloc_report'                         => 'report/report.php',
        'url_alloc_saveProjectPerson'              => 'project/saveProjectPerson.php',
        'url_alloc_search'                         => 'search/search.php',
        'url_alloc_searchTransaction'              => 'finance/searchTransaction.php',
        'url_alloc_settings'                       => 'shared/settings.php',
        'url_alloc_star'                           => 'shared/star.php',
        'url_alloc_starList'                       => 'shared/starList.php',
        'url_alloc_styles'                         => ALLOC_MOD_DIR . 'css/',
        'url_alloc_task'                           => 'task/task.php',
        'url_alloc_taskCalendar'                   => 'calendar/calendar.php',
        'url_alloc_taskList'                       => 'task/taskList.php',
        'url_alloc_taskListCSV'                    => 'task/taskListCSV.php',
        'url_alloc_taskListPrint'                  => 'task/taskListPrint.php',
        'url_alloc_tf'                             => 'finance/tf.php',
        'url_alloc_tfList'                         => 'finance/tfList.php',
        'url_alloc_timeSheet'                      => 'time/timeSheet.php',
        'url_alloc_timeSheetGraph'                 => 'time/timeSheetGraph.php',
        'url_alloc_timeSheetItem'                  => 'time/timeSheetItem.php',
        'url_alloc_timeSheetList'                  => 'time/timeSheetList.php',
        'url_alloc_timeSheetPrint'                 => 'time/timeSheetPrint.php',
        'url_alloc_tools'                          => 'tools/menu.php',
        'url_alloc_transaction'                    => 'finance/transaction.php',
        'url_alloc_transactionGroup'               => 'finance/transactionGroup.php',
        'url_alloc_transactionList'                => 'finance/transactionList.php',
        'url_alloc_transactionPendingList'         => 'finance/transactionPendingList.php',
        'url_alloc_transactionRepeat'              => 'finance/transactionRepeat.php',
        'url_alloc_transactionRepeatList'          => 'finance/transactionRepeatList.php',
        'url_alloc_updateClientDupes'              => 'client/updateClientDupes.php',
        'url_alloc_updateCommentTemplate'          => 'comment/updateCommentTemplate.php',
        'url_alloc_updateCopyProjectList'          => 'project/updateProjectList.php',
        'url_alloc_updateCostPrice'                => 'sale/updateCostPrice.php',
        'url_alloc_updateEstimatorPersonList'      => 'task/updateEstimatorPersonList.php',
        'url_alloc_updateInterestedParties'        => 'task/updateInterestedParties.php',
        'url_alloc_updateManagerPersonList'        => 'task/updateManagerPersonList.php',
        'url_alloc_updateParentTasks'              => 'task/updateParentTasks.php',
        'url_alloc_updatePersonList'               => 'task/updatePersonList.php',
        'url_alloc_updateProjectClientContactList' => 'project/updateProjectClientContactList.php',
        'url_alloc_updateProjectClientList'        => 'project/updateProjectClientList.php',
        'url_alloc_updateProjectList'              => 'task/updateProjectList.php',
        'url_alloc_updateProjectListByClient'      => 'time/updateProjectListByClient.php',
        'url_alloc_updateProjectPersonRate'        => 'project/updateProjectPersonRate.php',
        'url_alloc_updateRecipients'               => 'comment/updateRecipients.php',
        'url_alloc_updateTaskDupes'                => 'task/updateTaskDupes.php',
        'url_alloc_updateTaskName'                 => 'task/updateTaskName.php',
        'url_alloc_updateTFList'                   => 'finance/updateTFList.php',
        'url_alloc_updateTimeSheetHome'            => 'time/updateTimeSheetHome.php',
        'url_alloc_updateTimeSheetProjectList'     => 'time/updateProjectListByStatus.php',
        'url_alloc_updateTimeSheetTaskList'        => 'time/updateTimeSheetTaskList.php',
        'url_alloc_wagesUpload'                    => 'finance/wagesUpload.php',
        'url_alloc_weeklyTime'                     => 'time/weeklyTime.php',
    ];

    // Initializer
    public function __construct()
    {
    }

    public static function header(string $main_alloc_title = '')
    {
        global $TPL;
        $script_path = null;
        $sideBySideLink = null;

        $current_user = &singleton('current_user');
        $page = new Page();
        $config = new config();

        // TODO: remove $TPL global variable
        if ('' === $main_alloc_title) {
            $main_alloc_title = $TPL['main_alloc_title'];
        }

        if (null === $script_path) {
            $script_path = $TPL['script_path'];
        }

        $login = '';
        if ('allocPSA login' === $main_alloc_title) {
            $login = 'login';
        }

        $title = $page->escape($main_alloc_title);
        $fontSize = $page->escape((string) $page->default_font_size()) . 'px';
        $css = $page->stylesheet();

        $showFilters = is_object($current_user) ? ($current_user->prefs['showFilters'] ?? '') : '';
        $taxPercent = $page->escape((string) $config->get_config_item('taxPercent'));
        $firstDayOfWeek = $page->escape((string) $config->get_config_item('calendarFirstDay'));
        if ([] !== $_REQUEST && !empty($_REQUEST['sbs_link'])) {
            $sideBySideLink = $page->escape($_REQUEST['sbs_link']);
        }

        $privateMode = empty($current_user->prefs['privateMode']) ? '' : 'obfus';

        echo <<<HTML
            <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
            <html>
              <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                <meta http-equiv="Expires" content="Tue, 27 Jul 1997 05:00:00 GMT"> 
                <meta http-equiv="Pragma" content="no-cache">
                <title>{$title}</title>
                <style type="text/css" media="screen">body { font-size: {$fontSize} }</style>
                <link rel="StyleSheet" href="/css/{$css}" type="text/css" media="screen">
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
                               ,"side_by_side_link" : "{$sideBySideLink}"
                               ,"tax_percent"       : "{$taxPercent}"
                               ,"cal_first_day"     : "{$firstDayOfWeek}"
                               ,"show_filters"      : "{$showFilters}"
                               }
                  return values[key];
                }
                </script>
              </head>
              <body id="{$login}" class="{$privateMode}">
            HTML;
    }

    public static function footer()
    {
        $current_user = &singleton('current_user');

        echo <<<'HTML'
                        </div> <!-- end #main2 -->
                    </div> <!-- end #main -->
                </body>
            </html>
            HTML;

        // close page
        $session = new Session();
        $session->Save();
        if (!is_object($current_user)) {
            return;
        }

        if (!method_exists($current_user, 'get_id')) {
            return;
        }

        if (!$current_user->get_id()) {
            return;
        }

        $current_user->store_prefs();
    }

    public static function tabs()
    {
        $menu_links = [];
        global $TPL;
        $current_user = &singleton('current_user');
        $config = new config();
        $tabs = config::get_config_item('allocTabs');

        $menu_links['home'] = [
            'name'   => 'Home',
            'url'    => $TPL['url_alloc_home'],
            'module' => 'home',
        ];
        $menu_links['client'] = [
            'name'   => 'Clients',
            'url'    => $TPL['url_alloc_clientList'],
            'module' => 'client',
        ];
        $menu_links['project'] = [
            'name'   => 'Projects',
            'url'    => $TPL['url_alloc_projectList'],
            'module' => 'project',
        ];
        $menu_links['task'] = [
            'name'   => 'Tasks',
            'url'    => $TPL['url_alloc_taskList'],
            'module' => 'task',
        ];
        $menu_links['time'] = [
            'name'   => 'Time',
            'url'    => $TPL['url_alloc_timeSheetList'],
            'module' => 'time',
        ];
        $menu_links['invoice'] = [
            'name'   => 'Invoices',
            'url'    => $TPL['url_alloc_invoiceList'],
            'module' => 'invoice',
        ];
        $menu_links['sale'] = [
            'name'   => 'Sales',
            'url'    => $TPL['url_alloc_productSaleList'],
            'module' => 'sale',
        ];
        $menu_links['person'] = [
            'name'   => 'People',
            'url'    => $TPL['url_alloc_personList'],
            'module' => 'person',
        ];

        if (
            have_entity_perm('inbox', PERM_READ, $current_user)
            && config::get_config_item('allocEmailHost')
        ) {
            $menu_links['inbox'] = [
                'name'   => 'Inbox',
                'url'    => $TPL['url_alloc_inbox'],
                'module' => 'email',
            ];
        }

        $menu_links['tools'] = [
            'name'   => 'Tools',
            'url'    => $TPL['url_alloc_tools'],
            'module' => 'tools',
        ];

        $x = -1;
        $done = null;
        $url = '';
        foreach ($menu_links as $key => $arr) {
            if (in_array($key, $tabs) && (has($key) || 'tools' === $key)) {
                $name = $arr['name'];
                $url = $arr['url'];
                $activeTab = '';
                if (
                    preg_match('/' . str_replace('/', '\\/', $_SERVER['PHP_SELF']) . '/', $url)
                    || preg_match('/' . $arr['module'] . '/', $_SERVER['PHP_SELF'])
                    && !$done
                ) {
                    $activeTab = 'active';
                    $done = true;
                }

                $left = $x . 'px';
                echo <<<HTML
                    <a href="{$url}" class="tab {$activeTab} noselect" style="left: {$left};" unselectable="on">{$name}</a>
                    HTML;

                $x += 70;
                if (
                    $activeTab && 'Home' == $name
                    || (!empty($current_user->prefs['customizedTheme2'])
                        && 4 != $current_user->prefs['customizedTheme2'])
                ) {
                    echo <<<'HTML'
                        <style>
                        div#main {
                            border-radius: 0 0.2rem 0.2rem 0.2rem !important;
                        }
                        </style>
                        HTML;
                }
            }
        }
    }

    public static function toolbar()
    {
        $url_alloc_menuSubmit = null;
        $category_options = null;
        $history_options = null;
        $needle = null;
        $sessID = null;
        $str = [];
        $r = [];
        global $TPL;
        $current_user = &singleton('current_user');
        $page = new Page();
        $db = new AllocDatabase();
        has('task') && ($str[] = '<option value="create_' . $TPL['url_alloc_task'] . '">New Task</option>');
        has('time') && ($str[] = '<option value="create_' . $TPL['url_alloc_timeSheet'] . '">New Time Sheet</option>');
        has('task') && ($str[] = '<option value="create_' . $TPL['url_alloc_task'] . 'tasktype=Fault">New Fault</option>');
        has('task') && ($str[] = '<option value="create_' . $TPL['url_alloc_task'] . 'tasktype=Message">New Message</option>');
        if (has('project') && have_entity_perm('project', PERM_CREATE, $current_user)) {
            $str[] = '<option value="create_' . $TPL['url_alloc_project'] . '">New Project</option>';
        }

        has('client') && ($str[] = '<option value="create_' . $TPL['url_alloc_client'] . '">New Client</option>');
        has('finance') && ($str[] = '<option value="create_' . $TPL['url_alloc_expenseForm'] . '">New Expense Form</option>');
        has('reminder') && ($str[] = '<option value="create_' . $TPL['url_alloc_reminder'] . 'parentType=general&step=2">New Reminder</option>');
        if (has('person') && have_entity_perm('person', PERM_CREATE, $current_user)) {
            $str[] = '<option value="create_' . $TPL['url_alloc_person'] . '">New Person</option>';
        }

        has('item') && ($str[] = '<option value="create_' . $TPL['url_alloc_loanAndReturn'] . '">New Item Loan</option>');
        $str[] = '<option value="" disabled="disabled">--------------------';
        $history = new History();
        $q = $history->get_history_query('DESC');
        $db = new AllocDatabase();
        $db->query($q);
        while ($row = $db->row()) {
            $r['history_' . $row['value']] = $row['the_label'];
        }

        $str[] = Page::select_options($r, $_POST['search_action'] ?? '');
        $TPL['history_options'] = implode("\n", $str);
        $TPL['category_options'] = Page::get_category_options($_POST['search_action'] ?? '');
        $TPL['needle'] = $_POST['needle'] ?? '';

        // FIXME: 😞
        if (is_array($TPL)) {
            extract($TPL, EXTR_OVERWRITE);
        }

        $logo = $page->escape(config::get_config_logo());

        echo <<<HTML
            <table id="menu" cellpadding="0" cellspacing="0">
              <tr>
                <td style="font-size:145%; text-shadow:#fff 1px 1px 1px;">
                  {$logo}
                </td>
                <td class="nobr bottom" style="width:1%;">
                  <form action="{$url_alloc_menuSubmit}" method="get" id="form_search">
                    <select name="search_action" id="search_action" style="width:9em;">
                      {$category_options}
                      <option value="" disabled="disabled">--------------------
                        {$history_options}
                    </select>
                    <input size="40" type="text" name="needle" id="menu_form_needle" value="{$needle}">
                    <input type="hidden" name="sessID" value="{$sessID}">
                    <input type="submit" value="search" style="display:none"> <!-- for w3m -->
                  </form>
                </td>
              </tr>
            </table>

            <div id="tabs">
            HTML;

        self::tabs();

        $extraLinks = self::extra_links();
        echo <<<HTML
              <p id="extra_links">{$extraLinks}</p>
            </div>

            <div id="main">
              <div id="main2"><!-- another div nested for padding -->
            HTML;

        echo self::messages();
    }

    public static function extra_links(): string
    {
        $current_user = &singleton('current_user');
        global $TPL;
        global $sess;
        $str = '<a href="' . $TPL['url_alloc_starList'] . '" class="icon-star"></a>&nbsp;&nbsp;&nbsp;';
        $str .= $current_user->get_link() . '&nbsp;&nbsp;&nbsp;';
        if (defined('PAGE_IS_PRINTABLE') && PAGE_IS_PRINTABLE) {
            $sess || ($sess = new Session());
            $str .= '<a href="' . $sess->url($_SERVER['REQUEST_URI']) . 'media=print">Print</a>&nbsp;&nbsp;&nbsp;';
        }

        if (have_entity_perm('config', PERM_UPDATE, $current_user, true)) {
            $str .= '<a href="' . $TPL['url_alloc_config'] . '">Setup</a>&nbsp;&nbsp;&nbsp;';
        }

        $url = $sess->url('../help/help.php?topic=' . $TPL['alloc_help_link_name']);
        $str .= '<a href="' . $url . '">Help</a>&nbsp;&nbsp;&nbsp;';
        $url = $TPL['url_alloc_logout'];

        return $str . ('<a href="' . $url . '">Logout</a>');
    }

    public static function messages()
    {
        $class_to_icon = [];
        $msg = [];
        global $TPL;

        $class_to_icon['good'] = 'icon-ok-sign';
        $class_to_icon['bad'] = 'icon-exclamation-sign';
        $class_to_icon['help'] = 'icon-info-sign';

        $search = [
            '&lt;br&gt;',
            '&lt;br /&gt;',
            '&lt;b&gt;',
            '&lt;/b&gt;',
            '&lt;u&gt;',
            '&lt;/u&gt;', '\\',
        ];
        $replace = [
            '<br>',
            '<br />',
            '<b>',
            '</b>',
            '<u>',
            '</u>', '',
        ];

        $types = [
            'message'             => 'bad',
            'message_good'        => 'good',
            'message_help'        => 'help',
            'message_good_no_esc' => 'good',
            'message_help_no_esc' => 'help',
        ];

        foreach (array_keys($types) as $type) {
            $str = '';
            if (!empty($TPL[$type])) {
                $str = is_array($TPL[$type]) ? implode('<br>', $TPL[$type]) : $TPL[$type];
            }

            if (!empty($_GET[$type])) {
                $str = is_array($_GET[$type]) ? implode('<br>', $_GET[$type]) : $_GET[$type];
            }

            if (in_str('no_esc', $type)) {
                $str && ($msg[$type] = $str);
            } else {
                $str && ($msg[$type] = str_replace($search, $replace, Page::htmlentities($str)));
            }
        }

        if (is_array($msg) && count($msg)) {
            $str = '<div style="text-align:center;"><div class="message corner" style="width:60%;">';
            $str .= '<table cellspacing="0">';
            foreach ($msg as $type => $info) {
                $class = $types[$type];
                $str .= '<tr>';
                $str .= "<td class='" . $class . "' width='1%' style='vertical-align:top;padding:6px;font-size:150%;'>";
                $str .= "<i class='" . $class_to_icon[$class] . "'></i><td/>";
                $str .= "<td class='" . $class . "' width='99%' style='vertical-align:top;padding-top:11px;text-align:left;font-weight:bold;'>" . $info . '</td></tr>';
            }

            $str .= '</table>';
            $str .= '</div></div>';
        }

        return $str;
    }

    public static function get_category_options($category = '')
    {
        $category_options = [];
        has('task') && ($category_options['search_tasks'] = 'Search Tasks');
        has('project') && ($category_options['search_projects'] = 'Search Projects');
        has('time') && ($category_options['search_time'] = 'Search Time Sheets');
        has('client') && ($category_options['search_clients'] = 'Search Clients');
        has('comment') && ($category_options['search_comment'] = 'Search Comments');
        has('item') && ($category_options['search_items'] = 'Search Items');
        has('finance') && ($category_options['search_expenseForm'] = 'Search Expense Forms');

        return Page::select_options($category_options, $category);
    }

    public static function help($topic, $hovertext = '')
    {
        $page = new Page();
        $img = null;
        $str = '';

        $file = $page->getURL('url_alloc_help') . $topic . '.html';
        if (file_exists($file)) {
            $str = $page->prepare_help_string(@file_get_contents($file));
        }

        if (!empty($str)) {
            $img = "<div id='help_button_" . $topic . "' style='display:inline;'><a href=\"" . $page->getURL('url_alloc_getHelp') . '?topic=' . $topic . '" target="_blank">';
            $img .= "<img border='0' class='help_button' onmouseover=\"help_text_on('help_button_" . $topic . "','" . $str . "');\" onmouseout=\"help_text_off('help_button_" . $topic . "');\" src=\"";
            $img .= $page->getURL('url_alloc_images') . 'help.gif" alt="Help" /></a></div>';
        } elseif ($topic) {
            $str = $page->prepare_help_string($topic);
            $img = "<div id='help_button_" . md5($topic) . "' style='display:inline;'>";
            if ($hovertext) {
                $img .= "<span onmouseover=\"help_text_on('help_button_" . md5($topic) . "','" . $str . "');\" onmouseout=\"help_text_off('help_button_" . md5($topic) . "');\">";
                $img .= $hovertext . '</span>';
            } else {
                $img .= "<img border='0' class='help_button' onmouseover=\"help_text_on('help_button_" . md5($topic) . "','" . $str . "');\" ";
                $img .= 'onmouseout="help_text_off(\'help_button_' . md5($topic) . '\');" src="' . $page->getURL('url_alloc_images') . 'help.gif" alt="Help" />';
            }

            $img .= '</div>';
        }

        return $img;
    }

    public static function prepare_help_string($str)
    {
        $str = Page::htmlentities(addslashes($str));
        $str = str_replace("\r", ' ', $str);

        return str_replace("\n", ' ', $str);
    }

    public static function textarea($name, $default_value = '', $ops = []): string
    {
        $attrs = [];
        $heights = [
            'small'  => 40,
            'medium' => 100,
            'large'  => 340,
            'jumbo'  => 440,
        ];
        $height = $ops['height'] ?? 'small';

        $cols = $ops['cols'] ?? 0;
        if (!isset($ops['width']) && !isset($ops['cols'])) {
            $cols = 85;
        }

        $attrs['id'] = $name;
        $attrs['name'] = $name;
        $attrs['wrap'] = 'virtual';
        $cols && ($attrs['cols'] = $cols);
        $attrs['style'] = 'height:' . $heights[$height] . 'px';
        if (isset($ops['width'])) {
            $attrs['style'] .= '; width:' . $ops['width'];
        }

        if (isset($ops['class'])) {
            $attrs['class'] = $ops['class'];
        }

        if (isset($ops['tabindex'])) {
            $attrs['tabindex'] = $ops['tabindex'];
        }

        $str = '';
        foreach ($attrs as $k => $v) {
            $str .= sprintf(' %s="%s"', $k, $v);
        }

        return '<textarea' . $str . '>' . Page::htmlentities($default_value) . "</textarea>\n";
    }

    public static function calendar(string $name, string $default_value = ''): string
    {
        global $TPL;
        $images = $TPL['url_alloc_images'];

        return <<<EOD
                  <span class="calendar_container nobr"><input name="{$name}" type="text" value="{$default_value}" id="" class="datefield"><img src="{$images}cal.png" title="Date Selector" alt="Date Selector" id=""></span>
            EOD;
    }

    // FIXME: once on php 8.2, use stricter types like: array|string
    public static function select_options($options, $selected_value = null, int $max_length = 45, bool $escape = true): string
    {
        $rows = [];
        $selected_values = [];
        /**
         * Builds up options for use in a html select widget (works with multiple selected too).
         *
         * @param $options        mixed   An sql query or an array of options
         * @param $selected_value string  The current selected element
         * @param $max_length     int     The maximum string length of the label
         *
         * @return string The string of options
         */

        // Build options from an SQL query: "SELECT col_a as value, col_b as label FROM"
        if (is_string($options)) {
            $allocDatabase = new AllocDatabase();
            $allocDatabase->query($options);
            while ($row = $allocDatabase->row()) {
                $rows[$row['value']] = $row['label'];
            }

            // Build options from an array: array(value1=>label1, value2=>label2)
        } elseif (is_array($options)) {
            foreach ($options as $k => $v) {
                $rows[$k] = $v;
            }
        }

        if (is_array($rows)) {
            // Coerce selected options into an array
            if (is_array($selected_value)) {
                $selected_values = $selected_value;
            } elseif (null !== $selected_value) {
                $selected_values[] = $selected_value;
            }

            $str = '';
            foreach ($rows as $value => $label) {
                $sel = '';

                if ($value && !$label) {
                    $label = $value;
                }

                // If an array of selected values!
                if (is_array($selected_values)) {
                    foreach ($selected_values as $selected_value) {
                        if ('' === $selected_value && 0 === $value) {
                            // continue
                        } elseif ($selected_value == $value) {
                            $sel = ' selected';
                        }
                    }
                }

                $label = str_replace('&nbsp;', ' ', $label);
                if (strlen((string) $label) > $max_length) {
                    $label = substr($label, 0, $max_length - 3) . '...';
                }

                if ($escape) {
                    $label = Page::htmlentities($label);
                }

                $label = str_replace(' ', '&nbsp;', $label);

                $str .= "\n<option value=\"" . $value . '"' . $sel . '>' . $label . '</option>';
            }
        }

        return $str;
    }

    public static function expand_link($id, $text = 'New ', $id_to_hide = ''): string
    {
        $extra = null;
        global $TPL;
        $id_to_hide && ($extra = "$('#" . $id_to_hide . "').slideToggle('fast');");

        return "<a class=\"growshrink nobr\" href=\"#x\" onClick=\"$('#" . $id . "').fadeToggle();" . $extra . '">' . $text . '</a>';
    }

    public static function side_by_side_links($url, $items = [], $redraw = '', $title = '')
    {
        $str = null;
        $sp = '';
        $url = preg_replace('/[&?]+$/', '', $url);
        if (strpos($url, '?')) {
            $url .= '&';
        } else {
            $url .= '?';
        }

        foreach ($items as $id => $label) {
            $str .= $sp . '<a id="sbs_link_' . $id . "\" data-sbs-redraw='" . $redraw . "' href=\"" . $url . 'sbs_link=' . $id . '" class="sidebyside noselect" unselectable="on">' . $label . '</a>';
            $sp = '&nbsp;';
        }

        $s = "<div class='noprint' style='margin:20px 0px; text-align:center;'>" . $str . '</div>';
        $title && ($s .= "<span style='font-size:140%;'>" . $title . '</span>');

        return $s;
    }

    /** this is a part of the solution to the old global variable $TPL, instead
     * of getting the value $TPL[$url] from global state, use this to get the
     * same url.
     *
     * NOTE: you have to add the '?' manually unlike $TPL which did some magic
     *
     * @return string the url you want, e.g. 'task/task.php'
     */
    public function getURL(string $url): string
    {
        return SCRIPT_PATH . $this->urls[$url];
    }

    public static function mandatory($field = '')
    {
        $star = '&lowast;';
        if (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
            $star = '*';
        }

        if ('' == $field) {
            return '<b style="font-weight:bold;font-size:100%;color:red;display:inline;top:-5px !important;top:-3px;position:relative;">' . $star . '</b>';
        }
    }

    public static function exclaim($field = '')
    {
        $star = '&lowast;';
        if (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
            $star = '*';
        }

        if ('' == $field) {
            return '<b style="font-weight:bold;font-size:100%;color:green;display:inline;top:-5px !important;top:-3px;position:relative;">' . $star . '</b>';
        }
    }

    public static function warn(): string
    {
        $star = '&lowast;';
        if (stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE')) {
            $star = '*';
        }

        return '<b style="font-weight:bold;font-size:100%;color:orange;display:inline;top:-5px !important;top:-3px;position:relative;">' . $star . '</b>';
    }

    public static function stylesheet(): string
    {
        if (($_GET['media'] ?? '') === 'print') {
            return 'print.css';
        }

        // get user's theme, or default to 4 ('rams')
        $current_user = &singleton('current_user');
        $themes = Page::get_customizedTheme_array();
        $theme = strtolower($themes[sprintf('%d', $current_user->prefs['customizedTheme2'] ?? 4)]);

        return sprintf('style_%s.css', $theme);
    }

    public static function default_font_size()
    {
        // FIXME: why do we use a custom number to repesent the font size
        // instead of 'points'?
        $current_user = &singleton('current_user');
        $fonts = Page::get_customizedFont_array();

        return ($fonts[sprintf('%d', $current_user->prefs['customizedFont'] ?? '')] ?? 4) + 8;
    }

    public static function get_customizedFont_array()
    {
        return [
            '-3' => 1,
            '-2' => 2,
            '-1' => 3,
            '0'  => '4',
            '1'  => 5,
            '2'  => 6,
            '3'  => 7,
            '4'  => 8,
            '5'  => 9,
            '6'  => 10,
        ];
    }

    public static function get_customizedTheme_array()
    {
        global $TPL;
        $dir = $TPL['url_alloc_styles'];
        $rtn = [];
        if (is_dir($dir)) {
            $handle = opendir($dir);
            // TODO add icons to files attachaments in general
            while (false !== ($file = readdir($handle))) {
                if (preg_match('/style_(.*)\\.css$/', $file, $m)) {
                    $rtn[] = ucwords($m[1]);
                }
            }

            sort($rtn);
        }

        return $rtn;
    }

    public static function to_html($str = '', $maxlength = false): string
    {
        $maxlength && ($str = wordwrap($str, $maxlength, "\n"));
        $str = Page::htmlentities($str);

        return nl2br($str);
    }

    public static function htmlentities($str = ''): string
    {
        // FIXME: stop using this funciton so that this hack can go
        if (is_string($str)) {
            return (new Page())->escape($str);
        }

        return '';
    }

    public function escape(string $str = ''): string
    {
        return htmlentities($str, ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    public static function money_fmt($c, $amount = null)
    {
        $currencies = &get_cached_table('currencyType');
        $n = $currencies[$c]['numberToBasic'];
        $num = sprintf('%0.' . $n . 'f', $amount);
        if ($num === sprintf('%0.' . $n . 'f', -0)) {
            return sprintf('%0.' . $n . 'f', 0);
        }

        // *sigh* to prevent -0.00
        return $num;
    }

    public static function money_out($c, $amount = null)
    {
        // AUD,100        -> 100.00
        // AUD,0|''|false -> 0.00
        if (isset($amount) && (bool) strlen($amount)) {
            $c || alloc_error(sprintf('Page::money(): no currency specified for amount %s.', $amount));
            $currencies = &get_cached_table('currencyType');
            $n = $currencies[$c]['numberToBasic'];

            // We can use foo * 10^-n to move the decimal point left
            // Eg: sprintf(%0.2f, $amount * 10^-2) => 15000 becomes 150.00
            // We use the numberToBasic number (eg 2) to a) move the decimal point, and b) dictate the sprintf string
            return Page::money_fmt($c, $amount * 10 ** (-$n));
        }
    }

    public static function money_in($c, $amount = null)
    {
        // AUD,100.00 -> 100
        // AUD,0      -> 0
        // AUD        ->
        if (isset($amount) && (bool) strlen($amount)) {
            $c || alloc_error(sprintf('Page::money_in(): no currency specified for amount %s.', $amount));
            $currencies = &get_cached_table('currencyType');
            $n = $currencies[$c]['numberToBasic'];

            // We can use foo * 10^n to move the decimal point right
            // Eg: $amount * 10^-2 => 150.00 becomes 15000
            // We use the numberToBasic number (eg 2) to move the decimal point
            return $amount * 10 ** $n;
        }
    }

    public static function money($c, $amount = null, $fmt = '%s%mo')
    {
        // Money print
        $c || ($c = config::get_config_item('currency'));
        $currencies = &get_cached_table('currencyType');
        $fmt = str_replace('%mo', Page::money_out($c, $amount), $fmt);                          // %mo = money_out        eg: 150.21
        $fmt = str_replace('%mi', Page::money_in($c, $amount), $fmt);                           // %mi = money_in         eg: 15021
        $fmt = str_replace('%m', Page::money_fmt($c, $amount), $fmt);                          // %m = format           eg: 150.2 => 150.20
        $fmt = str_replace('%S', $currencies[$c]['currencyTypeLabel'], $fmt); // %S = mandatory symbol eg: $
        if (isset($amount) && (bool) strlen($amount)) {
            $fmt = str_replace('%s', $currencies[$c]['currencyTypeLabel'], $fmt);
        }

        // %s = optional symbol  eg: $
        $fmt = str_replace('%C', $c, $fmt);                                   // %C = mandatory code   eg: AUD
        if (isset($amount) && (bool) strlen($amount)) {
            $fmt = str_replace('%c', $c, $fmt);
        }

        // %c = optional code    eg: AUD
        $fmt = str_replace('%N', $currencies[$c]['currencyTypeName'], $fmt);  // %N = mandatory name   eg: Australian dollars
        if (isset($amount) && (bool) strlen($amount)) {
            $fmt = str_replace('%n', $currencies[$c]['currencyTypeName'], $fmt);
        }

        // %n = optional name    eg: Australian dollars
        $fmt = str_replace(['%mo', '%mi', '%m', '%S', '%s', '%C', '%c', '%N', '%n'], '', $fmt); // strip leftovers away

        return $fmt;
    }

    public static function money_print($rows = [])
    {
        $rtn = null;
        $sums = [];
        $k = null;
        $total = null;
        $str = null;
        $mainCurrency = config::get_config_item('currency');
        foreach ((array) $rows as $row) {
            $sums[$row['currency']] ??= 0;
            $sums[$row['currency']] += $row['amount'];
            $k = $row['currency'];
        }

        // If there's only one currency, then just return that figure.
        if (1 == count($sums)) {
            return Page::money($k, $sums[$k], '%s%m %c');
        }

        // Else if there's more than one currency, we'll provide a tooltip of
        // the aggregation.
        $sep = '';
        foreach ((array) $sums as $currency => $amount) {
            $str .= $sep . Page::money($currency, $amount, '%s%m %c');
            $sep = ' + ';
            if ($mainCurrency == $currency) {
                $total += $amount;
            } else {
                $total += $amount;
            }
        }

        $total = Page::money($mainCurrency, $total, '%s%m %c');
        if ($str && $str != $total) {
            $rtn = Page::help(Page::exclaim() . '<b>Approximate currency conversion</b><br>' . $str . ' = ' . $total, Page::exclaim() . $total);
        } elseif ($str) {
            $rtn = $str;
        }

        return $rtn;
    }

    public static function star($entity, $entityID): string
    {
        $current_user = &singleton('current_user');
        global $TPL;
        if (
            isset($current_user->prefs['stars'], $current_user->prefs['stars'][$entity], $current_user->prefs['stars'][$entity][$entityID])
        ) {
            $star_hot = ' hot';
            $star_icon = 'icon-star';
            $star_text = "<b style='display:none'>*</b>";
        } else {
            $star_hot = '';
            $star_icon = 'icon-star-empty';
            $star_text = "<b style='display:none'>.</b>";
        }

        return '<a class="star' . $star_hot . '" href="' . $TPL['url_alloc_star']
            . 'entity=' . $entity . '&entityID=' . $entityID . '"><b class="' . $star_icon . '">' . $star_text . '</b></a>';
    }

    public static function star_sorter($entity, $entityID): int
    {
        $current_user = &singleton('current_user');
        if (!isset($current_user->prefs['stars'])) {
            return 2;
        }

        if (!isset($current_user->prefs['stars'][$entity])) {
            return 2;
        }

        if (!isset($current_user->prefs['stars'][$entity][$entityID])) {
            return 2;
        }

        return 1;
    }
}

<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

use ZendSearch\Lucene\Index;
use ZendSearch\Lucene\Search\QueryParser;

require_once __DIR__ . '/../alloc.php';

function format_display_fields($str = '')
{
    if ($str) {
        $lines = explode('|+|=|', $str); // arbitrary line delimiter, can't use newlines as data will contain newlines.
        $t = "<table class='list'>";
        foreach ($lines as $line) {
            $t .= '<tr>';
            $cells = explode('|', $line);
            foreach ($cells as $cell) {
                $t .= '<td>' . str_replace(["\n", "\r", '<br>', '<br />'], ' ', substr($cell, 0, 200)) . '</td>';
            }

            $t .= '</tr>';
        }

        $t .= '</table>';

        return '<div>' . $t . '</div>';
    }
}

global $TPL;

$noRedirect = $_POST['idRedirect'] ?? $_GET['idRedirect'] ?? '';
($search = $_POST['search']) || ($search = $_GET['search']);
($category = $_POST['category']) || ($category = $_GET['category']);
($needle = trim($_POST['needle'])) || ($needle = trim($_GET['needle']));

$db = new AllocDatabase();

// Project Search
if ($search && $needle && 'search_projects' == $category) {
    $TPL['search_title'] = 'Project Search';
    if (!$noRedirect && is_numeric($needle)) {
        $query = unsafe_prepare('SELECT projectID FROM project WHERE projectID = %d', $needle);
        $db->query($query);
        if ($db->next_record()) {
            alloc_redirect($TPL['url_alloc_project'] . 'projectID=' . $db->f('projectID'));
        }
    } else {
        $index = new Index(ATTACHMENTS_DIR . 'search/project');
        $query = QueryParser::parse($needle);
        $hits = $index->find($needle);
        $TPL['index_count'] = $index->count();
        $TPL['hits_count'] = is_countable($hits) ? is_countable($hits) ? is_countable($hits) ? is_countable($hits) ? count($hits) : 0 : 0 : 0 : 0;

        foreach ($hits as $hit) {
            $d = $hit->getDocument();
            $row = [];
            $row['idx'] = $hit->id;
            $row['score'] = sprintf('%d%%', $hit->score * 100);
            $row['title'] = $d->getFieldValue('id') . ' ' . sprintf(
                "<a href='%sprojectID=%d'>%s</a>",
                $TPL['url_alloc_project'],
                $d->getFieldValue('id'),
                Page::htmlentities($d->getFieldValue('name'))
            );
            $row['related'] = sprintf(
                "<a href='%sclientID=%d'>%s</a>",
                $TPL['url_alloc_client'],
                $d->getFieldValue('cid'),
                Page::htmlentities($d->getFieldValue('client'))
            );
            $row['desc'] = Page::htmlentities($d->getFieldValue('desc'));
            $TPL['search_results'][] = $row;
        }
    }

    // Clients Search
} elseif ($search && $needle && 'search_clients' == $category) {
    $TPL['search_title'] = 'Client Search';
    if (!$noRedirect && is_numeric($needle)) {
        $query = unsafe_prepare('SELECT clientID FROM client WHERE clientID = %d', $needle);
        $db->query($query);
        if ($db->next_record()) {
            alloc_redirect($TPL['url_alloc_client'] . 'clientID=' . $db->f('clientID'));
        }
    } else {
        $index = new Index(ATTACHMENTS_DIR . 'search/client');
        $query = QueryParser::parse($needle);
        $hits = $index->find($needle);
        $TPL['index_count'] = $index->count();
        $TPL['hits_count'] = is_countable($hits) ? is_countable($hits) ? is_countable($hits) ? is_countable($hits) ? count($hits) : 0 : 0 : 0 : 0;

        foreach ($hits as $hit) {
            $d = $hit->getDocument();
            $row = [];
            $row['idx'] = $hit->id;
            $row['score'] = sprintf('%d%%', $hit->score * 100);
            $row['title'] = $d->getFieldValue('id') . ' ' . sprintf(
                "<a href='%sclientID=%d'>%s</a>",
                $TPL['url_alloc_client'],
                $d->getFieldValue('id'),
                Page::htmlentities($d->getFieldValue('name'))
            );
            // $row["related"] = sprintf("<a href='%sprojectID=%d'>%s</a>"
            //                ,$TPL["url_alloc_project"], $d->getFieldValue('pid'), $d->getFieldValue('project'));

            unset($num_contact);
            if ($d->getFieldValue('contact')) {
                $num_contact = count((array) explode('|+|=|', $d->getFieldValue('contact')));
                unset($s);
                if ($num_contact > 1) {
                    $s = 's';
                }

                $num_contact && ($num_contact = "\n\n" . $num_contact . ' contact' . $s . ".\n");
            }

            $desc = Page::htmlentities($d->getFieldValue('desc'));

            $row['desc'] = $desc . $num_contact;
            $row['desc2'] = Page::htmlentities($d->getFieldValue('contact'));

            $TPL['search_results'][] = $row;
        }
    }

    // Tasks Search
} elseif ($search && $needle && 'search_tasks' == $category) {
    $TPL['search_title'] = 'Task Search';
    if (!$noRedirect && is_numeric($needle)) {
        $query = unsafe_prepare('SELECT taskID FROM task WHERE taskID = %d', $needle);
        $db->query($query);
        if ($db->next_record()) {
            alloc_redirect($TPL['url_alloc_task'] . 'taskID=' . $db->f('taskID'));
        }
    } else {
        $index = new Index(ATTACHMENTS_DIR . 'search/task');
        $query = QueryParser::parse($needle);
        $hits = $index->find($needle);
        $TPL['index_count'] = $index->count();
        $TPL['hits_count'] = is_countable($hits) ? is_countable($hits) ? is_countable($hits) ? is_countable($hits) ? count($hits) : 0 : 0 : 0 : 0;

        foreach ($hits as $hit) {
            $d = $hit->getDocument();
            $row = [];
            $row['idx'] = $hit->id;
            $row['score'] = sprintf('%d%%', $hit->score * 100);
            $row['title'] = $d->getFieldValue('id') . ' ' . sprintf(
                "<a href='%staskID=%d'>%s</a>",
                $TPL['url_alloc_task'],
                $d->getFieldValue('id'),
                Page::htmlentities($d->getFieldValue('name'))
            );
            $row['related'] = sprintf(
                "<a href='%sprojectID=%d'>%s</a>",
                $TPL['url_alloc_project'],
                $d->getFieldValue('pid'),
                Page::htmlentities($d->getFieldValue('project'))
            );
            $row['desc'] = Page::htmlentities($d->getFieldValue('desc'));
            $TPL['search_results'][] = $row;
        }
    }

    // Item Search
} elseif ($search && $needle && 'search_items' == $category) {
    $TPL['search_title'] = 'Item Search';
    $today = date('Y') . '-' . date('m') . '-' . date('d');
    if (!$noRedirect && is_numeric($needle)) {
        $query = unsafe_prepare('SELECT itemID FROM item WHERE itemID = %d', $needle);
        $db->query($query);
        if ($db->next_record()) {
            alloc_redirect($TPL['url_alloc_item'] . 'itemID=' . $db->f('itemID'));
        }
    } else {
        // open the index
        $index = new Index(ATTACHMENTS_DIR . 'search/item');
        $query = QueryParser::parse($needle);
        $hits = $index->find($needle);
        $TPL['index_count'] = $index->count();
        $TPL['hits_count'] = is_countable($hits) ? is_countable($hits) ? is_countable($hits) ? is_countable($hits) ? count($hits) : 0 : 0 : 0 : 0;

        $p = &get_cached_table('person');

        foreach ($hits as $hit) {
            $d = $hit->getDocument();
            $item = new item();
            $item->set_id($d->getFieldValue('id'));
            $item->select();
            $row = [];
            $row['idx'] = $hit->id;
            $author = $item->get_value('itemAuthor');
            $author && ($author = ' by ' . $author);
            $row['title'] = $item->get_id() . ' ' . $item->get_link() . $author;
            $row['score'] = sprintf('%d%%', $hit->score * 100);
            $row['desc'] = Page::htmlentities($d->getFieldValue('desc'));

            // get availability of loan
            $db2 = new AllocDatabase();
            $query = unsafe_prepare("SELECT * FROM loan WHERE itemID = %d AND dateReturned='0000-00-00'", $item->get_id());
            $db2->query($query);
            if ($db2->next_record()) {
                $loan = new loan();
                $loan->read_db_record($db2);

                if ($loan->have_perm(PERM_READ_WRITE)) {
                    // if item is overdue
                    $status = $loan->get_value('dateToBeReturned') < $today ? 'Overdue' : 'Due on ' . $loan->get_value('dateToBeReturned');

                    $row['related'] = $status . ' <a href="' . $TPL['url_alloc_item'] . 'itemID=' . $item->get_id() . '&return=true">Return</a>';

                    // Else you dont have permission to loan or return so just show status
                } else {
                    $name = Page::htmlentities($p[$loan->get_value('personID')]['name']);

                    if ($loan->get_value('dateToBeReturned') < $today) {
                        $row['related'] = 'Overdue from ' . $name;
                    } else {
                        $row['related'] = 'Due from ' . $name . ' on ' . $loan->get_value('dateToBeReturned');
                    }
                }
            } else {
                $row['related'] = 'Available <a href="' . $TPL['url_alloc_item'] . 'itemID=' . $item->get_id() . '&borrow=true">Borrow</a>';
            }

            $TPL['search_results'][] = $row;
        }
    }

    // Expense Form ID search
} elseif ($search && $needle && 'search_expenseForm' == $category) {
    if (!$noRedirect && is_numeric($needle)) {
        $query = unsafe_prepare('SELECT expenseFormID FROM expenseForm WHERE expenseFormID = %d', $needle);
        $db->query($query);
        if ($db->next_record()) {
            alloc_redirect($TPL['url_alloc_expenseForm'] . 'expenseFormID=' . $db->f('expenseFormID'));
        }
    }

    // Time Sheet Search
} elseif ($search && $needle && 'search_time' == $category) {
    $TPL['search_title'] = 'Time Sheet Search';
    if (!$noRedirect && is_numeric($needle)) {
        $query = unsafe_prepare('SELECT timeSheetID FROM timeSheet WHERE timeSheetID = %d', $needle);
        $db->query($query);
        if ($db->next_record()) {
            alloc_redirect($TPL['url_alloc_timeSheet'] . 'timeSheetID=' . $db->f('timeSheetID'));
        }
    } else {
        $index = new Index(ATTACHMENTS_DIR . 'search/timeSheet');
        $query = QueryParser::parse($needle);
        $hits = $index->find($needle);
        $TPL['index_count'] = $index->count();
        $TPL['hits_count'] = is_countable($hits) ? is_countable($hits) ? is_countable($hits) ? is_countable($hits) ? count($hits) : 0 : 0 : 0 : 0;

        foreach ($hits as $hit) {
            $d = $hit->getDocument();
            $row = [];
            $row['idx'] = $hit->id;
            $row['score'] = sprintf('%d%%', $hit->score * 100);
            $c = (array) explode(' ', $d->getFieldValue('creator'));
            $creator = implode(' ', (array) array_slice($c, 2));
            // $creator = implode(" ",array_shift(array_shift(explode(" ",$d->getFieldValue('creator')))));
            $row['title'] = $d->getFieldValue('id') . ' ' . sprintf(
                "<a href='%stimeSheetID=%d'>%s</a>",
                $TPL['url_alloc_timeSheet'],
                $d->getFieldValue('id'),
                'Time Sheet for ' . Page::htmlentities($d->getFieldValue('project')) . ' by ' . Page::htmlentities($creator)
            );
            $row['related'] = sprintf(
                "<a href='%sprojectID=%d'>%s</a>",
                $TPL['url_alloc_project'],
                $d->getFieldValue('pid'),
                Page::htmlentities($d->getFieldValue('project'))
            );

            $row['desc'] = Page::htmlentities($d->getFieldValue('desc'));
            $TPL['search_results'][] = $row;
        }
    }

    // Comment Search
} elseif ($search && $needle && 'search_comment' == $category) {
    $TPL['search_title'] = 'Comment Search';
    if (!$noRedirect && is_numeric($needle)) {
        $query = unsafe_prepare('SELECT commentID FROM comment WHERE commentID = %d', $needle);
        $db->query($query);
        if ($db->next_record()) {
            alloc_redirect($TPL['url_alloc_comment'] . 'commentID=' . $db->f('commentID'));
        }
    } else {
        $index = new Index(ATTACHMENTS_DIR . 'search/comment');
        $query = QueryParser::parse($needle);
        $hits = $index->find($needle);
        $TPL['index_count'] = $index->count();
        $TPL['hits_count'] = is_countable($hits) ? is_countable($hits) ? is_countable($hits) ? is_countable($hits) ? count($hits) : 0 : 0 : 0 : 0;

        foreach ($hits as $hit) {
            $d = $hit->getDocument();
            $row = [];
            $row['idx'] = $hit->id;
            $row['score'] = sprintf('%d%%', $hit->score * 100);
            $row['title'] = Page::htmlentities($d->getFieldValue('name'));
            $row['related'] = sprintf(
                "<a href='%s%sID=%d'>%s</a>",
                $TPL['url_alloc_' . $d->getFieldValue('type')],
                $d->getFieldValue('type'),
                $d->getFieldValue('typeid'),
                Page::htmlentities($d->getFieldValue('typename'))
            );
            $row['desc'] = Page::htmlentities($d->getFieldValue('desc'));
            $TPL['search_results'][] = $row;
        }
    }
}

// setup generic values
$TPL['search_category_options'] = Page::get_category_options($category);
$TPL['needle'] = $needle;
$TPL['needle2'] = $needle;
if (!$needle || $noRedirect) {
    $TPL['redir'] = 'checked="1"';
}

$TPL['main_alloc_title'] = 'Search - ' . APPLICATION_NAME;
include_template('templates/searchM.tpl');

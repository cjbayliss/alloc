<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


require_once(__DIR__ . "/markdown.inc.php");


class wiki_module extends module
{
    public $module = "wiki";

    function get_wiki_path()
    {
        return realpath(ATTACHMENTS_DIR . "wiki") . DIRECTORY_SEPARATOR;
    }

    function file_save($file, $body)
    {
        if (is_dir(dirname($file)) && path_under_path(dirname($file), wiki_module::get_wiki_path())) {
            // Save the file ...
            $handle = fopen($file, "w+b");
            fputs($handle, $body);
            fclose($handle);

            // Update the search index for this file, if any
            $index = Zend_Search_Lucene::open(ATTACHMENTS_DIR . 'search/wiki');
            $f = str_replace(wiki_module::get_wiki_path(), "", $file);
            $hits = $index->find('id:' . $f);
            foreach ($hits as $hit) {
                $index->delete($hit->id);
            }
            wiki_module::update_search_index_doc($index, $f);
            $index->commit();
        }
    }

    function file_delete($file)
    {
        // remove the file, and remove the search index info for the file
        if (is_dir(dirname($file)) && path_under_path(dirname($file), wiki_module::get_wiki_path())) {
            // remove the file
            if (file_exists($file)) {
                unlink($file);
            }

            // Update the search index for this file, if any
            $index = Zend_Search_Lucene::open(ATTACHMENTS_DIR . 'search/wiki');
            $f = str_replace(wiki_module::get_wiki_path(), "", $file);
            $hits = $index->find('id:' . $f);
            foreach ($hits as $hit) {
                $index->delete($hit->id);
            }
            $index->commit();
        }
    }

    function nuke_trailing_spaces_from_all_lines($str)
    {
        // for some reason trailing slashes on a line appear to not get saved by
        // particular vcs's. So when we compare the two files (the one on disk and
        // the one in version control, we need to nuke trailing spaces, from every
        // line.
        $lines or $lines = [];
        $str = str_replace("\r\n", "\n", $str);
        $bits = explode("\n", $str);
        foreach ($bits as $line) {
            $lines[] = rtrim($line);
        }
        return rtrim(implode("\n", $lines));
    }

    function get_file($file, $rev = "")
    {
        global $TPL;

        $f = realpath(wiki_module::get_wiki_path() . $file);

        if (path_under_path(dirname($f), wiki_module::get_wiki_path())) {
            $mt = get_mimetype($f);
            if (strtolower($mt) != "text/plain") {
                $s = "<h6>Download File</h6>";
                $s .= "<a href='" . $TPL["url_alloc_fileDownload"] . "file=" . urlencode($file) . "'>" . $file . "</a>";
                $TPL["str_html"] = $s;
                include_template("templates/fileGetM.tpl");
                exit();
            }

            // Get the regular revision ...
            $disk_file = file_get_contents($f) or $disk_file = "";

            $vcs = vcs::get();
            //$vcs->debug = true;

            // Get a particular revision
            if ($vcs) {
                $vcs_file = $vcs->cat($f, $rev);
            }

            if ($vcs && wiki_module::nuke_trailing_spaces_from_all_lines($disk_file) != wiki_module::nuke_trailing_spaces_from_all_lines($vcs_file)) {
                if (!$vcs_file) {
                    $TPL["msg"] = "<div class='message warn noprint' style='margin-top:0px; margin-bottom:10px; padding:10px;'>
                          Warning: This file may not be under version control.
                         </div>";
                } else {
                    $TPL["msg"] = "<div class='message warn noprint' style='margin-top:0px; margin-bottom:10px; padding:10px;'>
                          Warning: This file may not be the latest version.
                         </div>";
                }
            }

            if ($rev && $vcs_file) {
                $TPL["str"] = $vcs_file;
            } else {
                $TPL["str"] = $disk_file;
            }

            $wikiMarkup = config::get_config_item("wikiMarkup");
            $TPL["str_html"] = $wikiMarkup($TPL["str"]);
            $TPL["rev"] = urlencode($rev);

            include_template("templates/fileGetM.tpl");
        }
    }

    function update_search_index_doc(&$index, $file)
    {
        // Attempt to parse pdfs
        if (strtolower(substr($file, -4)) == ".pdf") {
            $pdfstr = file_get_contents(wiki_module::get_wiki_path() . $file);
            $pdf_reader = new pdf_reader();
            $pdfstr = $pdf_reader->pdf2txt($pdfstr);

            // Else regular text
        } else {
            $str = file_get_contents(wiki_module::get_wiki_path() . $file);
        }

        $doc = new Zend_Search_Lucene_Document();
        $doc->addField(Zend_Search_Lucene_Field::Keyword('id', $file));
        $doc->addField(Zend_Search_Lucene_Field::Text('name', $file, "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::Text('desc', $str, "utf-8"));
        $doc->addField(Zend_Search_Lucene_Field::UnStored('pdfstr', $pdfstr, "utf-8"));
        $index->addDocument($doc);
    }
}

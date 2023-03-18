<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


class vcs_mercurial extends vcs
{

    function __construct($repo)
    {
        $current_user = &singleton("current_user");
        $this->name = "hg ";
        $this->repodir = $repo;
        $this->repoprefix = " --cwd '".$repo."' ";
        $this->commit = " commit --user='".$current_user->get_name()." <".$current_user->get_value("emailAddress").">' -m ";
        $this->metadir = ".hg";
        $this->add_everything = " add ";
        $this->cat = ' cat -r %2$s %1$s ';

        /*
          author String. The unmodified author of the changeset.
          branches String. The name of the branch on which the changeset was committed. Will be empty if the branch name was default.
          date Date information. The date when the changeset was committed. This is not human-readable; you must pass it through a filter that will render it appropriately. See section 11.6 for more information on filters. The date is expressed as a pair of numbers. The first number is a Unix UTC timestamp (seconds since January 1, 1970); the second is the offset of the committer’s timezone from UTC, in seconds.
          desc String. The text of the changeset description.
          files List of strings. All files modified, added, or removed by this changeset.
          filet4ht@95xadds List of strings. Files added by this changeset.
          filet4ht@95xdels List of strings. Files removed by this changeset.
          node String. The changeset identification hash, as a 40-character hexadecimal string.
          parents List of strings. The parents of the changeset.
          rev Integer. The repository-local changeset revision number.
          tags List of strings. Any tags associated with the changeset.
        */
        $this->log = " log --template 'Hash: {node}\nAuthor: {author}\nDate: {date}\nMsg: {desc}\n' ";
        parent::__construct($repo);
    }

    function file_in_vcs($file)
    {
        $output = $this->run("log ".$file);
        if (count($output) > 0) {
            return true;
        }
    }

    function juggle_command_order($name, $command, $repo)
    {
        return $name." ".$repo." ".$command;
    }

    function format_log($msg)
    {
        $msg = parent::format_log($msg);
        $msg or $msg = array();
        foreach ($msg as $id => $arr) {
            $a = explode(" ", $arr["author"]); // need to strip the email address from the author field
            count($a) >1 and array_pop($a);
            $msg[$id]["author"] = implode(" ", $a);
        }
        return $msg;
    }

    function log($file)
    {

        if (is_file(wiki_module::get_wiki_path().DIRECTORY_SEPARATOR.$file)) {
            $this->log.= " -f "; // follow renames to files
        }
        return $this->run($this->log." ".escapeshellarg($file));
    }
}

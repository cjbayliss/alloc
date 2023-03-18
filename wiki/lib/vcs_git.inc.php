<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


class vcs_git extends vcs
{

    function __construct($repo)
    {
        $current_user = &singleton("current_user");
        //$this->debug = true;
        $this->name = "git ";
        $this->repodir = $repo;
        $this->repoprefix = " --git-dir '".$repo.".git' --work-tree '".$repo."' ";
        $this->commit = " -c user.name='".$current_user->get_name()."' -c user.email='".$current_user->get_value("emailAddress")."' commit --author '".$current_user->get_name()." <".$current_user->get_value("emailAddress").">' -m ";
        $this->metadir = ".git";
        $this->add_everything = " add ".$repo."/. ";
        $this->log = " log --pretty=format:'Hash: %H%nAuthor: %an%nDate: %ct%nMsg: %s' -M -C --follow ";
        $this->cat = ' show %2$s:%3$s ';
        parent::__construct($repo);
    }

    function file_in_vcs($file)
    {
        $output = $this->run("log ".$file);
        if (count($output) > 0 && !preg_match("/^fatal:/", current($output))) {
            return true;
        }
    }

    function juggle_command_order($name, $command, $repo)
    {
        return $name." ".$repo." ".$command;
    }
}

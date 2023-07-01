<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class ProjectListHomeItem extends HomeItem
{
    private array $projects;

    private bool $has_config = true;

    public function __construct()
    {
        parent::__construct(
            'project_list',
            'Project List',
            'project',
            'standard',
            40,
        );
    }

    public function visible(): bool
    {
        $current_user = &singleton('current_user');

        if (!isset($current_user->prefs['showProjectHome'])) {
            $current_user->prefs['showProjectHome'] = 1;
            $current_user->prefs['projectListNum'] = '10';
        }

        return (bool) $current_user->prefs['showProjectHome'];
    }

    public function render(): bool
    {
        $current_user = &singleton('current_user');
        $options = [];

        if (isset($current_user->prefs['projectListNum']) && 'all' != $current_user->prefs['projectListNum']) {
            $options['limit'] = sprintf('%d', $current_user->prefs['projectListNum']);
        }

        $options['projectStatus'] = 'Current';
        $options['personID'] = $current_user->get_id();
        $this->projects = (new project())->getFilteredProjectList($options);

        return true;
    }

    public function get_config()
    {
        return $this->has_config;
    }

    public function getHTML(): string
    {
        if ([] === $this->projects) {
            return '';
        }

        $page = new Page();
        $projectListHTML = '';

        foreach ($this->projects as $project) {
            $projectShortName = isset($project['projectShortName']) ? $page->escape($project['projectShortName']) : '';
            $projectListHTML .= <<<HTML
                    <tr>
                      <td>{$project['projectLink']}</td>
                      <td>{$projectShortName}</td>
                      <td class="noprint" align="right">{$project['navLinks']}</td>
                    </tr>
                HTML;
        }

        return <<<HTML
                <table class="list sortable">
                  <tr>
                    <th>Project</th>
                    <th>Nick</th>
                    <th class="noprint">&nbsp;</th>
                  </tr>
                  {$projectListHTML}
                </table>
            HTML;
    }
}

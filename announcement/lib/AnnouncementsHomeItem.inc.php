<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class AnnouncementsHomeItem extends HomeItem
{
    public function __construct()
    {
        parent::__construct(
            'announcements',
            'Announcements',
            'announcement',
            'standard',
            10,
        );
    }

    public function visible(): bool
    {
        return (new announcement())->has_announcements();
    }

    public function render(): bool
    {
        return true;
    }

    private function show_announcements(): string
    {
        $page = new Page();
        $html = '';

        $allocDatabase = new AllocDatabase();
        $allocDatabase->connect();

        $getAnnoucements = $allocDatabase->pdo->query(
            'SELECT heading, body, displayFromDate, displayToDate
               FROM announcement
              WHERE displayFromDate <= CURRENT_DATE()
                AND displayToDate >= CURRENT_DATE()
              ORDER BY displayFromDate desc'
        );

        while ($annoucementRow = $getAnnoucements->fetch(PDO::FETCH_ASSOC)) {
            $heading = $page->escape($annoucementRow['heading']);
            $body = str_replace('&NewLine;', '<br />', $page->escape($annoucementRow['body']));

            $html .= <<<HTML
                    <strong>{$heading}</strong><br>
                    {$body}<br>
                    <br>
                HTML;
        }

        return $html;
    }

    public function getHTML(): string
    {
        return <<<HTML
                {$this->show_announcements()}
            HTML;
    }
}

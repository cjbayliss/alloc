<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class loan extends DatabaseEntity
{
    public $data_table = 'loan';

    public $display_field_name = 'itemID';

    public $key_field = 'loanID';

    public $data_fields = [
        'itemID',
        'personID',
        'loanModifiedUser',
        'loanModifiedTime',
        'dateBorrowed',
        'dateToBeReturned',
        'dateReturned',
    ];
}

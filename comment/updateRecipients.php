<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define("NO_REDIRECT", 1);
require_once("../alloc.php");

interestedParty::make_interested_parties('comment', $_POST['commentID'], $_POST['comment_recipients']);

if (interestedParty::is_external('comment', $_POST['commentID'])) {
    echo 'external';
} else {
    echo 'internal';
}

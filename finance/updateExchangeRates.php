<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

define('NO_AUTH', true);
define('IS_GOD', true);
require_once __DIR__ . '/../alloc.php';

exchangeRate::download();

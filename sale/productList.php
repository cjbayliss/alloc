<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

require_once __DIR__ . '/../alloc.php';

$TPL['productListRows'] = product::get_list($_FORM ?? []);

$TPL['main_alloc_title'] = 'Product List - ' . APPLICATION_NAME;
include_template('templates/productListM.tpl');

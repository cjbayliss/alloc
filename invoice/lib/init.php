<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


class invoice_module extends module
{
    var $module = "invoice";
    var $db_entities = array("invoice", "invoiceItem", "invoiceEntity");
}

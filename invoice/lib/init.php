<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class invoice_module extends module
{
    public $module = "invoice";
    public $databaseEntities = ["invoice", "invoiceItem", "invoiceEntity"];
}

<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class person_module extends Module
{
    public $module = "person";

    public $databaseEntities = ["person", "absence", "skill", "proficiency"];
}

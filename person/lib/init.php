<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */


class person_module extends module
{
    public $module = "person";
    public $db_entities = ["person", "absence", "skill", "proficiency"];
}

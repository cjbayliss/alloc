<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

use ZendSearch\Lucene\Document;
use ZendSearch\Lucene\Document\Field;

class item extends DatabaseEntity
{
    public $classname = "item";
    public $data_table = "item";
    public $display_field_name = "itemName";
    public $key_field = "itemID";
    public $data_fields = [
        "itemModifiedUser",
        "itemName",
        "itemAuthor",
        "itemNotes",
        "itemModifiedTime",
        "itemType",
        "personID",
    ];

    public function update_search_index_doc(&$index)
    {
        $p = &get_cached_table("person");
        $personID = $this->get_value("personID");
        $person_field = $personID . " " . $p[$personID]["username"] . " " . $p[$personID]["name"];
        $itemModifiedUser = $this->get_value("itemModifiedUser");
        $itemModifiedUser_field = $itemModifiedUser . " " . $p[$itemModifiedUser]["username"] . " " . $p[$itemModifiedUser]["name"];

        $zendSearchLuceneDocument = new Document();
        $zendSearchLuceneDocument->addField(Field::Keyword('id', $this->get_id()));
        $zendSearchLuceneDocument->addField(Field::Text('name', $this->get_value("itemName"), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('desc', $this->get_value("itemNotes"), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('type', $this->get_value("itemType"), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('author', $this->get_value("itemAuthor"), "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('creator', $person_field, "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('modifier', $itemModifiedUser_field, "utf-8"));
        $zendSearchLuceneDocument->addField(Field::Text('dateModified', str_replace("-", "", $this->get_value("itemModifiedTime")), "utf-8"));
        $index->addDocument($zendSearchLuceneDocument);
    }
}

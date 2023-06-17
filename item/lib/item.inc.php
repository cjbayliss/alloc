<?php

/*
 * Copyright: Alex Lance, Clancy Malcolm, Cyber IT Solutions Pty. Ltd.
 * SPDX-License-Identifier: AGPL-3.0-or-later
 */

class item extends db_entity
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

        $zendSearchLuceneDocument = new Zend_Search_Lucene_Document();
        $zendSearchLuceneDocument->addField(Zend_Search_Lucene_Field::Keyword('id', $this->get_id()));
        $zendSearchLuceneDocument->addField(Zend_Search_Lucene_Field::Text('name', $this->get_value("itemName"), "utf-8"));
        $zendSearchLuceneDocument->addField(Zend_Search_Lucene_Field::Text('desc', $this->get_value("itemNotes"), "utf-8"));
        $zendSearchLuceneDocument->addField(Zend_Search_Lucene_Field::Text('type', $this->get_value("itemType"), "utf-8"));
        $zendSearchLuceneDocument->addField(Zend_Search_Lucene_Field::Text('author', $this->get_value("itemAuthor"), "utf-8"));
        $zendSearchLuceneDocument->addField(Zend_Search_Lucene_Field::Text('creator', $person_field, "utf-8"));
        $zendSearchLuceneDocument->addField(Zend_Search_Lucene_Field::Text('modifier', $itemModifiedUser_field, "utf-8"));
        $zendSearchLuceneDocument->addField(Zend_Search_Lucene_Field::Text('dateModified', str_replace("-", "", $this->get_value("itemModifiedTime")), "utf-8"));
        $index->addDocument($zendSearchLuceneDocument);
    }
}

<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Model_add_category extends Model
{
    /**
     * @param $name
     * @return bool
     */
    public function checkExistCatName($name)
    {
        $name = core::database()->escape($name);
        $query = "SELECT * FROM " . core::database()->getTableName('category') . " WHERE name LIKE '" . $name . "'";
        $result = core::database()->querySQL($query);
        
        return (core::database()->getRecordCount($result) == 0) ? false : true;
    }

    /**
     * @param $fields
     * @return mixed
     */
    public function addNewCategory($fields)
    {
        return core::database()->insert($fields, core::database()->getTableName('category'));
    }
}
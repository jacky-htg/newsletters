<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Model_category extends Model
{

    /**
     * @return mixed
     */
    public function getCategoryArr()
    {
        $query = "SELECT * FROM " . core::database()->getTableName('category') . " ORDER BY name";
        $result = core::database()->querySQL($query);
        return core::database()->getColumnArray($result);
    }

    /**
     * @param $id_cat
     * @return int
     */
    public function getCountSubscription($id_cat)
    {
        if (is_numeric($id_cat)) {
            $query = "SELECT COUNT(*) FROM " . core::database()->getTableName('subscription') . " WHERE id_cat = " . $id_cat;
            $result = core::database()->querySQL($query);
            $count = core::database()->getRow($result, 'row');
            return $count[0];
        } else {
            return 0;
        }
    }

    /**
     * @param $id_cat
     * @return bool
     */
    public function removeCategory($id_cat)
    {
        if (is_numeric($id_cat)) {
            $result = core::database()->delete(core::database()->getTableName('category'), "id_cat=" . $id_cat, '');

            return ($result) ? core::database()->delete(core::database()->getTableName('subscription'), "id_cat=" . $id_cat, '') : false;
        }
    }
}
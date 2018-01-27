<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Model_add_user extends Model
{

    /**
     * @return mixed
     */
    public function getCategoryList()
    {
        $query = "SELECT * FROM " . core::database()->getTableName('category') . " ORDER BY name";
        $result = core::database()->querySQL($query);

        return core::database()->getColumnArray($result);
    }

    /**
     * @return bool
     */
    public function checkExistEmail()
    {
        $query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE email LIKE '" . $_POST['email'] . "'";
        $result = core::database()->querySQL($query);

        return (core::database()->getRecordCount($result) == 0) ? false : true;
    }

    /**
     * @param $arrs
     * @param $id_cat
     * @return bool
     */
    public function checkSub($arrs, $id_cat)
    {
        if ($arrs) {
            foreach ($arrs as $id) {
                if ($id_cat == $id) {
                    return true;
                    break;
                }
            }
        }
    }

    /**
     * @param $fields
     * @param $id_cat
     * @return bool
     */
    public function addUser($fields, $id_cat)
    {
        $id_user = core::database()->insert($fields, core::database()->getTableName('users'));
        
        if ($id_user) {
            if (!empty($id_cat)) {
                foreach ($id_cat as $id) {
                    if (is_numeric($id)) {
                        $insert = "INSERT INTO " . core::database()->getTableName('subscription') . " (`id_sub`,`id_user`,`id_cat`) VALUES (0," . $id_user . "," . $id . ")";
                        core::database()->querySQL($insert);
                    }
                }
            }
            return true;
        } else
            return false;
    }
}
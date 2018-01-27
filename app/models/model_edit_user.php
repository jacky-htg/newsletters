<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Model_edit_user extends Model
{
    /**
     * @param $id_user
     * @return mixed
     */
    public function getUserEdit($id_user)
    {
        if (is_numeric($id_user)) {
            $query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE id_user=" . $id_user;
            $result = core::database()->querySQL($query);
            return core::database()->getRow($result);
        }
    }

    /**
     * @return mixed
     */
    public function getGategoryList()
    {
        $query = "SELECT * FROM " . core::database()->getTableName('category') . " ORDER BY name";
        $result = core::database()->querySQL($query);
        return core::database()->getColumnArray($result);
    }

    /**
     * @param $id_cat
     * @param $id_user
     * @return mixed
     */
    public function checkUserSub($id_cat, $id_user)
    {
        if (is_numeric($id_cat) && is_numeric($id_user)) {

            $query = "SELECT id_user FROM " . core::database()->getTableName('subscription') . " WHERE id_cat=" . $id_cat . " AND id_user=" . $id_user;
            $result = core::database()->querySQL($query);
            return core::database()->getRecordCount($result);
        }
    }

    /**
     * @param $fields
     * @param $id_user
     * @param array $id_cat
     * @return bool
     */
    public function editUser($fields, $id_user, $id_cat = array())
    {
        if (is_numeric($id_user)) {
            $result = core::database()->update($fields, core::database()->getTableName('users'), "id_user=" . $id_user);

            if ($result) {
                if (core::database()->delete(core::database()->getTableName('subscription'), "id_user=" . $id_user, '')) {
                    foreach (Core_Array::getRequest('id_cat') as $id) {
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
}
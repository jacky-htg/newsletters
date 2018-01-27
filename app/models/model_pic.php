<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Model_pic extends Model
{
    /**
     * @param $id_template
     * @param $id_user
     * @return mixed
     */
    public function countUser($id_template, $id_user)
    {
        if (is_numeric($id_template) && is_numeric($id_user)) {
            $query = "UPDATE " . core::database()->getTableName('ready_send') . " SET readmail='yes' WHERE id_template=" . $id_template . " AND id_user=" . $id_user;
            return core::database()->querySQL($query);
        }
    }
}
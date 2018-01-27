<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Mailing
{
    public static function getCurrentMailingStatus($id_user)
    {
        if (is_numeric($id_user)) {
            $query = "SELECT * FROM " . core::database()->getTableName('process') . " WHERE id_user=" . $id_user;
            $result = core::database()->querySQL($query);
            $row = core::database()->getRow($result);

            return $row['process'];
        }
    }
}
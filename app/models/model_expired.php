<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Model_expired extends Model
{
    /**
     * @param $licensekey
     * @return mixed
     */
    public function addLicenseKey($licensekey)
    {
        $licensekey = core::database()->escape($licensekey);
        $data = array();
        $data['licensekey'] = $licensekey;

        return core::database()->insert($data, core::database()->getTableName('licensekey'));
    }

    /**
     * @param $licensekey
     * @return mixed
     */
    public function updateLicenseKey($licensekey)
    {
        $licensekey = core::database()->escape($licensekey);
        $fields = array();
        $fields['licensekey'] = $licensekey;

        return core::database()->update($fields, core::database()->getTableName('licensekey'), '');
    }

    /**
     * @return mixed
     */
    public function getLicenseKey()
    {
        $query = "SELECT * FROM ".core::database()->getTableName('licensekey')."";
        $result = core::database()->querySQL($query);
        $row = core::database()->getRow($result);

        return $row['licensekey'];
    }
}
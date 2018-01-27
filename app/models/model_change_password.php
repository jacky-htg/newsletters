<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Model_change_password extends Model
{
	/**
	 * @param $password
	 * @param $id
	 * @return mixed
	 */
	public function changePassword($password, $id)
	{
		if (is_numeric($id)){
			$password = md5(trim($password));
			$query = "UPDATE " . core::database()->getTableName('aut') . " SET password='" . $password . "' WHERE id=" . $id;
			return core::database()->querySQL($query);
		}
	}	
}
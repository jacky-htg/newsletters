<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Model_export_feedback extends Model
{
	/**
	 * @param $id_cat
	 * @return mixed
	 */
	public function getFeedbackList($id_cat){
		$query = "SELECT name, email, city, province, country, zipcode, phone, company, content FROM " . core::database()->getTableName('feedback') . " LEFT JOIN users ON (feedback.user_id = users.id_user)";
                
		$result = core::database()->querySQL($query);
		return core::database()->getColumnArray($result);
	}
}

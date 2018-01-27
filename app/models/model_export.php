<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Model_export extends Model
{
	/**
	 * @param $id_cat
	 * @return mixed
	 */
	public function getUserList($id_cat){
		if ($id_cat) {
			$temp = array();
			foreach ($id_cat as $id) {
				if (is_numeric($id)) {
					$temp[] = $id;
				}
			}

			$query = "SELECT u.name, u.email, u.address, u.city, u.province, u.country, u.zipcode, u.phone, u.company FROM " . core::database()->getTableName('users') . " u
						LEFT JOIN " . core::database()->getTableName('subscription') . " s ON u.id_user=s.id_user
						WHERE status='active' AND s.id_cat IN (" . implode(",", $temp) . ") 
						GROUP BY u.id_user"; //AND (u.address IS NOT NULL OR u.city IS NOT NULL OR u.province IS NOT NULL OR u.country IS NOT NULL OR u.zipcode IS NOT NULL OR u.phone is not null OR u.company is not null)
						
		}
		else{
			$query = "SELECT name, email, address, city, province, country, zipcode, phone, company FROM " . core::database()->getTableName('users') . " WHERE status='active'";//AND (address IS NOT NULL OR city IS NOT NULL OR province IS NOT NULL OR country IS NOT NULL OR zipcode IS NOT NULL OR phone is not null OR company is not null)";
		}

		$result = core::database()->querySQL($query);
		return core::database()->getColumnArray($result);
	}

	/**
	 * @return mixed
	 */
	public function getCategoryList()
	{
		$query =  "SELECT *,cat.id_cat as id FROM ".core::database()->getTableName('category')." cat
					LEFT JOIN ".core::database()->getTableName('subscription')." subs ON cat.id_cat=subs.id_cat
					GROUP by id
					ORDER BY name";

		$result = core::database()->querySQL($query);
		return core::database()->getColumnArray($result);
	}
}

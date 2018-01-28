<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Model_export_links extends Model
{
	/**
	 * @return mixed
	 */
	public function getLinksList(){
		$query = "SELECT users.name, users.email, users.address, users.city, users.province, users.country, users.zipcode, users.phone, users.company, links.url, links.ip, links.country AS country_links, links.city AS city_links, links.created_at  FROM " . core::database()->getTableName('links') . " LEFT JOIN " . core::database()->getTableName('users') . " ON links.user_id=users.id_user";
		$result = core::database()->querySQL($query);
		return core::database()->getColumnArray($result);
	}
}

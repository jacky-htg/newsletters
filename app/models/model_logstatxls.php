<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Model_logstatxls extends Model
{
	/**
	 * @param $id_log
	 * @return mixed
	 */
	public function getTimelog($id_log)
	{
		if (is_numeric($id_log)) {
			$query = "SELECT time FROM " . core::database()->getTableName('log') . " WHERE id_log=" . $id_log;
			$result = core::database()->querySQL($query);
			$row = core::database()->getRow($result);
			return $row['time'];
		}
	}

	/**
	 * @param $id_log
	 * @return mixed
	 */
	public function getTotalfaild($id_log)
	{
		if (is_numeric($id_log)) {
			$query = "SELECT COUNT(*) FROM " . core::database()->getTableName('ready_send') . " WHERE id_log=" . $id_log . " AND success='no'";
			$result = core::database()->querySQL($query);
			$row = core::database()->getRow($result, 'assoc');
			return $row['COUNT(*)'];
		}
	}

	/**
	 * @param $id_log
	 * @return mixed
	 */
	public function getTotaltime($id_log)
	{
		if (is_numeric($id_log)) {
			$query = "SELECT *,sec_to_time(UNIX_TIMESTAMP(max(time)) - UNIX_TIMESTAMP(min(time))) as totaltime FROM " . core::database()->getTableName('ready_send') . " WHERE id_log=" . $id_log;
			$result = core::database()->querySQL($query);
			$row = core::database()->getRow($result);
			return $row['totaltime'];
		}
	}

	/**
	 * @param $id_log
	 * @return mixed
	 */
	public function getLogList($id_log)
	{
		if (is_numeric($id_log)) {
			$query = "SELECT *, a.time as time FROM " . core::database()->getTableName('ready_send') . " a
						LEFT JOIN " . core::database()->getTableName('template') . " b ON b.id_template=a.id_template
						WHERE id_log=" . $id_log;

			$result = core::database()->querySQL($query);
			return core::database()->getColumnArray($result);
		}
	}

	/**
	 * @param $id_log
	 * @return mixed
	 */
	public function getTotalread($id_log)
	{
		if (is_numeric($id_log)) {
			$query = "SELECT COUNT(*) FROM " . core::database()->getTableName('ready_send') . " WHERE id_log=" . $id_log . " AND readmail='yes'";
			$result = core::database()->querySQL($query);
			$total = core::database()->getRow($result, 'assoc');
			return $total['COUNT(*)'];
		}
	}	
}
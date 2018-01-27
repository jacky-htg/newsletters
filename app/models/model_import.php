<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Model_import extends Model
{
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

	/**
	 * @param $email
	 * @return bool
	 */
	public function checkExistEmail($email)
	{		
		$email = core::database()->escape($email);
		$query =  "SELECT * FROM ".core::database()->getTableName('users')." WHERE email LIKE '".$email."'";
		$result = core::database()->querySQL($query);		
				
		if(core::database()->getRecordCount($result) == 0)
			return true;
		else
			return false;	
	}

	/**
	 * @param $id_cat
	 * @return int
	 */
	public function importFromExcel($id_cat)
	{
		core::requireEx('libs', "PHPExcel/PHPExcel/IOFactory.php");
		
		$count = 0;
		
		if ($_FILES['file']['tmp_name']){
			$objPHPExcel = PHPExcel_IOFactory::load($_FILES['file']['tmp_name']);
			$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
			$num = count($sheetData);					
			
			foreach($sheetData as $d){
				$email = trim($d['A']);
				$name = trim($d['B']);

				if (!Pnl::check_email($email)){
					$query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE email LIKE '" . $email . "'";
					$result = core::database()->querySQL($query);
					
					if (core::database()->getRecordCount($result) > 0){
						$row = core::database()->getRow($result);
						$delete = "DELETE FROM " . core::database()->getTableName('subscription') . " WHERE id_user=" . $row['id_user'];
						
						core::database()->delete(core::database()->getTableName('subscription'),"id_user=" . $row['id_user'],'');

						foreach($id_cat as $id){
							if(is_numeric($id))	{
								$fields = array();
								$fields['id_sub'] = 0;
								$fields['id_user'] = $row['id_user'];
								$fields['id_cat'] = $id;
									
								$insert_id = core::database()->insert($fields, core::database()->getTableName('subscription'));
							}
						}
					}
					else{
						$fields = array();
						$fields['id_user'] = 0;
						$fields['name'] = $name;
						$fields['email'] = $email;
						$fields['token'] = Pnl::getRandomCode();
						$fields['time'] = date("Y-m-d H:i:s");
						$fields['status'] = 'active';
						
						$insert_id = core::database()->insert($fields, core::database()->getTableName('users'));
						
						if($insert_id) $count++;
						
						foreach($id_cat as $id){
							if(is_numeric($id)){
								$subfields = array();
								$subfields['id_sub'] = 0;
								$subfields['id_user'] = $insert_id;
								$subfields['id_cat'] = $id;
									
								$insert_id2 = core::database()->insert($subfields, core::database()->getTableName('subscription'));
							}
						}
					}
				}
			}
		}	

		return $count;	
	}

	/**
	 * @param $id_cat
	 * @return bool|int
	 */
	public function importFromText($id_cat)
	{
		core::requireEx('libs', "ConvertCharset/ConvertCharset.class.php");

		if (!($fp = @fopen($_FILES['file']['tmp_name'], "rb"))) {
			return false;
		} else {
			$buffer = fread($fp, filesize($_FILES['file']['tmp_name']));
			fclose($fp);
			$tok = strtok($buffer, "\n");
			$strtmp[] = $tok;

			while ($tok) {
				$tok = strtok("\n");
				$strtmp[] = $tok;
			}

			$count = 0;

			for ($i = 0; $i < count($strtmp); $i++) {
				$email = "";
				$name = "";

				if (!mb_check_encoding($strtmp[$i], 'utf-8') && core::getSetting('id_charset')) {
					$sh = new ConvertCharset(core::getSetting('id_charset'), "utf-8");
					$strtmp[$i] = $sh->Convert($strtmp[$i]);
				}

				preg_match('/([a-z0-9&\-_.]+?)@([\w\-]+\.([\w\-\.]+\.)*[\w]+)/uis', $strtmp[$i], $out);

				$email = isset($out[0]) ? $out[0] : '';
				$name = str_replace($email, '', $strtmp[$i]);
				$email = strtolower($email);
				$name = trim($name);

				if (strlen($name) > 250) {
					$name = '';
				}

				if ($email) {
					$query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE email LIKE '" . $email . "'";
					$result = core::database()->querySQL($query);

					if (core::database()->getRecordCount($result) > 0) {
						$row = core::database()->getRow($result);

						$delete = "DELETE FROM " . core::database()->getTableName('subscription') . " WHERE id_user=" . $row['id_user'];
						core::database()->delete(core::database()->getTableName('subscription'), "id_user=" . $row['id_user'], '');

						if ($id_cat) {
							foreach ($id_cat as $id) {
								if (is_numeric($id)) {
									$fields = array();
									$fields['id_sub'] = 0;
									$fields['id_user'] = $row['id_user'];
									$fields['id_cat'] = $id;

									$insert_id = core::database()->insert($fields, core::database()->getTableName('subscription'));
								}
							}
						}

					} else {
						$email = core::database()->escape($email);
						$name = core::database()->escape($name);

						$fields = array();
						$fields['id_user'] = 0;
						$fields['name'] = $name;
						$fields['email'] = $email;
						$fields['token'] = Pnl::getRandomCode();
						$fields['time'] = date("Y-m-d H:i:s");
						$fields['status'] = 'active';

						$insert_id = core::database()->insert($fields, core::database()->getTableName('users'));

						if ($insert_id) $count++;

						if ($id_cat) {
							foreach ($id_cat as $id) {
								if (is_numeric($id)) {
									$fields = array();
									$fields['id_sub'] = 0;
									$fields['id_user'] = $insert_id;
									$fields['id_cat'] = $id;

									$insert_id2 = core::database()->insert($fields, core::database()->getTableName('subscription'));
								}
							}
						}
					}
				}
			}
		}

		return $count;
	}
}
<?php

defined('LETTER') || exit('NewsLetter: access denied.');

set_time_limit(0);

// authorization
Auth::authorization();

$autInfo = Auth::getAutInfo(Auth::getAutId());

if (Pnl::CheckAccess($autInfo['role'], 'admin')) throw new Exception403(core::getLanguage('str', 'dont_have_permission_to_access'));

if (Core_Array::getRequest('action')) {
	$arr = $data->getUserList(Core_Array::getRequest('id_cat'));
	
	if (intval(Core_Array::getRequest('export_type')) == 1) {
		$ext = 'txt';
		$filename = 'emailexport.txt';			
			
		if (is_array($arr)) {
			$contents = '';	
			foreach ($arr as $row) {
				$contents .= "" . $row['email'] . " " . $row['name'] . " " . $row['address'] . " " . $row['city'] . " " . $row['province'] . " " . $row['country'] . " " . $row['zipcode'] . " " . $row['phone'] . " " . $row['company'] . "\r\n";
			}
		}
	} elseif (intval(Core_Array::getRequest('export_type')) == 2) {
		$ext = 'xls';
		$filename = 'emailexport.xls';
			
		core::requireEx('libs', "PHPExcel/PHPExcel.php");
			
		$pExcel = core::factory('PHPExcel');
		$pExcel->setActiveSheetIndex(0);
		$aSheet = $pExcel->getActiveSheet();
		$aSheet->setTitle(core::getLanguage('str', 'emals_db'));

		$aSheet->setCellValue('A1',core::getLanguage('str', 'user_email'));
		$aSheet->setCellValue('B1',core::getLanguage('str', 'name'));
		$aSheet->setCellValue('C1',core::getLanguage('str', 'address'));
		$aSheet->setCellValue('D1',core::getLanguage('str', 'city'));
		$aSheet->setCellValue('E1',core::getLanguage('str', 'province'));
		$aSheet->setCellValue('F1',core::getLanguage('str', 'country'));
		$aSheet->setCellValue('G1',core::getLanguage('str', 'zipcode'));
		$aSheet->setCellValue('H1',core::getLanguage('str', 'phone'));
		$aSheet->setCellValue('I1',core::getLanguage('str', 'occupation'));
		$aSheet->setCellValue('J1',core::getLanguage('str', 'has_subscribe'));
			
		$i=1;

		foreach ($arr as $row) {
			$i++;
			$aSheet->setCellValue('A'.$i, $row['email']);
			$aSheet->setCellValue('B'.$i, $row['name']);
			$aSheet->setCellValue('C'.$i, $row['address']);
			$aSheet->setCellValue('D'.$i, $row['city']);
			$aSheet->setCellValue('E'.$i, $row['province']);
			$aSheet->setCellValue('F'.$i, $row['country']);
			$aSheet->setCellValue('G'.$i, $row['zipcode']);
			$aSheet->setCellValue('H'.$i, $row['phone']);
			$aSheet->setCellValue('I'.$i, $row['company']);
			$hasSubscribe = false;
			if (!empty($row['address']) || !empty($row['city']) || !empty($row['province']) || !empty($row['country']) || !empty($row['zipcode']) || !empty($row['phone']) || !empty($row['company'])){
			    $hasSubscribe = true;
			}
			$aSheet->setCellValue('J'.$i, $hasSubscribe);
		}
			
		$aSheet->getColumnDimension('A')->setWidth(20);
		$aSheet->getColumnDimension('B')->setWidth(30);
		$aSheet->getColumnDimension('C')->setWidth(50);
		$aSheet->getColumnDimension('D')->setWidth(15);
		$aSheet->getColumnDimension('E')->setWidth(15);
		$aSheet->getColumnDimension('F')->setWidth(15);
		$aSheet->getColumnDimension('G')->setWidth(15);
		$aSheet->getColumnDimension('H')->setWidth(15);
		$aSheet->getColumnDimension('I')->setWidth(15);
		$aSheet->getColumnDimension('J')->setWidth(15);
			
		core::requireEx('libs', "PHPExcel/PHPExcel/Writer/Excel5.php");
			
		$objWriter = new PHPExcel_Writer_Excel5($pExcel);
			
		ob_start();
		$objWriter->save('php://output');
		$contents = ob_get_contents();
		ob_end_clean();
	}
		
	if (intval(Core_Array::getRequest('zip')) == 2){
		header('Content-type: application/zip');
		header('Content-Disposition: attachment; filename=emailexport.zip');
		
		$fout = fopen("php://output", "wb");
	
		if ($fout !== false){
			fwrite($fout, "\x1F\x8B\x08\x08".pack("V", '')."\0\xFF", 10);

			$oname = str_replace("\0", "", $filename);
			fwrite($fout, $oname."\0", 1+strlen($oname));
			
			$fltr = stream_filter_append($fout, "zlib.deflate", STREAM_FILTER_WRITE, -1);
			$hctx = hash_init("crc32b");
  
			if (!ini_get("safe_mode")) set_time_limit(0);
		 
			hash_update($hctx, $contents);
			$fsize = strlen($contents);
		
			fwrite($fout, $contents, $fsize);

			stream_filter_remove($fltr);

			$crc = hash_final($hctx, TRUE);

			fwrite($fout, $crc[3] . $crc[2] . $crc[1] . $crc[0], 4);
			fwrite($fout, pack("V", $fsize), 4);
			fclose($fout);
		}		
		exit();		
	} else {
		header('Content-Type: ' . Pnl::get_mime_type($ext));
		header('Content-Disposition: attachment; filename=' . $filename);
		header('Cache-Control: max-age=0');
		echo $contents;
		exit;		
	}
}

// require temlate class
core::requireEx('libs', "html_template/SeparateTemplate.php");
$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . core::getSetting('controller') . ".tpl");

$tpl->assign('TITLE_PAGE', core::getLanguage('title_page', 'export'));
$tpl->assign('TITLE', core::getLanguage('title', 'export'));
$tpl->assign('INFO_ALERT', core::getLanguage('info', 'edit_user'));

include_once core::pathTo('extra', 'top.php');

//menu
include_once core::pathTo('extra', 'menu.php');

$tpl->assign('STR_BACK', core::getLanguage('str', 'return_back'));

//form
$tpl->assign('ACTION', $_SERVER['REQUEST_URI']);
$tpl->assign('STR_EXPORT', core::getLanguage('str', 'export'));
$tpl->assign('STR_COMPRESSION', core::getLanguage('str', 'compression'));

$tpl->assign('STR_EXPORT_TEXT', core::getLanguage('str', 'export_type_text'));
$tpl->assign('STR_EXPORT_CSV', core::getLanguage('str', 'export_type_cvs'));
$tpl->assign('STR_EXPORT_EXCEL', core::getLanguage('str', 'excel'));
$tpl->assign('STR_COMPRESSION_OPTION_1', core::getLanguage('str', 'compression_option_1'));
$tpl->assign('STR_COMPRESSION_OPTION_2', core::getLanguage('str', 'compression_option_2'));
$tpl->assign('STR_CATEGORY', core::getLanguage('str', 'category'));

foreach ($data->getCategoryList() as $row) {
	$rowBlock = $tpl->fetch('categories_list');
	$rowBlock->assign('ID_CAT', $row['id']);
	$rowBlock->assign('NAME', $row['name']);
	$tpl->assign('categories_list', $rowBlock);
}

$tpl->assign('BUTTON_APPLY',core::getLanguage('button', 'apply'));

//footer
include_once core::pathTo('extra', 'footer.php');

// display content
$tpl->display();

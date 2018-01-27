<?php

defined('LETTER') || exit('NewsLetter: access denied.');

// authorization
Auth::authorization();

$autInfo = Auth::getAutInfo(Auth::getAutId());

if (Pnl::CheckAccess($autInfo['role'], 'admin,moderator')) throw new Exception403(core::getLanguage('str', 'dont_have_permission_to_access'));
if (!is_numeric($_GET['id_log'])) exit();

core::requireEx('libs', "PHPExcel/PHPExcel.php");
	
$pExcel = new PHPExcel();
$pExcel->setActiveSheetIndex(0);
$aSheet = $pExcel->getActiveSheet();
$aSheet->setTitle(core::getLanguage('str', 'mailing_report'));

$timelog = $data->getTimelog($_GET['id_log']);
$totalfaild = $data->getTotalfaild($_GET['id_log']);
$totaltime = $data->getTotaltime($_GET['id_log']);
$readmail = $data->getTotalread($_GET['id_log']);

$arr = $data->getLogList($_GET['id_log']);

if (is_array($arr)) {
	$success = count($arr) - $totalfaild;
	$count = 100 * $success / count($arr);
	$total = count($arr);
} else {
	$success = 0;
	$count = 0;
	$total = 0;
}

$aSheet->setCellValue('A1', "" .  core::getLanguage('str', 'total') . ":" . count($arr) . " \n" . core::getLanguage('str', 'sent') . ":" . intval($count) . " %\n" . core::getLanguage('str', 'spent_time') . ":" . $totaltime . "\n" .  core::getLanguage('str', 'read') . ":" . $readmail . "");
$aSheet->getStyle('A1')->getAlignment()->setWrapText(true);

$aSheet->setCellValue('A2',core::getLanguage('str', 'email'));
$aSheet->setCellValue('B2',core::getLanguage('str', 'time'));
$aSheet->setCellValue('C2',core::getLanguage('str', 'status'));

$aSheet->setCellValue('A2',core::getLanguage('str', 'mailer'));
$aSheet->setCellValue('B2',core::getLanguage('str', 'email'));
$aSheet->setCellValue('C2',core::getLanguage('str', 'time'));
$aSheet->setCellValue('D2',core::getLanguage('str', 'status'));
$aSheet->setCellValue('E2',core::getLanguage('str', 'read'));
$aSheet->setCellValue('F2',core::getLanguage('str', 'error'));
$aSheet->mergeCells('A1:F1');
$aSheet->getStyle('A2')->getFill()->getStartColor()->setRGB('E3DA62');	
$aSheet->getStyle('A1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$aSheet->getStyle('A1')->getFill()->getStartColor()->setRGB('EEEEEE');
$aSheet->getStyle('A2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$aSheet->getStyle('A2')->getFill()->getStartColor()->setRGB('EE7171');
$aSheet->getStyle('B2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$aSheet->getStyle('B2')->getFill()->getStartColor()->setRGB('EE7171');
$aSheet->getStyle('C2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$aSheet->getStyle('C2')->getFill()->getStartColor()->setRGB('EE7171');
$aSheet->getStyle('D2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$aSheet->getStyle('D2')->getFill()->getStartColor()->setRGB('EE7171');
$aSheet->getStyle('E2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$aSheet->getStyle('E2')->getFill()->getStartColor()->setRGB('EE7171');
$aSheet->getStyle('F2')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID);
$aSheet->getStyle('F2')->getFill()->getStartColor()->setRGB('EE7171');
	
$aSheet->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$aSheet->getStyle('B2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$aSheet->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$aSheet->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$aSheet->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
$aSheet->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
	
if (is_array($arr)){
	$i=2;

	foreach($arr as $row){
		$i++;
		$status = $row['success'] == 'yes' ? core::getLanguage('str', 'send_status_yes') : core::getLanguage('str', 'send_status_no');
		$readmail = $row['readmail'] == 'yes' ? core::getLanguage('str', 'yes') : core::getLanguage('str', 'no');
		
		$aSheet->setCellValue('A'.$i, $row['email']);
		$aSheet->setCellValue('B'.$i, $row['time']);
		$aSheet->setCellValue('C'.$i, $status);		
		
		$aSheet->getStyle('D'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$aSheet->getStyle('E'.$i)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		
		$aSheet->setCellValue('A'.$i, $row['name']);
		$aSheet->setCellValue('B'.$i, $row['email']);
		$aSheet->setCellValue('C'.$i, $row['time']);
		$aSheet->setCellValue('D'.$i, $status);
		$aSheet->setCellValue('E'.$i, $readmail);
		$aSheet->setCellValue('F'.$i, $row['errormsg']);
	}
}

$aSheet->getRowDimension(1)->setRowHeight(70);
$aSheet->getColumnDimension('A')->setWidth(30);
$aSheet->getColumnDimension('B')->setWidth(25);
$aSheet->getColumnDimension('C')->setWidth(15);
$aSheet->getColumnDimension('D')->setWidth(15);
$aSheet->getColumnDimension('E')->setWidth(10);
$aSheet->getColumnDimension('F')->setWidth(35);
				
core::requireEx('libs', 'PHPExcel/PHPExcel/Writer/Excel5.php');

$objWriter = new PHPExcel_Writer_Excel5($pExcel);
header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="logstat_' . $timelog . '.xls"');
header('Cache-Control: max-age=0');
$objWriter->save('php://output');
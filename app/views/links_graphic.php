<?php

defined('LETTER') || exit('NewsLetter: access denied.');

// authorization
Auth::authorization();

$autInfo = Auth::getAutInfo(Auth::getAutId());

if (Pnl::CheckAccess($autInfo['role'], 'admin,moderator')) throw new Exception403(core::getLanguage('str', 'dont_have_permission_to_access'));

//horizontal menu
$tpl->assign('STR_EXPORT_LINKS', 'Detail');
$tpl->assign('PROMPT_EXPORT_LINKS',   'detail');

$arr = $data->getCitiesDemographics();
if($arr) {
    $rowBlock = $tpl->fetch('row');
    $citiesBlock = $tpl->fetch('cities');
    $other = 0;
    
    foreach ($arr as $i => $row) { 
        if ($i<10) {
            $dataBlock = $citiesBlock->fetch('data');
            $dataBlock->assign('CITY', $row['city']);
            $dataBlock->assign('TOTAL', $row['jumlah']);
            $citiesBlock->assign('data', $dataBlock);
            
            $columnBlock = $rowBlock->fetch('column');
            $columnBlock->assign('CITY', $row['city']);
            $columnBlock->assign('COUNTRY', $row['country']);
            $columnBlock->assign('TOTAL', $row['jumlah']);
            $rowBlock->assign('column', $columnBlock);
        }
        else {
            $other += $row['jumlah'];
        }
    }
    
    if ($other) {
        $dataBlock = $citiesBlock->fetch('data');
        $dataBlock->assign('CITY', 'Other');
        $dataBlock->assign('TOTAL', $other);
        $citiesBlock->assign('data', $dataBlock);
            
        $columnBlock = $rowBlock->fetch('column');
        $columnBlock->assign('CITY', 'OTHER');
        $columnBlock->assign('COUNTRY', 'OTHER');
        $columnBlock->assign('TOTAL', $other);
        $rowBlock->assign('column', $columnBlock);
    }
    
    $tpl->assign('row', $rowBlock);
    $tpl->assign('cities', $citiesBlock);
}
else {
    $tpl->assign('notfound', 'no data');
}
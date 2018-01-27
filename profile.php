<?php

//Error_Reporting(0);

//set_time_limit(0);

define('LETTER', TRUE);

require_once "config/config_db.php";

$dbh = new mysqli($ConfigDB["host"], $ConfigDB["user"], $ConfigDB["passwd"], $ConfigDB["name"]);

if (mysqli_connect_errno()){
	exit("Error connecting to MySQL database: Database server " . $ConfigDB["host"] . " is not available!");
}

if ($ConfigDB["charset"] != '') {
	$dbh->query("SET NAMES " . $ConfigDB["charset"] . "");
}

$query = "SELECT * FROM " . $ConfigDB["prefix"] . "users WHERE id_user={$_GET['id']} AND token='{$_GET['token']}'";
$result = $dbh->query($query);

if (!$result) exit('Error executing SQL query!');

$user = $result->fetch_array(1);
$result->close();

if ($_GET['id'] != 0 && empty($user)) exit('Invalid Token!');

if(empty($user['city']) || empty($user['phone'])) {
    if ('POST' === $_SERVER['REQUEST_METHOD']) {
        if (validate($_POST)) {
            $update = "UPDATE " . $ConfigDB["prefix"] . "users SET address='{$_POST['address']}', city='{$_POST['city']}', province='{$_POST['province']}', country='{$_POST['country']}', zipcode='{$_POST['zipcode']}', phone='{$_POST['phone']}', company='{$_POST['company']}' WHERE id_user={$_GET['id']}";
            if (!$dbh->query($update)){
                exit('Gagal update profile');
            }
            $dbh->close();
            header("Location:{$_GET['url']}");
        }
    }
    include ('form_profile.php'); 
    $dbh->close();
}
else {
    header("Location:{$_GET['url']}");
}


function validate($form){
    /*if (empty($form['address'])) {
        $_POST['message'] = 'Please input valid address';
        return false;    
    }
    else*/
    if (empty($form['city'])) {
        $_POST['message'] = 'Please input valid city';
        return false;    
    }
    /*elseif (empty($form['province'])) {
        $_POST['message'] = 'Please input valid province';
        return false;    
    }
    /*elseif (empty($form['country'])) {
        $_POST['message'] = 'Please input valid country';
        return false;    
    }*/
    elseif (empty($form['phone'])) {
        $_POST['message'] = 'Please input valid phone';
        return false;    
    }
    
    return true;
}

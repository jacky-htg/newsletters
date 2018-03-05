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
            
            // save the log url
            $ip = get_client_ip_server();
            if ($ip && 'UNKNOWN' !== $ip) {
                $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
                if(!$query || $query['status'] != 'success') {
                    $query = ['city'=> '', 'country' => ''];
                }
            }

            $insert = "INSERT INTO " . $ConfigDB["prefix"] . "links (`url`, `user_id`, `ip`, `country`, `city`) VALUES ('{$_GET['url']}', {$_GET['id']}, '{$ip}', '{$query['country']}', '{$query['city']}')";
            if (!$dbh->query($insert)){
                exit('Gagal insert log link');
            }

            header("Location:{$_GET['url']}");
        }
    }
    include ('form_profile.php'); 
    $dbh->close();
}
else {
    // save the log url
    $ip = get_client_ip_server();
    if ($ip && 'UNKNOWN' !== $ip) {
        $query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
        if(!$query || $query['status'] != 'success') {
            $query = ['city'=> '', 'country' => ''];
        }
    }

    $insert = "INSERT INTO " . $ConfigDB["prefix"] . "links (`url`, `user_id`, `ip`, `country`, `city`) VALUES ('{$_GET['url']}', {$_GET['id']}, '{$ip}', '{$query['country']}', '{$query['city']}')";
    if (!$dbh->query($insert)){
        exit('Gagal insert log link');
    }

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

function get_client_ip_server() {
    $ipaddress = '';
    if ($_SERVER['HTTP_CLIENT_IP'])
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if($_SERVER['HTTP_X_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if($_SERVER['HTTP_X_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if($_SERVER['HTTP_FORWARDED_FOR'])
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if($_SERVER['HTTP_FORWARDED'])
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if($_SERVER['REMOTE_ADDR'])
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}

<?php

Error_Reporting(0);

set_time_limit(0);

define('LETTER', TRUE);

require_once "config/config_db.php";
require_once "sys/engine/classes/Pnl.php";
require_once "vendor/PHPMailer/class.phpmailer.php";

$dbh = new mysqli($ConfigDB["host"], $ConfigDB["user"], $ConfigDB["passwd"], $ConfigDB["name"]);

if (mysqli_connect_errno()){
	exit("Error connecting to MySQL database: Database server " . $ConfigDB["host"] . " is not available!");
}

if ($ConfigDB["charset"] != '') {
	$dbh->query("SET NAMES " . $ConfigDB["charset"] . "");
}

$fh = fopen(__FILE__, 'r');

if (!flock($fh, LOCK_EX | LOCK_NB)){
	exit('Script is already running');
}

$mailcountno = 0;
$mailcount = 0;

$query = "SELECT * FROM " . $ConfigDB["prefix"] . "settings";
$result = $dbh->query($query);

if (!$result) exit('Error executing SQL query!');

$settings = $result->fetch_array();
$result->close();

$insert = "INSERT INTO " . $ConfigDB["prefix"] . "log (`id_log`, `time`) VALUES (0, NOW())";
$result = $dbh->prepare($insert);
	
if (!$result) exit('Error executing SQL query!');
$result->execute();	
$id_log = $result->insert_id;

$query = "SELECT * FROM " . $ConfigDB["prefix"] . "charset WHERE id_charset=" . $settings['id_charset'];
$result = $dbh->query($query);

if (!$result) exit('Error executing SQL query!');

$char = $result->fetch_array();
$charset = $char['charset'];

$result->close();

if ($charset != 'utf-8') {
	$from = iconv('utf-8',$charset,$from);
	if (!empty($settings['organization'])) $settings['organization'] = iconv('utf-8', $charset, $settings['organization']);
}	
		
$from = $settings['email_name'] == '' ? $_SERVER["SERVER_NAME"] : $settings['email_name'];

$query = "SELECT * FROM " . $ConfigDB["prefix"] . "template WHERE active='yes' ORDER by pos";
$result_send = $dbh->query($query);

if (!$result_send) exit('Error executing SQL query!');

if ($result_send->num_rows > 0){
	$m = new PHPMailer();	

	if($settings['add_dkim'] == 'yes' && file_exists($settings['dkim_private'])){
		$m->DKIM_domain = $settings['dkim_domain'];
		$m->DKIM_private = $settings['dkim_private'];
		$m->DKIM_selector = $settings['dkim_selector'];
		$m->DKIM_passphrase = $settings['dkim_passphrase'];
		$m->DKIM_identity = $settings['dkim_identity'];		
	}
		
	if ($settings['how_to_send'] == 2){
		$m->IsSMTP();			
		$m->SMTPAuth = true;
		$m->SMTPKeepAlive = true;
		$m->Host = $settings['smtp_host'];
		$m->Port = $settings['smtp_port'];
		$m->Username = $settings['smtp_username'];
		$m->Password = $settings['smtp_password'];
			
		if ($settings['smtp_secure'] == 'ssl')
			$m->SMTPSecure  = 'ssl';
		elseif ($settings['smtp_secure'] == 'tls')
			$m->SMTPSecure  = 'tls';
				
		if ($settings['smtp_aut'] == 'plain')
			$m->AuthType = 'PLAIN';
		elseif ($settings['smtp_aut'] == 'cram-md5')
			$m->AuthType = 'CRAM-MD5';
		
		$m->Timeout = $settings['smtp_timeout'];
	} elseif ($settings['how_to_send'] == 3 and !empty($settings['sendmail'])){
		$m->IsSendmail();
		$m->Sendmail = $settings['sendmail'];
	} else {
		$m->IsMail();
	}

	while($send = $result_send->fetch_array()) {
		$m->CharSet = $charset;
		
		if ($send['prior'] == "1")
			$m->Priority = 1;
		elseif ($send['prior'] == "2")
			$m->Priority = 5;
		else $m->Priority = 3;

		if ($settings['show_email'] == "no")
			$m->From = "noreply@".$_SERVER['SERVER_NAME']."";
		else 
			$m->From = $settings['email'];	
					
		$m->FromName = $from;

		if (!empty($settings['list_owner'])) $m->addCustomHeader("List-Owner: <" . $settings['list_owner'] . ">");
		if (!empty($settings['return_path'])) $m->addCustomHeader("Return-Path: <" . $settings['return_path'] . ">");
		if ($settings['content_type'] == 2)
			$m->isHTML(true);
		else	
			$m->isHTML(false);			
			
		if ($settings['interval_type'] == 'm')
			$interval = "AND (time_send < NOW() - INTERVAL '" . $settings['interval_number'] . "' MINUTE)";
		elseif ($settings['interval_type'] == 'h')
			$interval = "AND (time_send < NOW() - INTERVAL '" . $settings['interval_number'] . "' HOUR)";
		elseif ($settings['interval_type'] == 'd')
			$interval = "AND (time_send < NOW() - INTERVAL '" . $settings['interval_number'] . "' DAY)";
		else  
			$interval = '';

		$order = $settings['random'] == "yes" ? 'ORDER BY RAND()' : '';	
		$limit = $settings['make_limit_send'] == "yes" ? "LIMIT " . $settings['limit_number'] . "" : "";
	
		if ($settings['re_send'] == "no") {
			if ($send['id_cat'] == 0)
				$query_users = "SELECT *,u.id_user AS id, u.email AS email FROM " . $ConfigDB["prefix"] . "users u
								LEFT JOIN " . $ConfigDB["prefix"] . "ready_send r ON u.id_user=r.id_user AND r.id_template=" . $send['id_template']."
								WHERE (r.id_user IS NULL) AND (status='active') " . $interval . " " . $order . " " . $limit . "";
			else 
				$query_users = "SELECT *,u.id_user AS id, u.email AS email FROM " . $ConfigDB["prefix"] . "users u
								LEFT JOIN " . $ConfigDB["prefix"] . "subscription s ON u.id_user=s.id_user
								LEFT JOIN " . $ConfigDB["prefix"] . "ready_send r ON u.id_user=r.id_user AND r.id_template=" . $send['id_template']."
								WHERE (r.id_user IS NULL) AND (id_cat=".$send['id_cat'].") AND (status='active') " . $interval . " " . $order . " " . $limit . "";
		}
		else{
			if ($send['id_cat'] == 0)
				$query_users = "SELECT *,id_user AS id FROM ". $ConfigDB["prefix"] . "users WHERE status='active' " . $interval . " ".$order." " . $limit . "";
			else 
				$query_users = "SELECT *,u.id_user AS id, u.email AS email FROM " . $ConfigDB["prefix"] . "users u
								LEFT JOIN ". $ConfigDB["prefix"] ."subscription s ON u.id_user=s.id_user
								WHERE (id_cat=".$send['id_cat'].") AND (status='active') " . $interval . " " . $order . " " . $limit . "";
		}						
		
		$result_users = $dbh->query($query_users);
		
		if (!$result_users) exit('Error executing SQL query!');
		
		while($user = $result_users->fetch_array()){
			$subject = $send['name'];
			$subject = str_replace('%NAME%', $user['name'], $subject);
			
			if ($charset != 'utf-8'){
				$subject = iconv('utf-8', $charset, $subject);
			}
					
			$m->Subject = $subject;		
		
			if ($settings['sleep'] && $settings['sleep'] > 0) sleep($settings['sleep']);
			if (!empty($settings['organization'])) $m->addCustomHeader("Organization: " . $settings['organization'] . "");
			if (!empty($settings['path'])) $IMG = '<img border="0" src="' . $settings['path'] . '?t=pic&id_user=' . $user['id'] . '&id_template=' . $send['id_template'] . '" width="1" height="1">';
			
			$m->AddAddress($user['email']);

			if ($settings['request_reply'] == 'yes' && !empty($settings['email'])){
				$m->addCustomHeader("Disposition-Notification-To: " . $settings['email'] . "");
				$m->ConfirmReadingTo = $settings['email'];
			}			

			if ($settings['precedence'] == 'bulk')
				$m->addCustomHeader("Precedence: bulk");
			elseif ($settings['precedence'] == 'junk')
				$m->addCustomHeader("Precedence: junk");
			elseif ($settings['precedence'] == 'list')
				$m->addCustomHeader("Precedence: list");				
				
			if (!empty($settings['path'])) $UNSUB = $settings['path'] . "?t=unsubscribe&id=" . $user['id'] . "&token=" . $user['token'] . "";

			$unsublink = str_replace('%UNSUB%', $UNSUB, $settings['unsublink']);

			if ($settings['show_unsubscribe_link'] == "yes" && !empty($settings['unsublink'])) {
				$msg = "".$send['body']."<br><br>" . $unsublink . "";
				$m->addCustomHeader("List-Unsubscribe: " . $UNSUB . "");
			} else
				$msg = $send['body'];

			$url_info = parse_url($settings['path']);

			$msg = str_replace('%NAME%', $user['name'], $msg);
			$msg = str_replace('%UNSUB%', $UNSUB, $msg);
			$msg = str_replace('%SERVER_NAME%', $url_info['host'], $msg);
			$msg = str_replace('%USERID%', $user['id'], $msg);
			$msg = str_replace('%USERTOKEN%', $user['token'], $msg);				
				
			$query = "SELECT * FROM " . $ConfigDB["prefix"] . "attach WHERE id_template=" . $send['id_template'];
		
			$result_attach = $dbh->query($query);
				
			while($row = $result_attach->fetch_array()){
				if ($fp = @fopen($row['path'], "rb")){
					$file = fread($fp, filesize($row['path']));

					fclose($fp);

					if ($charset != 'utf-8') $row['name'] = iconv('utf-8', $charset, $row['name']);

					$ext = strrchr($row['path'], ".");
					$mime_type = Pnl::get_mime_type($ext);

					$m->AddAttachment($row['path'], $row['name'], 'base64', $mime_type);
				}					
			}
				
			$result_attach->close();

			if ($charset != 'utf-8') $msg = iconv('utf-8', $charset, $msg);
				
			if ($settings['content_type'] == 2){
				$msg .= $IMG;
			} else {
				$msg = preg_replace('/<br(\s\/)?>/i', "\n", $msg);
				$msg = Pnl::remove_html_tags($msg);
			}	
				
			$m->Body = $msg;	

			if (!$m->Send()){
				$errormsg = $m->ErrorInfo;			
				$insert = "INSERT INTO " . $ConfigDB["prefix"] . "ready_send (`id_ready_send`,`id_user`, `email`, `id_template`,`success`,`errormsg`,`readmail`,`time`,`id_log`) VALUES (0,".$user['id'].",'".$user['email']."',".$send['id_template'].",'no','".$errormsg."','no', NOW(),".$id_log.")";
				$dbh->query($insert);
				$mailcountno = $mailcountno + 1;
			} else {
				$insert = "INSERT INTO " . $ConfigDB["prefix"] . "ready_send (`id_ready_send`,`id_user`, `email`, `id_template`,`success`,`errormsg`,`readmail`,`time`,`id_log`) VALUES (0,".$user['id'].",'".$user['email']."',".$send['id_template'].",'yes','','no', NOW(),".$id_log.")";
				$dbh->query($insert);

				$update = "UPDATE " . $ConfigDB["prefix"] . "users SET time_send = NOW() WHERE id_user=" . $user['id'];
				$dbh->query($update);

				$mailcount = $mailcount + 1;
			}	
				
			$m->ClearCustomHeaders(); 
			$m->ClearAllRecipients();
			$m->ClearAttachments();	

			if ($settings['make_limit_send'] == "yes" && $settings['limit_number'] == $mailcount){
				if ($settings['how_to_send'] == 2) $m->SmtpClose();
				break;
			}
		}
			
		if ($settings['make_limit_send'] == "yes" && $settings['limit_number'] == $mailcount){
			if ($settings['how_to_send'] == 2) $m->SmtpClose();
			break;
		}				
	}
}

$result_send->close();

$dbh->close();

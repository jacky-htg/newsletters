<?php

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

if (empty($user)){
    $_POST['message'] = 'Maaf token Anda tidak valid';
}
elseif ('POST' === $_SERVER['REQUEST_METHOD']) {
    if (empty($_POST['content'])) {
        $_POST['message'] = 'Please input valid feedback';
    }
    else {
        $insert = "INSERT INTO " . $ConfigDB["prefix"] . "feedback (user_id, content, created) VALUES ({$user['id_user']}, '{$_POST['content']}', NOW())";
        if (!$dbh->query($insert)){
            exit('Gagal insert feedback');
        }
        else {
            header("Location:http://independen.id");
        }
    }
}

$dbh->close();
?>

<html>
<head>
    <style>
        * {
            margin:0; padding:0;   
        }
        
        body {
            background : #ededed;
        }
        
        #container {
            width:900px;
            margin:0 auto;
            background : #fff;
            height: 100%;
            padding:1% 1% 0 1%;
        }
        
        header h1 {
            float:right;
            font-size: 80px;
            padding: 1%;
        }
        
        header img {
            float:right;
            width :100px;
        }
        
        article {
            border: 1px solid #ccc;
            padding: 1%;
            clear:both;
            background : #ededed;
        }
        
        h3, h2, p, div {
            padding: 1% 0;
        }
        
        label {
            display:block;
            float:left;
            width:200px;
        }
        input, textarea {
            float:left;
            width:400px;
        }
        textarea {
            height:100px;
        }
        form div {
            clear:both;
        }
        form h3 {
            color:#ff0000;
        }
        
        footer {
            width:900px; 
            background:#d22027; 
            position:absolute; 
            border-radius:50px 0 0 0; 
            color:#fff; 
            text-align:right;
            height:50px;
            bottom: 0;
        }
    </style>
</head>
<body>
    <div id="container">
        <header>
            <img src="./img/id.jpg">
            <h1>Independen</h1>
            
        </header>
        
        <article>
            <h2>Feedback</h2>
            <p>Silahkan memberikan saran atau komentar melalui form berikut. Terima kasih.</p>
        </article>
        <form method="POST">
            <?php if (isset($_POST['message'])) : ?>
            <h3><?php echo $_POST['message'];?></h3>
            <?php endif;?>

            <div><label>Name</label><input type="text" name="name" value="<?php echo $user['name'];?>" disabled></div>
            <div><label>Email</label><input type="email" name="email" value="<?php echo $user['email'];?>" disabled></div>
            <div><label>Feedback *</label><textarea name="content"><?php echo $_POST['content'];?></textarea></div>
            <div><button type="submit">SUBMIT</button></div>
        </form>
        <footer> &nbsp;</footer>
    </div>
</body>
</html>

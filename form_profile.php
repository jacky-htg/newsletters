<?php 
    defined('LETTER') || exit('NewsLetter: access denied.'); 
    
    if (isset($_POST['city'])) {
        //$user['address']    = $_POST['address'];
        $user['city']       = $_POST['city'];
        $user['province']   = $_POST['province'];
        $user['country']    = $_POST['country'];
        $user['zipcode']    = $_POST['zipcode'];
        $user['phone']      = $_POST['phone'];
        $user['company']    = $_POST['company'];
    }
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
            padding:1%;
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
        
        h2, p, div {
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
            width:100%;background:#d22027; border-radius:50px 0 0 0; color:#fff; text-align:right;padding:1%;
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
            <h3>Mohon melengkapi data profile terlebih dahulu</h3>
            <p>Mohon mengisi data profil yang bertanda * terlebih dahulu. Setelah data profile Anda lengkap, kami akan mengalihkan halaman ke link newsletter yang Anda tuju. Anda akan menerima newsletter ini secara berkala. Terima kasih.</p>
        </article>
        <form method="POST">
            <h2> PROFILE </h2>
            <?php if (isset($_POST['message'])) : ?>
            <h3><?php echo $_POST['message'];?></h3>
            <?php endif;?>

            <div><label>Name</label><input type="text" name="name" value="<?php echo $user['name'];?>" disabled></div>
            <div><label>Email</label><input type="email" name="email" value="<?php echo $user['email'];?>" disabled></div>
            <!--div><label>Address *</label><textarea name="address"><?php echo $user['address'];?></textarea></div-->
            <div><label>City *</label><input type="text" name="city" value="<?php echo $user['city'];?>"></div>
            <div><label>Province</label><input type="text" name="province" value="<?php echo $user['province'];?>"></div>
            <div><label>Country</label><input type="text" name="country" value="<?php echo $user['country'];?>"></div>
            <!--div><label>Zip Code</label><input type="text" name="zipcode" value="<?php echo $user['zipcode'];?>"></div-->
            <div><label>Phone *</label><input type="text" name="phone" value="<?php echo $user['phone'];?>"></div>
            <div><label>Occupation</label>
                <select name="company">
                    <option value="student" <?php if (isset($user['company']) and 'student' === $user['company']) {echo 'selected';}?> >Student</option>
                    <option value="journalist" <?php if (isset($user['company']) and 'journalist' === $user['company']) {echo 'selected';}?> >Journalist</option>
                    <option value="ngo" <?php if (isset($user['company']) and 'ngo' === $user['company']) {echo 'selected';}?> >NGO</option>
                    <option value="government" <?php if (isset($user['company']) and 'government' === $user['company']) {echo 'selected';}?> >Government</option>
                    <option value="parliament" <?php if (isset($user['company']) and 'parliament' === $user['company']) {echo 'selected';}?> >Parliament</option>
                    <option value="others" <?php if (isset($user['company']) and 'others' === $user['company']) {echo 'selected';}?> >Others</option>
                </select>
            </div>
            <div><button type="submit">UPDATE PROFILE</button></div>
        </form>
        <footer> &nbsp;</footer>
    </div>
</body>
</html>

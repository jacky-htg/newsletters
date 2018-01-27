<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Auth
{
    public static function authorization()
    {
        core::session()->start();

        if (core::session()->issetName('sess_admin') === false || core::session()->issetName('id') === false ) {
            core::session()->set('id', null);
            core::session()->set('sess_admin', null);
        }
        
        if (isset($_REQUEST['admin'])) {
            $login = trim(core::database()->escape($_POST['login']));
            $query = "SELECT * FROM " . core::database()->getTableName('aut') . " WHERE login='" . $login . "'";
            $result = core::database()->querySQL($query);
            $row = core::database()->getRow($result);

            if (core::session()->get('sess_admin') != "ok") $sess_pass = md5(trim($_POST['password']));
            if ($sess_pass === $row['password']) {
                core::session()->set('sess_admin', "ok");
                core::session()->set('id', $row['id']);
                core::session()->commit();
            } else {
                self::logOut();

                echo '<!DOCTYPE html>
				<html>
				<head>
				<title>' . core::getLanguage('title', 'error_authorization') . '</title>
				<meta http-equiv="content-type" content="text/html; charset=utf-8">
				</head>
				<body>
				<script type="text/javascript">
				window.alert(\'' . core::getLanguage('alert', 'not_authorized') . '\');
				window.location.href=\'' . $_SERVER['PHP_SELF'] . '\';
				</script>
				</body>
				</html>';
                exit();
            }
        } else {
            if (core::session()->get('sess_admin') != "ok") {

				// require temlate class
				core::requireEx('libs', "html_template/SeparateTemplate.php");

				$tpl = SeparateTemplate::instance()->loadSourceFromFile(core::getTemplate() . "authorization.tpl");
				$tpl->assign('TITLE', core::getLanguage('title', 'authorization'));
				$tpl->assign('STR_ADMIN_AREA', core::getLanguage('str', 'admin_area'));
				$tpl->assign('SCRIPT_NAME', core::getLanguage('str', 'script_name'));
				$tpl->assign('STR_SIGN_IN', core::getLanguage('str', 'sign_in'));
                $tpl->assign('STR_LOGIN', core::getLanguage('str', 'login'));
				$tpl->assign('STR_PASSWORD', core::getLanguage('str', 'password'));
				
				// display content
				$tpl->display();
                core::session()->commit();
                exit();
            }
        }
    }

    public static function getCurrentHash($id)
    {
        if (is_numeric($id)) {
            $query = "SELECT * FROM " . core::database()->getTableName('aut') . " WHERE id=" . $id;
            $result = core::database()->querySQL($query);
            $row = core::database()->getRow($result);

            return $row['password'];
        }
    }

    public static function logOut()
    {
        core::session()->start();
        core::session()->destroy();
    }

    public static function getAutInfo($id)
    {
        if (is_numeric($id)){
            $query = "SELECT * FROM " . core::database()->getTableName('aut') . " WHERE id=" . $id;
            $result = core::database()->querySQL($query);
            return core::database()->getRow($result);
        }
    }

    public static function getAutId()
    {
        core::session()->start();
        $id = core::session()->get('id');
        core::session()->commit();
        return $id;
    }
}
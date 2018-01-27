<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Model_subform extends Model
{

    /**
     * @return mixed
     */
    public function getCategoryList()
    {
        $query = "SELECT * FROM " . core::database()->getTableName('category') . " ORDER BY name";
        $result = core::database()->querySQL($query);
        return core::database()->getColumnArray($result);
    }

    /**
     * @param $fields
     * @return mixed
     */
    public function makeSubscribe($fields)
    {
        return core::database()->insert($fields, core::database()->getTableName('users'));
    }

    /**
     * @param $email
     * @return mixed
     */
    public function checkExistEmail($email)
    {
        $email = core::database()->escape($email);
        $query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE email LIKE '" . $email . "'";
        $result = core::database()->querySQL($query);
        return core::database()->getRecordCount($result);
    }

    /**
     * @param $id_user
     * @param $id_cat
     */
    public function insertSubs($id_user, $id_cat)
    {
        if (is_numeric($id_user)) {
            foreach ($id_cat as $id) {
                if (is_numeric($id)) {
                    $fields = array();
                    $fields['id_sub'] = 0;
                    $fields['id_user'] = $id_user;
                    $fields['id_cat'] = $id;
                    $result = core::database()->insert($fields, core::database()->getTableName('subscription'));
                }
            }
        }
    }

    /**
     * @param $id_user
     * @return bool
     * @throws Exception
     * @throws phpmailerException
     */
    public function sendNotification($id_user)
    {
        if (is_numeric($id_user)) {
            $user = $this->getUserInfo($id_user);

            core::requireEx('libs', "PHPMailer/class.phpmailer.php");

            $query = "SELECT * FROM " . core::database()->getTableName('charset') . " WHERE id_charset = " . core::getSetting('id_charset');
            $result = core::database()->querySQL($query);
            $char = core::database()->getRow($result);
            $charset = $char['charset'];

            $UNSUB = "http://" . $_SERVER["SERVER_NAME"] . Pnl::root() . "?t=unsubscribe&id=" . $id_user . "&token=" . $user['token'];
            $CONFIRM = "http://" . $_SERVER["SERVER_NAME"] . Pnl::root() . "?t=subscribe&id=" . $id_user . "&token=" . $user['token'];

            $textconfirmation = core::getSetting('textconfirmation');
            $subjecttextconfirm = core::getSetting('subjecttextconfirm');
            $organization = core::getSetting('organization');

            $textconfirmation = str_replace('%NAME%', $user['name'], $textconfirmation);
            $textconfirmation = str_replace('%CONFIRM%', $CONFIRM, $textconfirmation);
            $textconfirmation = str_replace('%UNSUB%', $UNSUB, $textconfirmation);
            $textconfirmation = str_replace('%SERVER_NAME%', $_SERVER['SERVER_NAME'], $textconfirmation);

            $email_name = core::getSetting('email_name') == '' ? $_SERVER["SERVER_NAME"] : core::getSetting('email_name');

            $m = new PHPMailer();

            if (core::getSetting('how_to_send') == 2) {
                $m->IsSMTP();
                $m->SMTPAuth = true;
                $m->SMTPKeepAlive = true;
                $m->Host = core::getSetting('smtp_host');
                $m->Port = core::getSetting('smtp_port');
                $m->Username = core::getSetting('smtp_username');
                $m->Password = core::getSetting('smtp_password');

                if (core::getSetting('smtp_secure') == 'ssl')
                    $m->SMTPSecure = 'ssl';
                else
                    if (core::getSetting('smtp_secure') == 'tls') $m->SMTPSecure = 'tls';

                if (core::getSetting('smtp_aut') == 'plain')
                    $m->AuthType = 'PLAIN';
                else
                    if (core::getSetting('smtp_aut') == 'cram-md5') $m->AuthType = 'CRAM-MD5';

                $m->Timeout = core::getSetting('smtp_timeout');
            } else
                if (core::getSetting('how_to_send') == 3 && core::getSetting('sendmail') != '') {
                    $m->IsSendmail();
                    $m->Sendmail = core::getSetting('sendmail');
                } else {
                    $m->IsMail();
                }

            $m->CharSet = $charset;

            if ($charset != 'utf-8') {
                $textconfirmation = iconv('utf-8', $charset, $textconfirmation);
                $subjecttextconfirm = iconv('utf-8', $charset, $subjecttextconfirm);
                if (!empty($organization)) $organization = iconv('utf-8', $charset, $organization);
                $email_name = iconv('utf-8', $charset, $email_name);
            }

            $m->Subject = $subjecttextconfirm;

            if (core::getSetting('show_email') == "no")
                $m->From = "noreply@" . $_SERVER['SERVER_NAME'] . "";
            else
                $m->From = core::getSetting('email');

            $m->FromName = $email_name;
            $m->AddAddress($user['email']);

            if (!empty($organization)) $m->addCustomHeader("Organization: " . $organization . "");

            $m->isHTML(false);
            $m->Body = $textconfirmation;
            $m->Send();
            $m->ClearCustomHeaders();
            $m->ClearAllRecipients();

            if (core::getSetting('newsubscribernotify') == 'yes') {
                if ($charset != 'utf-8')
                    $subject = iconv('utf-8', $charset, core::getLanguage('subject', 'notification_newuser'));
                else
                    $subject = core::getLanguage('subject', 'notification_newuser');

                $msg = "" . core::getLanguage('msg', 'notification_newuser') . "\nName: " . $user['name'] . " \nE-mail: " . $user['email'] . "\n";
                $msg = str_replace('%SITE%', $_SERVER['SERVER_NAME'], $msg);

                if ($charset != 'utf-8')  $msg = iconv('utf-8', $charset, $msg);

                $m->From = core::getSetting('email');
                $m->AddAddress(core::getSetting('email'));
                $m->Subject = $subject;
                $m->Body = $msg;
                $m->Send();
            }

            if (core::getSetting('how_to_send') == 2) $m->SmtpClose();

            return TRUE;
        } else
            return FALSE;
    }

    /**
     * @param $id_user
     * @return mixed
     */
    public function getUserInfo($id_user)
    {
        if (is_numeric($id_user)){
            $query = "SELECT * FROM " . core::database()->getTableName('users') . " WHERE id_user=" . $id_user;
            $result = core::database()->querySQL($query);
            return core::database()->getRow($result);
        }
    }
}
<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class core
{
    protected static $_init = NULL;
    protected static $paths = array();
    protected static $mainConfig = NULL;
    protected static $language = NULL;
    protected static $key = 'Rii73dg=4&8#!@9';
    protected static $licensekey_url = 'http://license.rijalasepnugroho.com/';
    protected static $license_path = 'sys/license_key';
    public static $db = NULL;
    public static $tpl = NULL;
    public static $path = NULL;
    public static $session = NULL;
    protected static $licensekey = NULL;
    public static $system_error = NULL;

    /**
     * Check if self::init() has been called
     *
     * @return boolean
     */

    static public function isInit()
    {
        return self::$_init;
    }

    /**
     * Initialization
     *
     * @return boolean
     */

    static public function init($paths)
    {
        if (self::isInit())
            return TRUE;
        self::$paths = $paths;
        self::$path = str_replace("//", "/", "/" . trim(str_replace(chr(92), "/", substr(SYS_ROOT, strlen($_SERVER["DOCUMENT_ROOT"]))), "/") . "/");
        self::_loadEngines();
        self::$_init = TRUE;
    }

    /**
     * Create class $className
     *
     * @param string $className
     *            class name
     * @return mixed
     */

    static public function factory($className)
    {
        return new $className();
    }

    static public function database()
    {
        return self::$db;
    }

    static public function session()
    {
        return self::$session;
    }

    /**
     * AUTOLOAD modules
     */

    static protected function _loadEngines()
    {
        require_once 'folders.php';
        $folders = array(
            self::$paths['engine']
        );
        $autoload = array_reverse(folders::scan($folders, 'php', TRUE));
        foreach ($autoload as $lib) {
            if (is_file($lib))
                require_once $lib;
        }

        self::$licensekey = self::getLicensekey();

        if (self::checkLicense() == false && $_SERVER['REMOTE_ADDR'] != '127.0.0.1'){
            header('Location: ./?t=expired');
            exit();
        }
    }

    static public function getLicensekey()
    {
        global $ConfigDB;

        $db = new DB($ConfigDB);
        $query = "SELECT * FROM " . $db->getTableName('licensekey') . "";
        $result = $db->querySQL($query);
        $row = $db->getRow($result);

        return $row['licensekey'];
    }

    static public function getTemplate()
    {
        return self::$tpl;
    }

    static public function setTemplate($tpl)
    {
        self::$tpl = SYS_ROOT . self::$paths['templates'] . DIRECTORY_SEPARATOR . $tpl;
    }

    // --------- SETTINGS -------------------------------
    static public function addSetting($set = array())
    {
        self::$mainConfig = (is_array(self::$mainConfig)) ? array_merge(self::$mainConfig, $set) : $set;
    }

    static public function setSetting($index, $value)
    {
        self::$mainConfig[$index] = $value;
    }

    static public function getSetting($name = '')
    {
        // Main config
        return ($name == '') ? self::$mainConfig : self::$mainConfig[$name];
    }
    // --------- SETTINGS -------------------------------

    // --------- language -------------------------------
    static public function addLanguage($lng = array())
    {
        self::$language = $lng;
    }

    static public function getLanguage($section, $item)
    {
        return (isset(self::$language[$section][$item])) ? self::$language[$section][$item] : '';
    }

    static public function setLanguage($section, $item, $value)
    {
        self::$language[$section][$item] = $value;
    }

    static public function pathTo($engine, $data)
    {
        return SYS_ROOT . self::$paths[$engine] . DIRECTORY_SEPARATOR . $data;
    }

    static public function requireEx($engine, $name)
    {
        $file = SYS_ROOT . self::$paths[$engine] . DIRECTORY_SEPARATOR . $name;
        if (file_exists($file)) {
            require_once $file;
            return true;
        } else
            return false;
    }

    static public function includeEx($engine, $name)
    {
        $file = SYS_ROOT . self::$paths[$engine] . DIRECTORY_SEPARATOR . $name;
        if (file_exists($file)) {
            include_once $file;
            return true;
        } else
            return false;
    }

    static public function getPath($path)
    {
        return self::$paths[$path];
    }

    static public function encodeStr($text = null)
    {
        $td = mcrypt_module_open("tripledes", '', 'cfb', '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        if (mcrypt_generic_init($td, self::$key, $iv) != -1) {
            $enc_text = base64_encode(mcrypt_generic($td, $iv . $text));
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
            return $enc_text;
        }
    }

    static public function strToHex($string)
    {
        $hex = '';
        for ($i = 0; $i < strlen($string); $i++) {
            $hex .= dechex(ord($string[$i]));
        }

        return $hex;
    }

    static public function decodeStr($text)
    {
        $td = mcrypt_module_open("tripledes", '', 'cfb', '');
        $iv_size = mcrypt_enc_get_iv_size($td);
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        if (mcrypt_generic_init($td, self::$key, $iv) != -1) {
            $decode_text = substr(mdecrypt_generic($td, base64_decode($text)), $iv_size);
            mcrypt_generic_deinit($td);
            mcrypt_module_close($td);
            return $decode_text;
        }
    }

    static public function hexToStr($hex)
    {
        $string = '';
        for ($i = 0; $i < strlen($hex) - 1; $i += 2) {
            $string .= chr(hexdec($hex[$i] . $hex[$i + 1]));
        }
        return $string;
    }

    static public function checkLicense()
    { 
        return true;
    }

    static public function updateLicensekey($licensekey)
    {
        $lisense_info = self::getLicenseInfo(SYS_ROOT . self::$license_path);

        if (file_exists(SYS_ROOT . self::$license_path) || $lisense_info['licensekey'] != $licensekey) {
           self::makeLicensekey();
        }
    }

    static public function makeLicensekey()
    {
        $domain = (substr($_SERVER["SERVER_NAME"], 0, 4)) == "www." ? str_replace('www.','', $_SERVER["SERVER_NAME"]) : $_SERVER["SERVER_NAME"];
        $lisense_info = json_decode(self::file_get_contents_curl(self::$licensekey_url . '?t=licensekey&licensekey=' . self::$licensekey . '&domain=' . $domain . ''), true);

        if (!isset($lisense_info['error'])) {
            $arr = array();
            $arr['domain'] = (substr($_SERVER["SERVER_NAME"], 0, 4)) == "www." ? str_replace('www.','', $_SERVER["SERVER_NAME"]) : $_SERVER["SERVER_NAME"];
            $arr['license_type'] = $lisense_info['license_type'];
            $arr['licensekey'] = self::$licensekey;
            $arr['created'] = $lisense_info['date_created'];
            $arr['date_from'] = $lisense_info['date_active_from'];
            $arr['date_to'] = $lisense_info['date_active_to'];

            $encodeStr = self::encodeStr(json_encode($arr));

            $f = fopen(SYS_ROOT . self::$license_path, "w");

            if (fwrite($f, $encodeStr) === false) {
                self::$system_error = 'CANNT_CREATE_LICENSEKEY_FILE';
            }

            fclose($f);
        } else {
            self::$system_error = 'ERROR_CHECK_LICENSEKEY';
        }
    }

    static public function getLicenseInfo()
    {
        if (file_exists(SYS_ROOT . self::$license_path)) {
            $handle = fopen(SYS_ROOT . self::$license_path, "r");
            $contents = fread($handle, filesize(SYS_ROOT . self::$license_path));
            fclose($handle);

            return json_decode(self::decodeStr($contents), true);
        } else {
            self::makeLicensekey();
        }
    }

    static public function file_get_contents_curl($url)
    {
        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        $data = curl_exec($ch);

        curl_close($ch);

        preg_match('/\{([^\}])+\}/U',$data, $out);
        return $out[0];
    }

    static public function expired_day_count()
    {
        $lisense_info = self::getLicenseInfo(SYS_ROOT . self::$license_path);

        if ($lisense_info['license_type'] == 'demo') {
            return round((strtotime($lisense_info['date_to']) - strtotime(date("Y-m-d H:i:s"))) / 3600 / 24);
        }
    }
}

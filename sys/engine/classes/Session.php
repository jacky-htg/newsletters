<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Session {
    private $lifetime;
    public $cookieName = "cid";
    private $started = false;

    public function __construct($lifetime = 1200000)
    {
        $this->lifetime = $lifetime;
    }

    public function isCreated () {
        return (!empty($_COOKIE[$this->cookieName]) && ctype_alnum($_COOKIE[$this->cookieName])) ? true : false;
    }

    public function setCookieName ($name = null)
    {
        if (!empty($name)) $this->cookieName = $name;
    }

    public function start () {
        if (!$this->started) {
            if (!empty($_COOKIE[$this->cookieName]) && !ctype_alnum($_COOKIE[$this->cookieName])) {
                unset($_COOKIE[$this->cookieName]);
            }
            session_set_cookie_params ($this->lifetime, '/');
            session_name ($this->cookieName);
            session_start ();
            $this->started = true;
        }
    }

    public function set ($name, $value) {
        if ($this->started) {
            $_SESSION[$name] = $value;
        } else {
            trigger_error('You should start Session first', E_USER_WARNING);
        }
    }

    public function issetName ($name) {
        if ($this->started) {
            if( isset($_SESSION[$name]))
                return true;
            else
                return false;
        } else {
            trigger_error('You should start Session first', E_USER_WARNING);
        }
    }

    public function get ($name) {
        if ($this->started) {
            return isset($_SESSION[$name]) ? $_SESSION[$name] : null;
        } else {
            trigger_error('You should start Session first', E_USER_WARNING);
        }
    }

    public function delete ($name) {
        if ($this->started) {
            if (isset($_SESSION[$name])) unset($_SESSION[$name]);
        } else {
            trigger_error('You should start Session first', E_USER_WARNING);
        }
    }

    public function clear () {
        if ($this->started) {
            unset($_SESSION);
        } else {
            trigger_error('You should start Session first', E_USER_WARNING);
        }
    }

    public function destroy () {
        if ($this->started) {
            $this->started = false;
            unset($_COOKIE[$this->cookieName]);
            setcookie($this->cookieName, '', 1, '/');
            session_destroy();
        } else {
            trigger_error('Session is not started!', E_USER_WARNING);
        }
    }

    public function restart () {
        $this->destroy();
        $this->start();
    }

    public function getArray () {
        if ($this->started) {
            return $_SESSION;
        } else {
            trigger_error('You should start Session first', E_USER_WARNING);
        }
    }

    public function commit () {
        if ($this->started) {
            session_write_close();
            $this->started = false;
        } else {
            trigger_error('You should start Session first', E_USER_WARNING);
        }
    }
}
<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_page403 extends Controller
{
    function action_index()
    {
        $this->view->generate('page403_view.php');
    }
}
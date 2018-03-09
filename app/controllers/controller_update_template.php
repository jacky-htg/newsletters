<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_update_template extends Controller
{
    function __construct()
    {
        $this->model = new Model_update_template();
        $this->view = new View();
    }

    function action_index()
    {
        $this->view->generate('update_template_view.php', $this->model);
    }
}
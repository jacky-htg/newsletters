<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_edit_template extends Controller
{
    function __construct()
    {
        $this->model = new Model_edit_template();
        $this->view = new View();
    }

    function action_index()
    {
        $this->view->generate('edit_template_view.php', $this->model);
    }
}
<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_change_password extends Controller
{
	function __construct()
	{
		$this->model = new Model_change_password();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('change_password_view.php', $this->model);
	}
}
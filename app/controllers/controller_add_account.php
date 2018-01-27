<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_add_account extends Controller
{
	function __construct()
	{
		$this->model = new Model_add_account();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('add_account_view.php', $this->model);
	}
}
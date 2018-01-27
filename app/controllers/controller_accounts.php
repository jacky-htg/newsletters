<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_accounts extends Controller
{
	function __construct()
	{
		$this->model = new Model_accounts();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('accounts_view.php', $this->model);
	}
}
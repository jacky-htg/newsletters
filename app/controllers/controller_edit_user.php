<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_edit_user extends Controller
{
	function __construct()
	{
		$this->model = new Model_edit_user();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('edit_user_view.php', $this->model);
	}
}
<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_add_user extends Controller
{
	function __construct()
	{
		$this->model = new Model_add_user();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('add_user_view.php',$this->model);
	}
}
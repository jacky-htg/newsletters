<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_export extends Controller
{
	function __construct()
	{
		$this->model = new Model_export();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('export_view.php',$this->model);
	}
}
<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_log extends Controller
{
	function __construct()
	{
		$this->model = new Model_log();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('log_view.php',$this->model);
	}
}
<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_ajax extends Controller
{
	function __construct()
	{
		$this->model = new Model_ajax();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('ajax_view.php',$this->model);
	}
}
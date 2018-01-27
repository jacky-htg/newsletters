<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_export_feedback extends Controller
{
	function __construct()
	{
		$this->model = new Model_export_feedback();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('export_feedback_view.php',$this->model);
	}
}
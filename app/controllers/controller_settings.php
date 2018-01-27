<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_settings extends Controller
{
	function __construct()
	{
		$this->model = new Model_settings();
		$this->view = new View();
	}

	public function action_index()
	{
		$this->view->generate('settings_view.php',$this->model);
	}
}
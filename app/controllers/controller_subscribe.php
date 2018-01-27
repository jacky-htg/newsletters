<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_subscribe extends Controller
{
	function __construct()
	{
		$this->model = new Model_subscribe();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('subscribe_view.php',$this->model);
	}
}
<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_category extends Controller
{
	function __construct()
	{
		$this->model = new Model_category();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('category_view.php',$this->model);
	}
}
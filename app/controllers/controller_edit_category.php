<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_edit_category extends Controller
{
	function __construct()
	{
		$this->model = new Model_edit_category();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('edit_category_view.php',$this->model);
	}
}
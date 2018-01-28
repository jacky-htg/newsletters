<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_export_links extends Controller
{
	function __construct()
	{
		$this->model = new Model_export_links();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('export_links_view.php',$this->model);
	}
}
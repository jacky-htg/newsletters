<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_import extends Controller
{
	function __construct()
	{
		$this->model = new Model_import();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('import_view.php', $this->model);
	}
}
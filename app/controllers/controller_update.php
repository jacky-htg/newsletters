<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_update extends Controller
{
	function __construct()
	{
		$this->model = new Model_update();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('update_view.php', $this->model);
	}
}
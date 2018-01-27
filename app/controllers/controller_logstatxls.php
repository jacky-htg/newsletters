<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_logstatxls extends Controller
{
	function __construct()
	{
		$this->model = new Model_logstatxls();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('logstatxls_view.php', $this->model);
	}
}
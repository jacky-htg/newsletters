<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_create_template extends Controller
{	
	function __construct()
	{
		$this->model = new Model_create_template();
		$this->view = new View();
	}

	public function action_index()
	{
		$this->view->generate('create_template_view.php', $this->model);
	}
}
<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_subform extends Controller
{
	function __construct()
	{
		$this->model = new Model_subform();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('subform_view.php', $this->model);
	}
}
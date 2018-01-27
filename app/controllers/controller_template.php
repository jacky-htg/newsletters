<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_template extends Controller
{
	function __construct()
	{
		$this->model = new Model_template();
		$this->view = new View();
	}

	public function action_index()
	{	
		$this->view->generate('template_view.php',$this->model);
	}
}
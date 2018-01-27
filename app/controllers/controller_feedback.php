<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_feedback extends Controller
{
	function __construct()
	{
		$this->model = new Model_feedback();
		$this->view = new View();
	}
	
	public function action_index()
	{
		$this->view->generate('feedback_view.php', $this->model);
	}
}

<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_subscribers extends Controller
{
	function __construct()
	{
		$this->model = new Model_subscribers();
		$this->view = new View();
	}
	
	public function action_index()
	{
		$this->view->generate('subscribers_view.php', $this->model);
	}
}
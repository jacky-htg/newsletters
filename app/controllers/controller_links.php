<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_links extends Controller
{
	function __construct()
	{
		$this->model = new Model_links();
		$this->view = new View();
	}
	
	public function action_index()
	{
		$this->view->generate('links_view.php', $this->model);
	}
}

<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_page404 extends Controller
{
	function action_index()
	{
		$this->view->generate('page404_view.php');
	}
}
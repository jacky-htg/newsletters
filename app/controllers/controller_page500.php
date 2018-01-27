<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Controller_page500 extends Controller
{
	function action_index()
	{
		$this->view->generate('page500_view.php');
	}
}
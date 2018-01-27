<?php

defined('LETTER') || exit('NewsLetter: access denied.');


class Controller_faq extends Controller
{
	function __construct()
	{
		$this->model = new Model_faq();
		$this->view = new View();
	}

	function action_index()
	{
		$this->view->generate('faq_view.php',$this->model);
	}
}
<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Exception404 extends Exception
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}
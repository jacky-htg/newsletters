<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Model_faq extends Model
{
	/**
	 * @return string
	 */
	public function get_faq()
	{
		$filename = core::pathTo('templates', "language/faq_".core::getSetting("language") );
		return file_get_contents($filename);
	}
}
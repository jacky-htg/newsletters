<?php

defined('LETTER') || exit('NewsLetter: access denied.');

class Core_Type_Conversion
{
	/**
	 * Convert $var to string value
	 * @param mixed $var
	 * @return string
	 */
	static public function toStr(&$var)
	{
		if (is_array($var) || is_object($var))
		{
			return '';
		}

		return strval($var);
	}

	/**
	 * Convert $var to integer value
	 * @param mixed $var
	 * @return int
	 */
	static public function toInt(&$var)
	{
		if (is_int($var))
		{
			return $var;
		}
		
		$return = intval($var);
		if (strval($return) != $var)
		{
			return 0;
		}

		return $return;
	}

	/**
	 * Convert $var to float value
	 * @param mixed $var
	 * @return float
	 */
	static public function toFloat(&$var)
	{
		return floatval($var);
	}

	/**
	 * Convert $var to float value
	 * @param mixed $var
	 * @return bool
	 */
	static public function toBool(&$var)
	{
		if (is_bool($var))
		{
			return $var;
		}
		return $var == 1;
	}

	/**
	 * Convert $var to array
	 * @param mixed $var
	 * @return array
	 */
	static public function toArray(&$var)
	{
		return is_array($var) ? $var : array();
	}
}
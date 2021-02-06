<?php
/**
 * @author ahmet
 * @Date 30.01.2021 21:47
 *
 */
if (!function_exists('createToken')) {
	
	/**
	 * @param int $length
	 * @return false|string
	 */
	function createToken(int $length = 64)
	{
		return substr(md5(uniqid()).md5(uniqid('', true)),0, $length);
	}
	
}
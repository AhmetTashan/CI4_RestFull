<?php

if (!function_exists('getUserId')) {


	function getUserId()
	{
		$request = service('request');

		$token = $request->getHeaderLine('Authorization');
		$token = explode('Bearer ', $token)[1];

		$tokenModel = new \App\Models\Authentication\TokenModel();
		return $tokenModel->user_id($token);
	}

}
<?php

Class Response
{
	public static function wrapResult($data)
	{
		$result = array(
			"code" => 200,
			"data" => $data
		);

		return json_encode($result);
	}
	public static function wrapFault($message)
	{
		$result = array(
			"code" => 500,
			"message" => $message
		);

		return json_encode($result);
	}
}
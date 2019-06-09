<?php

class MY_Loader extends CI_Loader
{
	public function __construct()
	{
		parent::__construct();
	}

	public function iface($interface_name)
	{
		require_once APPPATH . '/interfaces/' . $interface_name . '.php';
	}
}

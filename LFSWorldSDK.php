<?php
/* Name: LFSWorldSDK
* Author: Mark 'Dygear' Tomlin
* License: The MIT License
* Version: 2.0.0
*/
class LFSWorldSDK
{
	const HOST = 'http://www.lfsworld.net';
	const PATH = '/pubstat/get_stat2.php';

	protected $idk = NULL;
	protected $user = NULL;
	protected $pass = NULL;

	private $query = array();
	private $compression = 0;
	private $premium = FALSE;
	private $time = NULL;

	public function __construct($premium, $idkORuser, $pass = NULL)
	{
		$this->premium = $premium;

		if (($this->pass = $pass) === NULL)
			$this->idk = $idkORuser;
		else
			$this->user = $idkORuser;

		$this->compression = (function_exists('gzinflate')) ? 3 : 0;

		$this->time = time();

		$this->query = $this->getQueryURL();

		print_r($this);
	}

	private function getQueryURL($host = self::HOST, $path = self::PATH)
	{
		return $host . $path;
	}
}
?>
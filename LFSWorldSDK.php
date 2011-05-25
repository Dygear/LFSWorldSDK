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
	const QUERY = array('version' => 1.5);

	protected $idk = NULL;
	protected $user = NULL;
	protected $pass = NULL;

	private $query = array();
	private $compression = 0;
	private $premium = FALSE;
	private $time = time();

	public __construct($premium, $idkORuser, $pass = NULL)
	{
		$this->premium = $premium;

		if (($this->pass = $pass) === NULL)
			$this->idk = $idkORuser;
		else
			$this->user = $idkORuser;

		$this->compression = (function_exists('gzinflate')) ? 3 : 0;

		$this->time = time();

		return $this;
		
		print_r($this);
	}

	private function getQueryString($host = self::HOST, $path = self::PATH)
	{
		$url = $host . $path;
		$query = array_merge($this->QUERY, array('ps' => $this->premium, 'c' => $this->compression), $this->query);
		return http_build_url($url, $query, HTTP_URL_JOIN_PATH | HTTP_URL_JOIN_QUERY);
	}
}

$LFS = new LFSWorldSDK(TRUE, '35cP2S05Cvj3z7564aXKyw0Mqf1Hhx7P');
?>
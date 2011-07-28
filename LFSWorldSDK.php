<?php

$SDK = new LFSWorldSDK('35cP2S05Cvj3z7564aXKyw0Mqf1Hhx7P', TRUE);

/*
** Live For Speed World SDK, easily find what you need from the World of Live For Speed.
**
** @package   LFSWorldSDK
** @since     2009-10-14 06:40
** @author    Mark 'Dygear' Tomlin
** @coauthor  Mikael 'filur' Forsberg.
** @coauthor  Victor van Vlaardingen.
** @coauthor  Jeff 'glyphon' DeLamater.
** @coauthor  'AndroidXP'.
** @coauthor  Dr. Timo 'HorsePower' Bergmann.
** @coauthor  Becky Rose.
** @coauthor  'kanutron'.
** @license   MIT License (http://opensource.org/licenses/mit-license.php)
** @copyright Copyright (C) 2006 - 2009,
**            Mark 'Dygear' Tomlin, Mikael 'filur' Forsberg,
**            Victor van Vlaardingen, Jeff 'glyphon' DeLamater,
**            AndroidXP and Dr. Timo 'HorsePower' Bergmann.
** @version   1.9.5 Alpha 1
*/

# Extra Resources:
# ISO Names with Extended List: http://www.lfsforum.net/showthread.php?p=1268645#post1268645

class LFSWorldSDK {
	// Constructor
	function LFSWorldSDK($idk, $ps = FALSE) {
		$this->ps = $ps;		# Premium Stats
		$this->idk = $idk;		# IDK For LFS World Stats
		$this->time = time();	# Current Time.
		$this->fpass = TRUE;	# First Pass in make_query function.
		$this->compression = (function_exists('gzinflate')) ? 3 : 0;
	}
	// Core Functions.
	function fetch_data($url) {
		if ((($data = @file_get_contents($url)) === FALSE) && function_exists('curl_init')) {
			$cURL = curl_init();
			curl_setopt($cURL, CURLOPT_URL,$url);
			curl_setopt($cURL, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($cURL, CURLOPT_CONNECTTIMEOUT, 5);
			$data = curl_exec($cURL);
			curl_close($cURL);
		} else
			trigger_error('Your server\'s configuration is not supported by this version of LFSWorldSDK.', E_WARNING);
		return $data;
	}
	function make_query($qryStr) {
		if ($this->ps === FALSE && $this->fpass === TRUE && (time() - $this->time) < 5) {
			sleep(6 - (time() - $this->time));
			$this->time = time();
			$this->fpass = FALSE;
		}
		$data = $this->fetch_data("http://www.lfsworld.net/pubstat/get_stat2.php?version=1.4&idk={$this->idk}&ps={$this->ps}&c={$this->compression}&s=2{$qryStr}");
		if ($this->compression)
			$data = gzinflate($data);
		if ($this->is_lfsw_error($data))
			return $this->make_query($qryStr);
		if (($return = @unserialize($data)) === FALSE)
			return $data;
		else
			return $return;
	}
	function is_lfsw_error($data) {
		switch ($data) {
			case 'Identification is required - http://www.lfsforum.net/showthread.php?t=14480':
			case 'hl: no hotlaps found':
			case 'hl: no racer':
			case 'ch: invalid track':
			case 'ch: invalid car':
			case 'pst: no valid username':
			case 'pb: racer has no pbs':
			case 'no output':
			case 'can\'t reload this page that quickly after another':
			case 'Invalid Ident-Key':
			case 'not authed (invalid identkey)':
			case 'not authed (ip)':
			case 'Invalid login details provided':
			case 'No authentication method provided':
			case 'You shouldn\'t flood me! You\'re locked out for 15 minutes now. DO NOT make your program do its requests as fast as it can, until the tarpit is over...':
			case 'hl_log is only available since version 1.2 and later':
				return TRUE;
			default:
				return FALSE;
		}
	}
	// Helper Fuctions.
	function convert_lfsw_time($time) {
		return sprintf('%d:%06.3f', floor($time / 60000), (($time % 60000) / 1000));
	}
	function convert_lfs_text($str, $mkHref = FALSE, $codePage = 'L', $toCodePage = 'UTF-8') {
		$colors = array('^0','^1','^2','^3','^4','^5','^6','^7','^8','^9');
		# Parse Colors
		$clrHTML = array (
			'<span style="color: #000;">',	# ^0
			'<span style="color: #F00;">',	# ^1
			'<span style="color: #0F0;">',	# ^2
			'<span style="color: #FF0;">',	# ^3
			'<span style="color: #00F;">',	# ^4
			'<span style="color: #F0F;">',	# ^5
			'<span style="color: #0FF;">',	# ^6
			'<span style="color: #FFF;">',	# ^7
			'<span style="color: INHERIT;">',	#^8
			'<span style="color: INHERIT;">'	# ^9
		);
		$str = str_replace($colors, $clrHTML, $str, $count);
		if ($count) {
			$str = str_replace('<span ', '</span><span ', $str);
			$str = substr($str, 0, strpos($str, '</span>')) . substr($str, strpos($str, '</span>') + 7) . '</span>';
		}
		# Parse Code Pages
		$sets = array (
			'L' => 'CP1252',		# Latin 1
			'E' => 'ISO-8859-2',	# Central Europe
			'T' => 'ISO-8859-9',	# Turkish
			'B' => 'ISO-8859-13',	# Baltic
			'J' => 'SJIS-win',		# Japanese
			'G' => 'ISO-8859-7',	# Greek
			'C' => 'CP1251',		# Cyrillic
			'H' => 'CP950',		# Traditional Chinese
			'S' => 'CP936',		# Simplified Chinese
			'K' => 'CP949'			# Korean
		);
		$newstr = $tmp = '';
		for ($i = 0, $len = strlen($str); $i < $len; $i++) {
			if ($str{$i} == '^' && isset ($sets[$str{$i+1}]) && $str{$i-1} != '^') {
				if ($tmp != '') {
					$newstr .= mb_convert_encoding($tmp, $toCodePage, $sets[$codePage]);
					$tmp = '';
				}
				$codePage = $str{++$i};
			} else if (ord($str{$i}) > 31)
				$tmp .= $str{$i};
		}
		if ($tmp != '')
			$newstr .= mb_convert_encoding($tmp, $toCodePage, $sets[$codePage]);
		return str_replace('^^', '^', $newstr);
	}
	function convert_track_name($trackCode, $short = FALSE) {
		if ($short) {
			switch($trackCode[0]) {
				case 0: $rtn = 'BL'; break;
				case 1: $rtn = 'SO'; break;
				case 2: $rtn = 'FE'; break;
				case 3: $rtn = 'AU'; break;
				case 4: $rtn = 'KY'; break;
				case 5: $rtn = 'WE'; break;
				case 6: $rtn = 'AS'; break;
				default: $rtn= '??';
			}
			if ($trackCode[2] == 1)
				return $rtn . ($trackCode[1] + 1) . 'R';
			else
				return $rtn . ($trackCode[1] + 1);
		} else {
			switch($trackCode[0]) {
				case 0: $rtn = 'Blackwood ';
					switch($trackCode[1]) {
						case 0: $rtn .= 'GP Track'; break;
						case 1: $rtn .= 'Rally Cross'; break;
						case 2: $rtn .= 'Car Park'; break;
					}
				break;
				case 1: $rtn = 'South City ';
					switch($trackCode[1]) {
						case 0: $rtn .= 'Classic'; break;
						case 1: $rtn .= 'Sprint Track 1'; break;
						case 2: $rtn .= 'Sprint Track 2'; break;
						case 3: $rtn .= 'Long'; break;
						case 4: $rtn .= 'Town Course'; break;
						case 5: $rtn .= 'Chicane Route'; break;
					}
				break;
				case 2: $rtn = 'Fern Bay ';
					switch($trackCode[1]) {
						case 0: $rtn .= 'Club'; break;
						case 1: $rtn .= 'Green Track'; break;
						case 2: $rtn .= 'Gold Track'; break;
						case 3: $rtn .= 'Black Track'; break;
						case 4: $rtn .= 'Rally Cross'; break;
						case 5: $rtn .= 'RallyX Green'; break;
					}
				break;
				case 3: $rtn = 'Autocross ';
					switch($trackCode[1]) {
						case 0: $rtn .= 'Autocross'; break;
						case 1: $rtn .= 'Skid Pad'; break;
						case 2: $rtn .= 'Drag Strip'; break;
						case 3: $rtn .= '8 Lane Drag'; break;
					}
				break;
				case 4: $rtn = 'Kyoto Ring ';
					switch($trackCode[1]) {
						case 0: $rtn .= 'Oval'; break;
						case 1: $rtn .= 'National'; break;
						case 2: $rtn .= 'GP Long'; break;
					}
				break;
				case 5: $rtn = 'Westhill ';
					switch($trackCode[1]) {
						case 0: $rtn .= 'International'; break;
					}
				break;
				case 6: $rtn = 'Aston ';
					switch($trackCode[1]) {
						case 0: $rtn .= 'Cadet'; break;
						case 1: $rtn .= 'Club'; break;
						case 2: $rtn .= 'National'; break;
						case 3: $rtn .= 'Historic'; break;
						case 4: $rtn .= 'Grand Prix'; break;
						case 5: $rtn .= 'Grand Touring'; break;
						case 6: $rtn .= 'North'; break;
					}
				break;
				default: return 'Unknown Track';
			}
			if ($trackCode[2] == 1)
				return "$rtn Reversed";
			else
				return $rtn;
		}
	}
	function convert_flags_hlaps($flags_hlaps, $toString = FALSE) {
		if ($toString == FALSE) {
			$data = array();
			$data[1] = ($flags_hlaps & 1)  ? 'LEFTHANDDRIVE' : 'RIGHTHANDDRIVE';
			if ($flags_hlaps & 2)		$data[2] = 'GEARCHANGECUT';
			if ($flags_hlaps & 4)		$data[4] = 'GEARCHANGEBLIP';
			if ($flags_hlaps & 8)		$data[8] = 'AUTOGEAR';
			if ($flags_hlaps & 16)		$data[16] = 'SHIFTER'; 
			if ($flags_hlaps & 64)		$data[64] = 'BRAKEHELP';
			if ($flags_hlaps & 128)		$data[128] = 'THROTTLEHELP';
			if ($flags_hlaps & 512)		$data[512] = 'AUTOCLUTCH'; 
			if ($flags_hlaps & 1024)		$data[1024] = 'MOUSESTEER';
			if ($flags_hlaps & 2048)		$data[2048] = 'KN';
			if ($flags_hlaps & 4096)		$data[4096] = 'KS';
			if (!($flags_hlaps & 7168))	$data[7168] = 'WHEEL';
		} else {
			$data = '';
			$data .= ($flags_hlaps & 1)  ? 'L&nbsp;' : 'R&nbsp;';
			if ($flags_hlaps & 2)		$data .= 'cc&nbsp;';
			if ($flags_hlaps & 4)		$data .= 'cb&nbsp;';
			if ($flags_hlaps & 8)		$data .= 'A&nbsp;';
			if ($flags_hlaps & 16)		$data .= 'S&nbsp;'; 
			if ($flags_hlaps & 64)		$data .= 'bh&nbsp;';
			if ($flags_hlaps & 128)		$data .= 'cl&nbsp;';
			if ($flags_hlaps & 512)		$data .= 'ac&nbsp;'; 
			if ($flags_hlaps & 1024)		$data .= 'M&nbsp;';
			if ($flags_hlaps & 2048)		$data .= 'Kn&nbsp;';
			if ($flags_hlaps & 4096)		$data .= 'Ks&nbsp;';
			if (!($flags_hlaps & 7168))	$data .= 'W&nbsp;';
		}
		return $data;
	}
	function convert_team_bits($bits) {
		$data = array();
		if ($bits & 1)		$data[1]		= 'race';
		if ($bits & 2)		$data[2]		= 'drift';
		if ($bits & 4)		$data[4]		= 'drag';
		if ($bits & 8)		$data[8]		= 'can apply';
		if ($bits & 16)	$data[16]		= 'has host';
		if ($bits & 32)	$data[32]		= 'Demo';
		if ($bits & 64)	$data[64]		= 'S1';
		if ($bits & 128)	$data[128]	= 'S2';
		if ($bits & 256)	$data[256]	= 'S3';
		return $data;
	}
	function convert_car_bits($bits) {
		$data = array();
		if ($bits & 1)		$data[1]		= 'XFG';
		if ($bits & 2)		$data[2]		= 'XRG';
		if ($bits & 4)		$data[4]		= 'XRT';
		if ($bits & 8)		$data[8]		= 'RB4';
		if ($bits & 16)	$data[16]		= 'FXO';
		if ($bits & 32)	$data[32]		= 'LX4';
		if ($bits & 64)	$data[64]		= 'LX6';
		if ($bits & 128)	$data[128]	= 'MRT';
		if ($bits & 256)	$data[256]	= 'UF1';
		if ($bits & 512)	$data[512]	= 'RAC';
		if ($bits & 1024)	$data[1024]	= 'FZ5';
		if ($bits & 2048)	$data[2048]	= 'FOX';
		if ($bits & 4096)	$data[4096]	= 'XFR';
		if ($bits & 8192)	$data[8192]	= 'UFR';
		if ($bits & 16384)	$data[16384]	= 'FO8';
		if ($bits & 32768)	$data[32768]	= 'FXR';
		if ($bits & 65536)	$data[65536]	= 'XRR';
		if ($bits & 131072)	$data[131072]	= 'FZR';
		if ($bits & 262144)	$data[262144]	= 'BF1';
		if ($bits & 524288)	$data[524288]	= 'FBM';
		if ($bits & 1048576)$data[1048576]	= 'VWS';
		return $data;
	}
	function convert_rule_bits($bits) {
		$data = array();
		if ($bits & 1)		$data[1]		= 'CAN_VOTE';
		if ($bits & 2)		$data[2]		= 'CAN_SELECT';
		if ($bits & 4)		$data[4]		= 'QUALIFY';
		if ($bits & 8)		$data[8]		= 'PRIVATE';
		if ($bits & 16)	$data[16]		= 'MODIFIED';
		if ($bits & 32)	$data[32]		= 'MIDRACEJOIN';
		if ($bits & 64)	$data[64]		= 'MUSTPIT';
		if ($bits & 128)	$data[128]	= 'CAN_RESET';
		if ($bits & 256)	$data[256]	= 'FCV';
		return $data;
	}
	// LFSWorld Functions
	function get_hl($racer) {
		if (is_array($racer)) {
			foreach($racer as $uname)
				$result[$uname] = $this->get_hl($uname);
		} else {
			if (($result = $this->make_query('&action=hl&racer='.urlencode($racer))) !== FALSE) {
				if (is_array($result)) {
					foreach ($result as $i => $data)
						$result[$i]['flags_hlaps'] = $this->convert_flags_hlaps($data);
				}
			}
		}
		return $result;
	}
	function get_ch($track, $car, $control = null) {
		if (is_array($track) || is_array($car)) {
			if (is_array($track) && is_array($car)) {
				foreach($track as $tname) {
					foreach ($car as $cname)
						$result[$tname][$cname] = $this->get_ch($tname, $cname, $control);
				}
			} else if (is_array($track)) {
				foreach($track as $tname)
					$result[$tname] = $this->get_ch($tname, $cname, $control);
			} else if (is_array($car)) {
				foreach ($car as $cname)
					$result[$cname] = $this->get_ch($tname, $cname, $control);
			}
		} else {
			if (($result = $this->make_query("&action=ch&track={$track}&car={$car}&control={$control}")) !== FALSE) {
				foreach ($result as $i => $data)
					$result[$i]['flags_hlaps'] = $this->convert_flags_hlaps($data);
			}
		}
		return $result;
	}
	function get_wr() {
		if (($result = $this->make_query("&action=wr")) !== FALSE) {
			foreach ($result as $i => $data)
				$result[$i]['flags_hlaps'] = $this->convert_flags_hlaps($data);
		}
		return $result;
	}
	function get_pb($racer) {
		if (is_array($racer)) {
			foreach($racer as $uname)
				$result[$uname] = $this->get_pb($uname);
		} else
			$result = $this->make_query('&action=pb&racer='.urlencode($racer));
		return $result;
	}
	function get_fuel($racer) {
		if (is_array($racer)) {
			foreach($racer as $uname)
				$result[$uname] = $this->get_fuel($uname);
		} else 
			return $this->make_query('&action=fuel&racer='.urlencode($racer));
	}
	function get_pst($racer) {
		if (is_array($racer)) {
			foreach($racer as $uname)
				$result[$uname] = $this->get_pst($uname);
		} else
			$result = $this->make_query('&action=pst&racer='.urlencode($racer));
		return $result;
	}
	function get_hosts() {
		$result = $this->make_query('&action=hosts');
		foreach ($result as $i => $data) {
			$result[$i]['tmlt'] = unpack('ctype/cmain/aletter/ctestId', $data['tmlt']);
			$result[$i]['tcrm'] = unpack('ctrack/cconfig/creversed/cmax', $data['tcrm']);
#			$result[$i]['cars'] = $this->convert_car_bits($data['cars']);
#			$result[$i]['rules'] = $this->convert_rule_bits($data['rules']);
		}
		return $result;
	}
	function get_teams() {
		$result = $this->make_query('&action=teams');
		foreach ($result as $i => $data) {
			$result[$i]['info'] = urldecode($data['info']);
			$result[$i]['bits'] = $this->convert_team_bits($data['bits']);
		}
		return $result;
	}
	function get_hl_log($log_filter = 4, $lines = 150, $control = null, $starttime = 0) {
		$result = $this->make_query("&action=hl_log&log_filter={$log_filter}&lines={$lines}&control={$control}&starttime={$starttime}");
		foreach ($result as $i => $data)
			$result[$i]['flags_hlaps'] = $this->convert_flags_hlaps($data);
		return $result;
	}
	function get_progress($host) {
		if (is_array($host)) {
			foreach ($host as $name) {
				$item = $this->get_progress($name);
				$return[$item['hostinfo']['host_stripped']] = $item;
			}
		} else
			$return = json_decode(array_pop(explode("\n", $this->fetch_data('http://www.lfsworld.net/pubstat/hostprogress.php?host='.urlencode($host)))), TRUE);
		return $return;
	}
}

?>
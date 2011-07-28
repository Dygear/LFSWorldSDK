<?php

	header('Content-Type: text/html; charset=UTF-8;');

	include('lfsworldsdk.php');

	print_r($SDK->get_teams());

?>
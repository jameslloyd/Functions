<?php
require_once('curl-functions.php');
function _getstats($player = 'KungFuMonkay',$platform = 'ps3')
	{
	$url = 'http://api.bf3stats.com/'.$platform.'/playerlist/?players='.$player ;
	$data = _curl($url);
	if ($data):
		$out = json_decode ($data, true);
	endif;
	return($out);
	}
function _getdogtags($player = 'KungFuMonkay',$platform = 'ps3')
	{
	$url = 'http://api.bf3stats.com/'.$platform.'/dogtags/?player='.$player ;
	$data = _curl($url);
	if ($data):
		$out = json_decode ($data);
	endif;
	return($out);
	
	}
<?php
function _geo_address($latlong) // $latlong = '1020,02103'
	{
        if(!function_exists(curl_init)):
        	die('curl not enabled');
        endif;
		$output = _curl("http://maps.googleapis.com/maps/api/geocode/json?latlng=$latlong&sensor=false");   
        $output = json_decode($output,true);
		return($output);	
}
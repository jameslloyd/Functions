<?php
function _geo_address($latlong) // $latlong = '1020,02103'
	{
        if(!function_exists(curl_init)):
        	die('curl not enabled');
        endif;
        // create curl resource 
        $ch = curl_init(); 
        // set url 
        curl_setopt($ch, CURLOPT_URL, "http://maps.googleapis.com/maps/api/geocode/json?latlng=$latlong&sensor=false");
        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        // $output contains the output string 
        $output = curl_exec($ch); 
        // close curl resource to free up system resources 
        curl_close($ch);    
        $output = json_decode($output,true);
		return($output);	
}
<?php
function _curl($url)
	{
		$ch = curl_init();  
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        $output = curl_exec($ch); 
        curl_close($ch); 
        if($output):
        	return($output);
        else:
        	return(false);
        endif;	
}
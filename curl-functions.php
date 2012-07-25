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
function _curl_linkstatus($url, $timeout = 30)
	{
        $ch = curl_init(); // get cURL handle
        // set cURL options
        $opts = array(CURLOPT_RETURNTRANSFER => true, // do not output to browser
                                  CURLOPT_URL => $url,            // set URL
                                  CURLOPT_NOBODY => true,                 // do a HEAD request only
                                  CURLOPT_TIMEOUT => $timeout);   // set timeout
        curl_setopt_array($ch, $opts); 
        curl_exec($ch); // do it!
        $retval = curl_getinfo($ch, CURLINFO_HTTP_CODE); // check if HTTP OK
        curl_close($ch); // close handle
        return $retval;
}
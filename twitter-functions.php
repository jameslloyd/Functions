<?php
function _twitter_get_new($twitter_id)
{
    if(!function_exists('_curl')):
        	die('include curl-functions.php');
    endif;
	$data = _curl("http://twitter.com/statuses/user_timeline/$twitter_id.xml");
	$data = _xml_xml2array($data);
	return($data);
}
function _twitter_get_profile($twitter_id)
	{
	$data = _twitter_get_new($twitter_id);
	if ($data['statuses']['status'][0]['user']):
		return($data['statuses']['status'][0]['user']);
		//print_r($data['statuses']['status'][0]['user']);
	else:
		return(false);
	endif;
	}




function _twitter_status($twitter_id) {	
	$c = curl_init();
	curl_setopt($c, CURLOPT_URL, "http://twitter.com/statuses/user_timeline/$twitter_id.xml");
	curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($c, CURLOPT_TIMEOUT, 5);
	$response = curl_exec($c);
	$responseInfo = curl_getinfo($c);
	curl_close($c);
	if (intval($responseInfo['http_code']) == 200) {
		if (class_exists('SimpleXMLElement')) {
			$xml = new SimpleXMLElement($response);
			return $xml;
		} else {
			return $response;
		}
	} else {
		return false;
	}
}

/** Method to add hyperlink html tags to any urls, twitter ids or hashtags in the tweet */ 
function _twitter_processLinks($text) {
	$text = utf8_decode( $text );
	$text = preg_replace('@(https?://([-\w\.]+)+(d+)?(/([\w/_\.]*(\?\S+)?)?)?)@', '<a href="$1">$1</a>',  $text );
	$text = preg_replace("#(^|[\n ])@([^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://www.twitter.com/\\2\" >@\\2</a>'", $text);  
	$text = preg_replace("#(^|[\n ])\#([^ \"\t\n\r<]*)#ise", "'\\1<a href=\"http://hashtags.org/search?query=\\2\" >#\\2</a>'", $text);
	return $text;
}

/** Main method to retrieve the tweets and return html for display */
function _twitter_get_tweets($twitter_id, 
					$nooftweets=3, 
					$dateFormat="D jS M y H:i", 
					$includeReplies=false, $dateTimeZone="Europe/London",
					$beforeTweetsHtml="<ul>", 
					$tweetStartHtml="<li class=\"tweet\"><span class=\"tweet-status\">",
					$tweetMiddleHtml="</span><br/><span class=\"tweet-details\">",
					$tweetEndHtml="</span></li>", 
					$afterTweetsHtml="</ul>") {

	date_default_timezone_set($dateTimeZone);
   	if ( $twitter_xml = _twitter_status($twitter_id) ) {
		$result = $beforeTweetsHtml;
		$i=0;
		foreach ($twitter_xml->status as $key => $status) {
			if ($includeReplies == true | substr_count($status->text,"@") == 0 | strpos($status->text,"@") != 0) {
				$message = _twitter_processLinks($status->text);
				$result.=$tweetStartHtml.$message.$tweetMiddleHtml.date($dateFormat,strtotime($status->created_at)).$tweetEndHtml;
				++$i;
				if ($i == $nooftweets) break;
    			}
    		}
			$result.=$afterTweetsHtml;
    } 
	else {
        $result.= $beforeTweetsHtml."<li id='tweet'>Twitter seems to be unavailable at the moment</li>".$afterTweetsHtml;
    }	
    echo $result;
}


function _twitter_get_tweets_array($twitter_id, 
					$nooftweets=3, 
					$dateFormat="D jS M y H:i", 
					$includeReplies=false, $dateTimeZone="Europe/London") {

	date_default_timezone_set($dateTimeZone);
   	if ( $twitter_xml = _twitter_status($twitter_id) ) {
		$i=0;
		foreach ($twitter_xml->status as $key => $status) {
			if ($includeReplies == true | substr_count($status->text,"@") == 0 | strpos($status->text,"@") != 0) {
				$message = _twitter_processLinks($status->text);
				$output['tweets'][$i]['timestamp'] = date('U',strtotime($status->created_at));
				$output['tweets'][$i]['created']= date($dateFormat,strtotime($status->created_at));
				$output['tweets'][$i]['tweet'] = $message;
				++$i;
				$output['tweets']['time'][date('U',strtotime($status->created_at))] = array ('user' => $twitter_id, 'tweet' => $message);
				$output['profile']['name'] = $status->user->name[0];
				$output['profile']['screen_name'] = $status->user->screen_name;
				if(!empty($status->user->location)):
					$output['profile']['location'] = $status->user->location;
				endif;
				if(!empty($status->user->description)):
					$output['profile']['description'] = $status->user->description;
				endif;				
				if ($i == $nooftweets) break;
    			}
    		}
    } 
	else {
        //w$output['tweets'][] = '';
    }	
	//rsort($output['time']);
    return $output;
}
?>
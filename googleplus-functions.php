<?php

function _ShortenText($text,$length) { 

        // Change to the number of characters you want to display 
        $chars = $length; 

        $text = $text." "; 
        $text = substr($text,0,$chars); 
        $text = substr($text,0,strrpos($text,' ')); 
        $text = $text."..."; 

        return $text; 

    } 

function _googleplus_person($id,$apikey)
	{
	$url = "https://www.googleapis.com/plus/v1/people/".$id."?fields=aboutMe%2CdisplayName%2Cimage%2Ctagline%2Curl%2Curls&pp=1&key=".$apikey;
	$data = file_get_contents($url);
	$parsed = str_replace(")]}'", "", $data);
	$parsed = str_replace('[,' , '["",' , $parsed); 
	$parsed = str_replace(',,' , ',"",' , $parsed); 
	$parsed = str_replace(',,' , ',"",' , $parsed);
	$output = json_decode ($parsed,true);
	//print_r($output);
	return($output);
	}
function _googleplus($id,$username ='',$password ='',$date = 'l jS Y',$truncate = 0) 
	{
	$url = 'https://plus.google.com/_/stream/getactivities/'.$id.'/?sp=[1,2,"'.$id.'",null,null,null,null,"social.google.com",[]]';

	
	
	if ($username == ''):
		$data = file_get_contents($url);
	else:
		
	endif;
	// reformat so that decode_json will work
	$parsed = str_replace(")]}'", "", $data);
	$parsed = str_replace('[,' , '["",' , $parsed); 
	$parsed = str_replace(',,' , ',"",' , $parsed); 
	$parsed = str_replace(',,' , ',"",' , $parsed);
	$output = json_decode ($parsed);
	//print_r($output);
	foreach ($output[0][1][0] as $item):
		unset($post);
		$post['author'] = $item[3];
		$post['avatar'] = 'http:'.$item[18];
		$post['id'] = $item[21];
		$post['postid'] = str_replace($id.'/posts/','',$item[21]);
		$post['permalink'] = "https://plus.google.com/" . $id;
		$post['when'] = date($date,$item[5] / 1000);
		$post['date'] = date('Y-m-d\TH:i:sO',$item[5] / 1000);
		if (!empty($item[4])):
			$content = $item[4];
		else:
			$content = $item[47];
		endif;
		
		if($truncate > 0):
			$content = _ShortenText($content,$truncate);
		endif;
		
		$post['content'] = $content;
		
		
		if (isset($item[44]) && !empty($item[44])):
			$post['origshare'][0] = true;
			$post['origshare']['avatar'] = $item[44][4];
			$post['origshare']['link'] = $item[44][1];
			$post['origshare']['who'] = $item[44][0];
		else:
			$post['origshare'][0] = false;
		endif;
	
		if (!empty($item[66][0][6])):
			$post['postimg'] = $item[66][0][6][0][2];
		else:
			$post['postimg'] = false;
		endif;
	
		if (isset($item[7][0])):
			if (isset($item[95][1])):
				$post['comments'] = count($item[95][1]);
			endif;
			$post['lastcomment']['name']= $item[7][0][1];
			$post['lastcomment']['comment']= $item[7][0][2];
			$post['lastcomment']['avatar'] = $item[7][0][16];
			$post['lastcomment']['id'] = $item[7][0][6];
			if (isset($item[95][1])):
					$post['commenter'] = $item[95][1];

			endif;
		else:
			$post['comments'] = 0;
		endif;
	
		if (isset($item[11][0][3])):
			$post['link']['title'] = $item[11][0][3];
			$post['link']['description'] = $item[11][0][21];
			$post['link']['url'] = $item[11][0][24][1];
			$post['link']['icon'] = 'http:'.$item[66][0][2];
		else:
			$post['link']['title'] = '';
		endif;
	
		$posts[] = $post;
		unset($post);
	endforeach;	
	if (!isset($posts)):
		return false;
	else:
		return($posts);
	endif;
		}
<?php
function _googleplus($id,$username ='',$password ='',$date = 'l jS Y') 
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
	print_r($output);
	foreach ($output[1][0] as $item):
		unset($post);
		$post['author'] = $item[3];
		$post['avatar'] = 'http:'.$item[18];
		$post['id'] = $item[21];
		$post['permalink'] = "https://plus.google.com/" . $id;
		$post['when'] = date($date,$item[5] / 1000);
		$post['date'] = date('Y-m-d\TH:i:sO',$item[5] / 1000);
		if (!empty($item[4])):
			$post['content'] = $item[4];
		else:
			$post['content'] = $item[47];
		endif;
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
	
		if (isset($item[60][0])):
			$post['comments'] = $item[60][0];
			
			foreach ($item[60][1] as $commenter):
				$post['commenters'][]= $commenter;
			endforeach;
		else:
			$post['comments'] = 0;
		endif;
	
		if (isset($item[11][0][3])):
			$post['link']['title'] = $item[11][0][3];
			$post['link']['description'] = $item[11][0][21];
			$post['link']['url'] = $item[11][0][24][1];
		else:
			$post['link']['title'] = '';
		endif;
	
		$posts[] = $post;
		unset($post);
	endforeach;	
	return($posts);
	}
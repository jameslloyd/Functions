<?php
function _echo($item,$pre = '',$post = '')
	{
	if ($item && isset($item) && !empty($item)):
		echo $pre.$item.$post;
	endif;
	}

function _tmdb_getinfo($id)
	{
	global $TMDBapikey;
	$url = "http://api.themoviedb.org/2.1/Movie.getInfo/en/json/$TMDBapikey/".$id;
	//$url = "http://localhost:8888/2state10/187.txt";
	$data = file_get_contents($url);
	if ($data):
		$out = json_decode ($data);
		//print_r($out);
		foreach ($out[0] as $k => $v):
			$output[$k] = $v;	
		endforeach;		
	foreach($output as $k => $v):
	if (!is_object($v) && !is_array($v) && !empty($v)):
		$film[$k] = $v;
	endif;
	if (($k == 'posters' || $k == 'backdrops') && is_array($v)):
		foreach($v as $i):
			$posters[$i->image->type][$i->image->size][] = array('url' => $i->image->url, 'width' => $i->image->width, 'height' => $i->image->height);
		endforeach; 
	endif;
	if ($k == 'cast' && is_array($v)):
		foreach($v as $c):
			//echo $c->name."\n";
			$cast[$c->department][] = array('name' => $c->name,
									'department' => $c->department,
									'job' => $c->job,
									'character' => $c->character,
									'id' => $c->id,
									'order' => $c->order,
									'cast_id' => $c->cast_id,
									'url' => $c->url,
									'profile' => $c->profile,
									);
									
		endforeach;
	endif;	
	if ($k == 'genres' && is_array($v)):
		foreach($v as $g):
			//echo $c->name."\n";
			$genre[] = array('name' => $g->name,
									'type' => $g->type,
									'url' => $g->url,
									'id' => $g->id
									);
									
		endforeach;
	endif;
	endforeach;	
	array_rand($posters['poster']['thumb']);
	$film['genres'] = $genre;
	$film['keywords'] = $out[0]->keywords;
	$film['posters'] = $posters;
	$film['cast'] = $cast;	
	if (!isset($film['tagline'])):
		$film['tagline'] ='';
	endif;	
		
		
		return $film;
	else:
		return false;
	endif;
	}
function _tmdb_person($id)
	{
	global $TMDBapikey;
	$url = "http://api.themoviedb.org/2.1/Person.getInfo/en/json/$TMDBapikey/".$id;
//echo $url;
	$data = file_get_contents($url);
	if ($data):
		$out = json_decode ($data);
	//print_r($out);
	foreach($out[0] as $k => $v):
	if (!is_object($v) && !is_array($v) && !empty($v)):
		$output[$k] = $v;
	endif;
	//echo $k .' = '.$v."\n";
	if($k == 'filmography'):
	//print_r($v);
		foreach($v as $f):
			$output['filmography'][$f->name] =  array('name' => $f->name,
											'id' => $f->id,
											'job' => $f->job,
											'department' => $f->department,
											'character' => $f->character,
											'cast_id' => $f->cast_id,
											'url' => $f->url,
											'poster' => $f->poster,
											'release' => $f->release); 
		endforeach;

	endif;
	if($k == 'profile'):
		foreach($v as $p):
			$output['images'][$p->image->type][$p->image->size][] = array('url' => $p->image->url,
																		'width' => $p->image->width,
																		'height' => $p->image->height,
																		'size' => $p->image->size,
																		'type' => $p->image->type,
																		'id' => $p->image->id);
		endforeach;
	endif;
	
	endforeach;
		
		return $output;
	else:
		return false;
	endif;
	}

function _tmdb_search($search)
	{
	global $TMDBapikey;
	$search = str_replace(" ","+",$search);
	$url = "http://api.themoviedb.org/2.1/Movie.search/en/json/$TMDBapikey/".$search;
//echo $url;
	$data = file_get_contents($url);
	if ($data):
		$output = json_decode ($data);

		
		
		
		return $output;
	else:
		return false;
	endif;
	}
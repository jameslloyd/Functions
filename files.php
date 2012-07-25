<?php
function _get_dir_files($path,$filetype = 'csv',$fullpath = false) {
        if ($handle = opendir($path)):
                /* This is the correct way to loop over the directory. */
                $files = false;
                while (false !== ($entry = readdir($handle))):
                        //echo "$entry\n";
                        if ( $entry !='.' && $entry !='..' && substr($entry, -3) == $filetype):
                                if (!is_dir($path.'/'.$entry)):
                                	if ($fullpath == true):
                                		$files[] = $path . '/' . $entry;
                                	else:
                                        $files[] = $entry;
                                    endif;
                                endif;
                        endif;
                endwhile;

                closedir($handle);
        endif;
        return($files);
}

function _open_delimited_file($file,$delim){
        $row = 0;
        if(($handle = fopen($file, "r")) !== FALSE):
                while (($data = fgetcsv($handle, 1000,$delim)) !== FALSE):
                        $num = count($data);
                        //echo "<p> $num fields in line $row: <br /></p>\n";
                $row++;
                for ($c=0; $c < $num; $c++):
            //  echo $data[$c] . "<br />\n";
                endfor;
                $output['filename'] = $file;
                        $output[$row]['date'] = trim($data[0]);
                        $output[$row]['location'] = trim($data[1]);
                        $output[$row]['accuracy'] = trim($data[2]);
                        $output[$row]['altitude'] = trim($data[3]);
                        $output[$row]['speed'] = trim($data[4]);
                        $output[$row]['cellid'] = trim($data[5]);
                        $output[$row]['cellsig'] = trim($data[6]);
                        $output[$row]['cellsvr'] = trim($data[7]);
                        if (isset($data[8])):
                                $output[$row]['battery'] = trim($data[8]);
                        endif;
                        if (isset($data[9])):
                                $output[$row]['power'] = trim($data[9]);
                        endif;
                endwhile;
        endif;
        return($output);
}
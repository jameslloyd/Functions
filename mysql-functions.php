<?php

/*############ My SQL Functions  ###############*/

function  _dbconnect($db) 
        {
        $link = mysql_connect($db['dbhost'], $db['user'], $db['pass']);
        if (!$link) { die('Not connected : ' . mysql_error()); }
        $db_selected = mysql_select_db($db['dbname'], $link);
        if (!$db_selected) { die ('Can\'t use '.$db['dbname'].' : ' . mysql_error()); }        
        }

//_dbupdate executes a SQL statement, i.e for UPDATE, DROP etc statements.
function _dbupdate ($sql,$db)
        {

        _dbconnect($db);
        $result = mysql_query($sql);
        if (!$result) {
            die("\n ".'<br><font color="red"><b>Invalid query:</b></font> ' . mysql_error().'<br>'.$sql);
                    }
	    return(mysql_insert_id());
        mysql_close();
        }

// _dbquery returns an array from a SELECT statement (OLD NON ZEND)    
function _isitindb ($sql,$db)
        {
         // Connect to the database
         _dbconnect($db);
         $result = mysql_query($sql);
         $num_rows = mysql_num_rows($result);
         if ($num_rows > 0) {
            return true; } else {
            return false;
            }
        
        }

// _dbcount chucks out a total of rows for the vote counting   
function _dbcount ($sql,$db)
        {
         // Connect to the database
         _dbconnect($db);
         $result = mysql_query($sql);
         $num_rows = mysql_num_rows($result);        
         return $num_rows;
	}

function _dbquery ($sql,$db,$type=MYSQL_ASSOC,$print=false)  // type MYSQL_ASSOC , MYSQL_NUM , MYSQL_BOTH
        {
         global $_GET;  
        _dbconnect($db);
        $query = mysql_query($sql);
        $i=0;
        if ($print == true)
            {
             echo '<div class=debug>';
             echo '<h5>OUTPUT for '.$sql.'</h5>';   
            }
        while ($results = mysql_fetch_array($query,$type))
            {
            $output['themoviedb'][$i]=$results;
            $i++;
            }
        
        if ($print == true)
            {
            echo '<pre>';
            print_r($output['themoviedb']);
            echo '</pre>';
            }
        if ($print == true)
            {
             echo '</div>';   
            }        
        if (isset($output['themoviedb'])) { return $output['themoviedb']; } else { return false; }
        mysql_close();
        }




/*############ End of MYSQL Functions  ###############*/

?>

<?php
function _gmailconnect ($u, $p, $label = ''){
        $h = '{imap.gmail.com:993/imap/ssl}'.$label;
        $conn = imap_open($h,$u,$p) or die('Cannot connect to Gmail: ' . print_r(imap_errors()));
        $errs = imap_errors();
        if ($errs[3] =='Too many login failures'):
         echo 'Captcha Reset may be required https://www.google.com/accounts/DisplayUnlockCaptcha';
        endif;  
        return($conn);
}
function _gmail_listlabels ($u,$p){
        $conn = _gmailconnect($u,$p);
        $h = '{imap.gmail.com:993/imap/ssl}';
        $labels = imap_list($conn, $h, '*');
        imap_close($inbox);
        foreach($labels as $label):
                $output[]= str_replace($h,'',$label);
        endforeach;
        if ($output):
                return($output);
        else:
                return(false);
        endif;
		imap_close($conn);
}
function _getgmail($u,$p,$label,$read = 'UNSEEN') // $read can be UNSEEN or ALL (unread or any)
        $conn = _gmailconnect ($u, $p, $label);
        $email = imap_search($conn,$read);
        if ($email):
                rsort($email);
        $i=0;
        foreach ($email as $e):
                $output[$i]['body'] = quoted_printable_decode(imap_fetchbody($conn,$e,2));              
                $header = imap_fetch_overview($conn,$e,0);
                $header = $header[0];
                $output[$i]['messageid'] = $header->message_id;
                $output[$i]['subject'] = $header->subject;
                $output[$i]['to'] = $header->to;
                $output[$i]['from'] = $header->from;
                $output[$i]['date'] = $header->date;        
		$i++;               
        endforeach;
        return($output);
        else:
        return(false);
        endif;
		imap_close($conn);
    }
function _getgmaildb($u,$p,$label,$read = 'UNSEEN',$dbcommit = FALSE ) // $read can be UNSEEN or ALL (unread or any)
        $db['dbhost'] = 'localhost';
        $db['user'] = 'root';
        $db['pass'] = 'password';
        $db['dbname'] = 'email';


        $conn = _gmailconnect ($u, $p, $label);
        $email = imap_search($conn,$read);
        if ($email):
                rsort($email);
        $i=0;
        foreach ($email as $e):
                $output[$i]['body'] = quoted_printable_decode(imap_fetchbody($conn,$e,2));
                
                
                $header = imap_fetch_overview($conn,$e,0);
                $header = $header[0];
                $output[$i]['messageid'] = $header->message_id;
                $output[$i]['subject'] = $header->subject;
                $output[$i]['to'] = $header->to;
                $output[$i]['from'] = $header->from;
                $output[$i]['date'] = $header->date;        
        if ($dbcommit):        
			if (_isitindb("SELECT id FROM data WHERE messageid = '".$output[$i]['messageid']."'",$db)):      
				
			else:
				$insert = "
					INSERT INTO `email`.`data` (`id`, `messageid`, `to`,`from`,`subject`, `body`, `date`) 
					VALUES (NULL, '".$output[$i]['messageid']."', '".mysql_escape_string($output[$i]['to'])."','".mysql_escape_string($output[$i]['from'])."','".mysql_escape_string($output[$i]['subject'])."', '".mysql_escape_string($output[$i]['body'])."', '".mysql_escape_string($output[$i]['date'])."');
					";
				_dbupdate($insert,$db);	
			endif;
		endif;
		$i++;               
        endforeach;
        return($output);
        else:
        return(false);
        endif;
        imap_close($conn);
    }
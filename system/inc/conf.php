<?php
/*****************************************************************************************************/
//				######   ######       ######   #######  ##    ## ######## ####  ######   
//			   ##    ## ##    ##     ##    ## ##     ## ###   ## ##        ##  ##    ##  
//			   ##       ##           ##       ##     ## ####  ## ##        ##  ##        
//				######  ##   ####    ##       ##     ## ## ## ## ######    ##  ##   #### 
//					 ## ##    ##     ##       ##     ## ##  #### ##        ##  ##    ##  
//			   ##    ## ##    ##     ##    ## ##     ## ##   ### ##        ##  ##    ##  
//				######   ######       ######   #######  ##    ## ##       ####  ######   
//									######## #### ##       ######## 
//									##        ##  ##       ##       
//									##        ##  ##       ##       
//									######    ##  ##       ######   
//									##        ##  ##       ##       
//									##        ##  ##       ##       
//									##       #### ######## ########
/*****************************************************************************************************/		



$global = new sgClass;
$capcha = new capcha;





/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~!sgClass Dec.!~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
class sgClass
{
	function __construct()
	{

	//Now that the headers have be cleared, lets state some globals.
		$this->user = '';
		$this->alerts = '';
		$this->system = '';
		$this->get = $_GET;
		$this->POST = $_POST;

	//Make client variables safe for use. dirty clients need sanitation; Heh!
		$_GET = $this->makeSafe( $_GET, NULL );
		$_POST = $this->makeSafe( $_POST, NULL );
		
	//Database Variables
		$this->db['username'] = '';
		$this->db['password'] = '';
		$this->db['host'] = '';
		$this->db['dbname'] = '';
		
	//Now lets create a database link (also creates the `system` variables)
		$this->db['link'] = $this->createDatabaseLink();
		
	//If it's still a beta site, lets show verbose errors
		if( $this->system['debug'] == 1 )
		{
			$this->system['debug'] = true;
		}
		else
		{
			$this->system['debug'] = false;
		}
		$this->showErrors( $system['debug'] );	
	}


//This makes variables safe enough to use in mysql and direct display
	function makeSafe( $content, $exempt )
	{
		foreach( $content as $key => $data )
		{
			$temp = str_replace( array("'", '"','\\'), array("&apos;", '&quot;','&#093;'), stripslashes($data));
			$content[$key] = addslashes(strip_tags( $temp, $exempt ));
		}
		return($content);
	}


//Establishes a link tot he database engine
	function createDatabaseLink(  )
	{
		//attempts connection to the MySQL database system
		if( $link = mysql_connect( $this->db['host'], $this->db['username'], $this->db['password'] ) )
		{
			//Connects to the database
			if( mysql_select_db( $this->db['dbname'] ) )
			{
				//Collects the system variables
				if($this->system = mysql_fetch_array( mysql_query( " select * from `". $this->db['dbname']."`.`system` where `id`='1' " ) ))
				{
					//return true on successfull connection
					return $link;
				}
			}
		}
		
		//all else fails, returns false
		return false;
	}
	

//Displays the requested page. NOTE: DEFAULTS TO THE HOMEPAGE IF PAGE NOT FOUND!
	function getPages( $page )
	{
		$file = getcwd(  ).'/system/inc/pages/'.$page.'.php';
		if( is_file( $file ) )
		{
			include( $file );
		}
		else
		{
			include( getcwd(  ).'/system/inc/pages/home.php' );
		}
	}
	
	function getTitle(  )
	{
		return $this->system['title'];
	}
	
	function showErrors( $var )
	{
		if( $var )
		{
			error_reporting( 6135 );

		}
		else
		{
			error_reporting( 6135 );
		}
	}
	
	function getCss( $handle )
	{		
		$handle = opendir( $handle );
		while( ( $file = readdir( $handle ) ) != false)
		{
			if( $file != '.' and $file != '..' and $file != '_notes' and substr($file,strlen($file)-4, strlen($file)) == '.css' )
			{
				echo"<link href=\"system/css/".$file."\" rel=\"stylesheet\" type=\"text/css\" /> \n\r";
			}
		}	
	}
	
	function getECMAScript( $handle )
	{		
		$handle = opendir( $handle );
		while( ( $file = readdir( $handle ) ) != false)
		{
			if( $file != '.' and $file != '..' and $file != '_notes' and substr($file,strlen($file)-3, strlen($file)) == '.js' )
			{
				echo"<script type=\"text/javascript\" src=\"system/inc/scripts/js/".$file."\"></script> \n\r";
			}
		}	
	}
	
	
//This gets info from the `content`	
	function getDatabaseContent( $handle, $numRows, $whereTemp )
	{
		
	//Calculates the neccesity for the $where attribute.
		if( strlen( $where ) > 0 )
		{
			$where = $whereTemp;
		}
			$dbName = $this->db['dbname'];
	//Gets all the rows
		$SQL = mysql_query( "select * from `$dbName`.`content` where `handle`='$handle' $where order by `id` desc limit 0,$numRows " );

	//Arranges them into an array.
		while( $row = mysql_fetch_array( $SQL ) )
		{
			$multArray[count($multArray)-1] = $row;
		}
		
	//When done; Return;
		return $multArray;
	}
	
	
//This gets info from the `content`	
	function getContactForm( $handle )
	{
		
	}

	function getRandColour($exceptions)
	{
		$str = '';
		$alphaRange = range('A','A');
		$numericRange = range(1,9);
		$mixedRange = $numericRange;
	
		mt_srand(mt_rand(1, time())*1000003);
	
		foreach($alphaRange as $alpha)
		{
			$mixedRange[count($mixedRange)+1] = $alpha;
		}
		
		for( $str ='';  strlen($str) < 6; $str .= $mixedRange[mt_rand(0,15)] )	
		{
		}
		
		
	//Handles exception colours
	
		if(!is_array($exceptions))
		{
			$exceptions = array($exception);
		}
		
		foreach($exceptions as $x)
		{
			if(($x) == '#'.$str)
			{
				$str = getRandColour($x);
			}
		}
		
		return($str);
	}
	
	function getRssFeeds( $where )
	{
		$dbName = $this->db['dbname'];
		$SQL = mysql_query( "select * from `$dbName`.`rss` $where " );
		
		return $SQL;
	}

						/*This dynamically makes the sql code for any tables*/
	function getFields($tablename, $database, $mysql_connection)
	{
		$fields = mysql_list_fields($database, $tablename, $mysql_connection);
		$columns = mysql_num_fields($fields); 
		
		
		for ($i = 0; $i < $columns; $i++) 
		{
			$table_field[mysql_field_name($fields, $i)] = mysql_field_name($fields, $i);
		}
	
		return $table_field;
	}	

	function alterEntries($user_details, $db, $type, $table, $where)
	{
		$tf = $this->getFields($table, $db['dbname'], $db['link']);
	//This is just setting up some variables for use in the switch.
		if(is_array($user_details))
		{
				//This is so far the best way I can see that will let you count the number of items to be parsed.
			foreach($tf as $tf_key)
				{
					if($user_details[$tf_key] != NULL)
					{
						$x++;
					}
				}
		}	
	
	
		//Lets assemble this sucker.
		switch($type)
		{
		
			case'new_entry':
				
		
			foreach($tf as $tf_key)
			{
		
				if($tf_key != NULL)
					{
						if($user_details[$tf_key] != NULL)
							{
								$c++;
									if($c < $x)
									{
										$comma = ',';
									}
									
									else
									{
										$comma = NULL;
									}
									
								$sql_part1 .= '`'.$tf_key."`$comma "; 
								$sql_part2 .="'".$user_details[$tf_key]."'$comma "; 
								$user_details[$tf_key] = NULL;
				
							
							}
					}
						
			}
				
					return("insert into `$db[dbname]`.`$table` ($sql_part1) values($sql_part2) ");
				break;
				
			
			case'update_entry':
	
				foreach($tf as $tf_key)
				{
			
					
					if($tf_key != NULL)
					{
						if($user_details[$tf_key] != NULL)
						{
							$c++;
								if($c < $x)
								{
									$comma = ',';
								}
								
								else
								{
									$comma = NULL;
								}
							
							$sql_part1 .= "`$tf_key`='$user_details[$tf_key]'$comma "; 
							$user_details[$tf_key] = NULL;
							
							
						}
					}
											
			
				}
				
				return("update `$db[dbname]`.`$table` set $sql_part1 $where");
				
			break;
			
	
			case'delete_entry':
			return("delete from `$db[dbname]`.`$table` $where");
			
			break;
			
		}
		
		return false;
	}

//Checks to see if the submitter is legit.
	function canSubmit(  )
	{
		return TRUE;
	}

//Determins if a user is an admin
	function isAdmin( $user )
	{
		//is the users rank correct for admin?
		if( $user['level'] == 5 )
		{
			//Rank is correct return true
			return TRUE;
		}
		
		//All else fails, return false!
		return FALSE;
	}


}
















/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~!Capcha Class!~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
class capcha
{
	function image($str)
	{
		header('Content-type: image/png');  
		$string = $this->decode( $str );
		


		// some variables to set
		$font  = 5;
		$width  = ImageFontWidth( $font ) * strlen( $string );
		$height = ImageFontHeight( $font );
		
		// lets begin by creating an image
		$im = @imagecreatetruecolor( $width+10, $height+10 );
		
		//white background
		$background_color = @imagecolorallocate( $im, 255, 255, 255 );
		
		//black text
		$text_color = @imagecolorallocate( $im, 255, 255, 255 );
		
		// put it all together
		@imagestring( $im, $font, 5, 5,  $string, $text_color );
		
		// and display
		@imagejpeg( $im );  
	}
	
	function verify( $id1, $id2 )
	{
		$str1 =  $id1;
		$str2 = $this->decode( $this->decode( $id2 ) );
		if($str1 == $str2)
		{
			return true;
		}
		else
		{
			return false;
		}
	}
	
//Makes the capcha fields
	function makeField($error)
	{
		$alpha = range('A','Z'); //Make the alphabet range
		$str = $alpha[rand(0,count( $alpha ))].(rand('1111','9999' ).$alpha[rand(1,26)]); //Make the capcha string
		return(	$str.' 
					<div id="capchaID" class="capchaID" style="vertical-align: top;">
						<img src="?capcha='.$this->encode( $str ).'" width="64" height="25" /><br />
						'.$error.'
						<input type="hidden" name="capchaPass" id="capchaPass" value="'.$this->encode( $this->encode( $str ) ).'" />
						<input type="text" name="capcha" id="capcha" style="width: 64px; height:25px; border: 1px #000000 solid; font-size:12px;" value=""  /><br />
						Image is case SENSITIVE
					</div>
				');
	}
//encodes the string	
	function encode($str)						
	{
		$temp =	@base64_encode($str);
		$temp =	@gzcompress($temp);
		$temp =	@base64_encode($temp);
		$temp =	@gzcompress($temp);
		$temp =	@base64_encode($temp);
		$temp =	@urlencode($temp);
		return($temp);
	}

//decodes the string
	function decode($str)						
	{
		$temp =	@urldecode($str);
		$temp =	@base64_decode($temp);
		$temp =	@gzuncompress($temp);
		$temp =	@base64_decode($temp);
		$temp =	@gzuncompress($temp);
		$temp =	@base64_decode($temp);
		return($temp);
	}	
}
?>
<?php
/*****************************************************************************************************/
//				######   ######       ######   #######  ##    ## ######## ####  ######   
//			   ##    ## ##    ##     ##    ## ##     ## ###   ## ##        ##  ##    ##  
//			   ##       ##           ##       ##     ## ####  ## ##        ##  ##        
//				######  ##   ####    ##       ##     ## ## ## ## ######    ##  ##   #### index.cfm
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
		
		
	//Now that the headers have be cleared, lets state some globals.
		$this->get = $_GET;
		$this->POST = $_POST;


	//Make client variables safe for use. dirty clients need sanitation; Heh!
		$_GET = $this->makeSafe( $_GET, NULL );
		$_POST = $this->makeSafe( $_POST, NULL );


	//Database Variables
		$this->db['username'] = 'sgCMS';
		$this->db['password'] = 'sgCMS';
		$this->db['host'] = 'localhost';
		$this->db['dbname'] = 'sgCMS';


	//Now lets create a database link (also creates the `system` variables)
		$this->db['link'] = $this->createDatabaseLink();

	//Global Definitions.
	
		define( TEMPLATE, $this->system['template']);
		define( tDir, 'system/templates/');
		define( ECMADir, 'system/inc/scripts/ecma');
		define( CSSDir, 'system/css');		
		$this->tConfig = $this->getTemplateConfig( TEMPLATE ); //Template Config file;
		define( templateECMADir,  tDir.TEMPLATE.'/'.$this->tConfig['ECMAFolder'] );
		define( templateCSSDir, tDir.TEMPLATE.'/'.$this->tConfig['cssFolder']);
		define( templateIMGDir, tDir.TEMPLATE.'/'.$this->tConfig['imgFolder']);
		define( PHPDir, 'system/inc/scripts/php');
		
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
	

//Displays the requested page. NOTE: DEFAULTS TO THE HOMEPAGE IF PAGE NOT FOUND! (Default = home.php)
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
	
//Gets the page title.	
	function getTitle(  )
	{
		return $this->system['title'];
	}

//Sets the error reporting value
	function showErrors( $var )
	{
		if( !$var )
		{
			error_reporting( 6135 );
			include('scripts/php/FirePHPCore/FirePHP.class.php');
			include('scripts/php/FirePHPCore/fb.php');	
		}
		else
		{
			error_reporting( 0 );
		}
	}


//Appends all template stylesheets into the DOM
	function getCss( $doc )
	{		
	
	//First we'll append the global styles (this way they're overidable by the template sheets)
		$handle = opendir( CSSDir );
		while( ( $file = readdir( $handle ) ) != false)
		{
			if( $file != '.' and $file != '..' and $file != '_notes' and substr($file,strlen($file)-4, strlen($file)) == '.css' )
			{
				$x = count($css);
				$css[$x] = $doc->createElement( 'link', '' );
				$link = $doc->createAttribute('href');
				$linkText = $doc->createTextNode( CSSDir.'/'.$file );
				
				$rel = $doc->createAttribute('rel');
				$relText = $doc->createTextNode( 'stylesheet' );
				
				$type = $doc->createAttribute('type');
				$typeText = $doc->createTextNode( 'text/css' );
				
				$link->appendChild( $linkText );
				$rel->appendChild( $relText );
				$type->appendChild( $typeText );
				$css[$x]->appendChild( $link );
				$css[$x]->appendChild( $rel );
				$css[$x]->appendChild( $type );
			}
		}
		
	//Now we proccess the template directorys' styles.	
		if( is_dir( templateCSSDir ) )
		{
			$handle = opendir( templateCSSDir );
			while( ( $file = readdir( $handle ) ) != false)
			{
				if( $file != '.' and $file != '..' and $file != '_notes' and substr($file,strlen($file)-4, strlen($file)) == '.css' )
				{
					$x = count($css);
					//Creates the <link> DOM node
					$css[$x] = $doc->createElement( 'link', '' );
					
					//This created the href="" attribute
					$link = $doc->createAttribute('href');
					$linkText = $doc->createTextNode( templateCSSDir.'/'.$file );
					
					//This created the rel="stylesheet" attribute					
					$rel = $doc->createAttribute('rel');
					$relText = $doc->createTextNode( 'stylesheet' );
					
					//This created the type="text/css" attribute					
					$type = $doc->createAttribute('type');
					$typeText = $doc->createTextNode( 'text/css' );
					
					
					//Now we append the text to the attributes
					$link->appendChild( $linkText );
					$rel->appendChild( $relText );
					$type->appendChild( $typeText );
					
					//Now we append them all to the <link> node
					$css[$x]->appendChild( $link );
					$css[$x]->appendChild( $rel );
					$css[$x]->appendChild( $type );
				}
			}	
		}
		
		//Now lets send it back to the DOM Parser func
		return $css;	
	}


//Appends all global and template ECMA into the DOM
	function getECMAScript( $doc )
	{		
	
	//As with the stylesheets, we'll start by appending the global ECMA to the DOM, that way we can override global functions later on if need be
		$handle = opendir( ECMADir );
		while( ( $file = readdir( $handle ) ) != false)
		{
			if( $file != '.' and $file != '..' and $file != '_notes' and substr($file,strlen($file)-3, strlen($file)) == '.js' )
			{
				$x = count($ecma);
				$ecma[$x] = $doc->createElement( 'script', '' );
				$fileLoc = $doc->createAttribute('src');
				$srcText = $doc->createTextNode( ECMADir.'/'.$file );
				$fileLoc->appendChild( $srcText );
				$ecma[$x]->appendChild( $fileLoc );
			}
		}
		
	//Now we append the template's 	ECMA (if the folder exists)
		if( is_dir( templateECMADir ) )
		{
			$handle = opendir( templateECMADir );
			while( ( $file = readdir( $handle ) ) != false)
			{
				if( $file != '.' and $file != '..' and $file != '_notes' and substr($file,strlen($file)-3, strlen($file)) == '.js' )
				{
					$x = count($ecma);
					$ecma[$x] = $doc->createElement( 'script', '' );
					$fileLoc = $doc->createAttribute('src');
					$srcText = $doc->createTextNode( templateECMADir.'/'.$file );
					$fileLoc->appendChild( $srcText );
					$ecma[$x]->appendChild( $fileLoc );
				}
			}
		}
		
		return $ecma;
	}
	
	
//This gets info from the `content`	table
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
		
	//When done; Return
		return $multArray;
	}
	
	
//This gets info from the `content`	
	function getContactForm( $handle )
	{
		
	}


//creates a random colour (accepts an array of colours that are exceptions)
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


//Creates an RSS Feed based on the array sent to it.
	function makeRSS(  )
	{
		$dbName = $this->db['dbname'];
		$SQL = mysql_query( "select * from `$dbName`.`rss` $where " );
		return $SQL;
	}


//getFields finds and returns all the field names in a given table, as an array. 
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


//alterEntries is a method for auto creating a MySQL Complient string for query, using just a few input parameters.
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

//Determines if a user is an admin
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

//Gets the specified template and parses the websites *NOTE: DEFAULTS TO THE TEMPLATE LABLED `default`` IN THE TEMPLATE DIR.
	function parseTemplate( $t )
	{
		if( $this->isValidTemplate( tDir.$t ) )
		{
			$doc = new DOMDocument(  );
			$doc->loadHTMLFile( $this->getIndex( tDir.$t ) );
			
			
			fb( templateCSSDir );
			
			//Gets the `Head` Node
			$head = $doc->getElementsByTagName('head');
			foreach($head as $key=>$node)
			{
				$head = $node;
				break;
			}
			
			//Gets all ECMA script files included.
			foreach( $this->getECMAScript( $doc ) as $key=>$data )
			{
				$head->appendChild( $data );
			}
			
			//gets all stylesheet files included
			foreach( $this->getCss( $doc ) as $key=>$data )
			{
				$head->appendChild( $data );
			}
						
			$page = $doc->saveXML(  );
			return $page;
		}
		else
		{
			//include(  getcwd().'/system/templates/sgCMSDefault/index.html' );
		}
	}


//Check if the specified template exists
	function isValidTemplate( $temp )
	{
		if( $temp != NULL and is_dir( $temp ) and $this->getIndex( $temp ) and file_exists( $temp.'/config.xml' ) )
		{
			return TRUE;
		}
		
		return FALSE;
	}


//Gets the config file for a template
	function getTemplateConfig( $t )
	{
			$config = new DOMDocument(  );
			$config->loadXML( file_get_contents( tDir.$t.'/config.xml' ) );
			$temp['title'] = $config->getElementsByTagName( 'title' )->item(0)->nodeValue;
			$temp['author'] = $config->getElementsByTagName( 'author' )->item(0)->nodeValue;
			$temp['pubDate'] = $config->getElementsByTagName( 'pubDate' )->item(0)->nodeValue;
			$temp['imgFolder'] = $config->getElementsByTagName( 'imgFolder' )->item(0)->nodeValue;
			$temp['ECMAFolder'] = $config->getElementsByTagName( 'ECMAFolder' )->item(0)->nodeValue;
			$temp['cssFolder'] = $config->getElementsByTagName( 'cssFolder' )->item(0)->nodeValue;
		
		return $temp;
	}
	
	
//gets the index file for a given directory
	function getIndex( $dir )
	{
		$indexList = 
			array(
					'default.htm',
					'index.php',
					'index.html',
					'index.htm',
					'index.cfm',
				);
		foreach( $indexList as $i )
		{
			$temp = $dir.'/'.$i;
			if( is_file( $temp ) )
			{
				return $temp;
			}
		}
		return FALSE;
	}


//Gets the `innerHTML` of a given node
	function DOMinnerHTML($element)
	{
		$innerHTML = "";
		$children = $element->childNodes;
		foreach ($children as $child)
		{
			$tmp_dom = new DOMDocument();
			$tmp_dom->appendChild($tmp_dom->importNode($child, true));
			$innerHTML.=trim($tmp_dom->saveHTML());
		}
		return $innerHTML;
	}	







}
















/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~!Capcha Class!~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
class capcha
{
//Creates the image	
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
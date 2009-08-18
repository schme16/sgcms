<?php

$global = new sgClass;
$capcha = new capcha;

	if( !$_POST )
	{
		echo'
			<form action="" method="post" enctype="application/x-www-form-urlencoded">
			<table width="593" border="0" cellspacing="0" cellpadding="0">
			<tr>
			<td width="127" height="30">Feed Name:</td>
			<td width="466"><label>
			<input name="title" type="text" id="title" size="75">
			</label></td>
			</tr>
			<tr>
			<td height="29">Link to Feed:</td>
			<td><input name="link" type="text" id="link" size="75"></td>
			</tr>
			<tr>
			<td>Your Email Address:</td>
			<td><input name="authorEmail" type="text" id="authorEmail" size="75"></td>
			</tr>
			</table>
			', $capcha->makeField( NULL ),'
			<p>
			<label>
			<input type="submit" name="button" id="button" value="Submit">
			</label>
			</p>
			<p>Please allow a few hours for your feed to be manually verified; SUBMIT IT ONLY ONCE!</p>
			</form>
			';
	}
	
	elseif($_POST and $global->canSubmit(  ) )
	{
		foreach( $_POST as $key => $data )
		{
			if( $data == NULL )
			{
				$error[count($error)-1] = $key;
			}
		}
		
		if(count($error) < 1)
		{
			if( $capcha->verify( $_POST['capcha'], $_POST['capchaPass'] ) )
			{
				$_POST['date'] = time();
			
				if( mysql_query( $global->alterEntries( $_POST, $global->db, 'new_entry', 'rss', '' ) ) )
				{
					echo'You feed has been submitted; and will be reviewed shortly; once reviewed you will receive an email notifying you of it\'s acceptance of denial';
				}
			}
			else
			{
				echo'Your Image verifacation was incorrect; please go back and try again.';
			}
		}
		else
		{
			echo'Please go back and fill out the following fields: ';
			
			foreach( $error as $data )
			{
				echo $data,'<br />';
			}
		}
	}
	
	elseif($_POST and $global->canSubmit(  ) == FALSE )
	{
		echo'You cannot submit more than once per 15 minutes.';
	}
?>
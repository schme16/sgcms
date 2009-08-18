<?php
	$global = new sgClass;

	if( $global->isAdmin( $user ) or true )
	{
		if( $_GET['action'] == NULL )
		{
			$SQL = $global->getRssFeeds( "where `verified`='false' order by `date` asc" );
			while( $rss = mysql_fetch_array( $SQL ) )
			{
				echo 'Check: <a href="', $rss['link'], '">', $rss['title'], '</a> || <a href="?page=admin&action=verify&id=',$rss['id'],'">Verify</a><br />';
			}
		}
		
		if( $_GET['action'] == 'verify' and is_numeric( $_GET['id'] ) )
		{
			$SQL = $global->getRssFeeds( "where `verified`='false' order by `date` asc" );
			print 'not yet verified!';
		}
	}
?>
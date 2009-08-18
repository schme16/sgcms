<?php

$global = new sgClass;
	$resID = $global->getRssFeeds( "where `verified`='true' order by `id` desc limit 0,10" );
	while( $item = mysql_fetch_array( $resID ) )
	{
		echo '<br /><div style="background:#',$global->getRandColour(NULL),'; width:219px;">
<a href="?rss=',$item['id'],'">
<table id="Table_01" width="219" border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="75" rowspan="4"><img src="system/img/tag/tag_01.png" alt="" width="75" height="70" border="0" id="tag_01" /></td>
<td colspan="2"><img src="system/img/tag/tag_02.png" alt="" width="121" height="7" border="0" id="tag_02" /></td>
<td width="23" rowspan="4"><img src="system/img/tag/tag_03.png" alt="" width="23" height="70" border="0" id="tag_03" /></td>
</tr>
<tr>
<td width="1"><img src="system/img/general/spacer.gif" alt="" name="tag_" width="1" height="26" border="0" id="tag_" /></td>
<td width="120" align="center" valign="middle" class="tagTitle">',$item['title'],'</td>
</tr>
<tr>
<td colspan="2"><img src="system/img/tag/tag_05.png" alt="" width="121" height="6" border="0" id="tag_05" /></td>
</tr>
<tr>
<td height="31" colspan="2" bgcolor="#FFFFFF"><img src="system/img/tag/tag_06.png" width="121" height="31" border="0" /></td>
</tr>
</table>
</a>
</div>';
	}

?>
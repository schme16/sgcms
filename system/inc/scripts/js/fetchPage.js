/*
	This fetches pages using the A.J.A.X Protocols outlined in the open-source, cross-browser, XMLHttpRequest Google Code Group
	Project Homepage: http://code.google.com/p/xmlhttprequest/
*/
function fetchPage( page ){

	//This is the shorthand for the content panel inner HTML
	var conPanel = document.getElementById( 'contentPanel' );
	
	if( page != null && isObject( conPanel ) )
	{
	
	//Checks if theres a current request and aborts it;
		if( isObject( fetchProto ) )
		{ 
			fetchProto.abort()
		}
		
	//Creates the XMLHttpRequest object
		fetchProto = new XMLHttpRequest;
		fetchProto.onreadystatechange = function(){

		//Check is the 'readystate' is set to 'DONE'	
			if( this.readyState == XMLHttpRequest.DONE )
			{
				conPanel.innerHTML = this.responseText;
			}
		//...and if it's not we just throw in a loading image.	
			else
			{
				conPanel.innerHTML = '<div align="center"><img src="system/img/commonImages/loading.gif" width="100" height="100" /></div>';
			}
		}
		var location = page+'&ajax=true';
		fetchProto.open( "GET", location, false );
		fetchProto.send( null );		
	}
}
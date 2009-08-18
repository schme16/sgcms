//WYSIWYG Editor



/*This allows for cross browser support.*/
function browserCompat(id)
{
	if (document.getElementById(id).contentDocument)//W3C compliant (Mozilla, Firefox, ect.)
		{
			return document.getElementById(id).contentDocument;
		} 
		
	else  //Internet Explorer
		{
			return document.frames[id].document;
		}
}

wysiwygElement = '';

/*This is the actual Iframes code*/
function wysiwyg(parent, id, width, height, border, toolbox)
{
	wysiwygElement = id;

//Have a toolbox present?
	if(toolbox)
		{
			parent.innerHTML = '<div id="'+id+'_toolbox" name="'+id+'_toolbox"></div>';

			var xmlhttp	= new XMLHttpRequest;
				xmlhttp.onreadystatechange	= function() {
					if(this.readyState == XMLHttpRequest.DONE)
					{
						document.getElementById(id+'_toolbox').innerHTML = xmlhttp.responseText;
						
					}
				}
			
			xmlhttp.open("GET", 'system/inc/js/toolbox.html?&'+getEpoch(), true);
			xmlhttp.send(null);
		}


//CSS Styles
	var styles = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml"><head><link href="system/css/stylesheet-1.css" rel="stylesheet" type="text/css" /><style> body{	background: #ffffff;	color:#000000; 	font-size: 12px;}</style></head><body></body></html>';


//Create the editor

	if(document.createElement && (iframeElement = document.createElement('iframe'))) 
	{
		iframeElement.id = id;
		iframeElement.name = id;
		iframeElement.style.width = width;
		iframeElement.style.height = height;
		iframeElement.style.border = border;		
		parent.appendChild(iframeElement);

		var iframeWin = window.frames[id];
		
		if(iframeWin) 
		{
			iframeWin.document.open();
			iframeWin.document.write(styles);
			iframeWin.document.close();
			iframeWin.document.designMode = 'on';
			iframeWin.focus();
		}
	}


}




function getSelText(id)
{
	if (browserCompat(id).getSelection)//Firefox 2+ (perhaps even before that, but untested)
		{
			return(browserCompat(id).getSelection()); 
		}
		
	else if (browserCompat(id).getSelection)//Assumed to be safari or netscape
		{
			return(browserCompat(id).getSelection()); 
		}
		
	else if (browserCompat(id).selection)//IE 5.5+
		{
			return(browserCompat(id).selection.createRange().text); 
		}
		
	else return;
}




/*From here on the functions are for text alteration or retreival*/
function textAlter( id, action)
{
	 browserCompat(id).execCommand(action, false, null);
}

function CopyToClipboard(id)
{
   CopiedTxt = getSelText(id);
   CopiedTxt.execCommand("Copy");
}

function getInnerText(from, to, copy)
{
	if(to != null && copy)
		{	
			document.getElementById(to).innerHTML = browserCompat(from).body.innerHTML;
		}
		
	else
		{
			return(browserCompat(from).body.innerHTML);
		}
}

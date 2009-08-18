// JavaScript Document

function checkFields(array){
	x=0;
	t = '';
	for (key in array)
	{

		if(array[key] == '' || array[key] == ' ' || array[key] == null || array[key] == '<br>'  )
		{
			t += key+'\r\n';
			x++;
		}
	}

	if(x > 0)
	{
		alert('The Following Fields Are Empty!:\r\n'+t);
		return false;
	}
	
	else
	{
		return true;
	}
}
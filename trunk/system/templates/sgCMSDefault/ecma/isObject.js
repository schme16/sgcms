// Determins if an 'variable' is an Object
function isObject(x){
	if(null !== x && 'object' == typeof(x))
	{
		return true;
	}
	else
	{
		return false;
	}
}
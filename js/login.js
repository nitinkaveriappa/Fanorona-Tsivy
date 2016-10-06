// JavaScript Document
function errCheck()
{
	var url = document.URL;
	unescape(url);
	if(url.search('\\?type=err')>0)
	{
		document.getElementById('errmsg').innerHTML="INVALID USER NAME OR PASSWORD";
	}
	else
	{
		document.getElementById('errmsg').innerHTML="";
	}
	
}
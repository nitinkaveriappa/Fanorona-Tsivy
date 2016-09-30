// JavaScript Document
function errCheck()
{
	var url = document.URL;
	unescape(url);
	if(url.search('\\?type=err')>0)
	{
		document.getElementById('errmsg').removeAttribute('hidden');
	}
	else
	{
		document.getElementById('errmsg').setAttribute('hidden','on');
	}
	
}
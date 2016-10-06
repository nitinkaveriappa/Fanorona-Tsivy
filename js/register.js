// JavaScript Document

var echeck;
function validate()
{
	var pcheck = pvalidator();
	if(echeck && pcheck)
	 return true;
	else 
	 return false;
}
	
function pvalidator()		//Verify if password enter is the same
{
	var p1 = document.getElementsByName('rMemPassword').item(0).value;
	var p2 = document.getElementsByName('rMemPasswordVerify').item(0).value;
	if(p1 == p2)
	{
		return true;
	}else
	{  	
		alert("The passwords do not match");
		return false;
	}
}
function EmailChecking(mailID)			//Check if mail id is already in use
{
	var AjaxReq = new XMLHttpRequest();
	AjaxReq.onreadystatechange = function(){
		if(AjaxReq.readyState==4){
			var res = AjaxReq.responseText;
			if(res != ""){ 
				document.getElementById('EmailExist').innerHTML = res;
				echeck = false;
			}
			else{
				document.getElementById('EmailExist').innerHTML = "";
				echeck = true;
			}
		}
	}
	AjaxReq.open("GET","idexists.php?m="+mailID ,true);
	AjaxReq.send();

}
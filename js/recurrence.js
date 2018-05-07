jQuery(document).ready(function($){
	console.log('Carrito');
	var cookieR=[];
	jQuery('.frecuencia').each(function(i,val){
		console.log(i);
		console.log(jQuery(this).attr('data-kitId'));
		var objR= [{"kitId":jQuery(this).attr('data-kitId'),"frecuencia":jQuery(this).val()}];
		cookieR.push(objR);
	});
	cookieR = JSON.stringify(cookieR);
	setCookie('carritoR',cookieR,15);
});


function setCookie(cname, cvalue, exdays) {
   var d = new Date();
   d.setTime(d.getTime() + (exdays*24*60*60*1000));
   var expires = "expires="+ d.toUTCString();
   document.cookie = cname + "=" + btoa(cvalue) + ";" + expires + ";path=/";
}

function getCookie(cname) {
   var name = cname + "=";
   var decodedCookie = decodeURIComponent(document.cookie);
   var ca = decodedCookie.split(';');
   for(var i = 0; i <ca.length; i++) {
       var c = ca[i];
       while (c.charAt(0) == ' ') {
           c = c.substring(1);
       }
       if (c.indexOf(name) == 0) {
           return atob(c.substring(name.length, c.length));
       }
   }
   return "";
}
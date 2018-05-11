jQuery(document).ready(function($){
	console.log('Carrito');
	var cookieR=[];
	jQuery('.btn-comprar').click(function(r){
		//console.log('Le dieron click');
		jQuery('.frecuencia').each(function(i,val){
			var nodeID=[];
			jQuery(this).parent().parent().prev().find('li').each(function(e,node){
				console.log(jQuery(this).find('figure').attr('data-nId'));
				nodeID.push(jQuery(this).find('figure').attr('data-nId'));
			});
			//console.log(nodeID);
			var objR= {"kitId":jQuery(this).attr('data-kitId'),'productId':nodeID,"frecuencia":jQuery(this).val()};
			cookieR.push(objR);
		});
		cookieR = JSON.stringify(cookieR);
		setCookie('carritoR',cookieR,3200);
	});
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
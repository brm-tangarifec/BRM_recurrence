jQuery(document).ready(function($){
	console.log('Carrito');
	var cookieR=[];
	jQuery('.btn-comprar').click(function(r){
		//console.log('Le dieron click');
		if(jQuery('.frecuenciaArma').length>0){
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
	}
	if(jQuery('.frecuenciaArma').length>0){
		jQuery('.frecuenciaArma').each(function(i,val){
		var objRAk= {"kitId":jQuery(this).attr('data-kitid'),'productId':jQuery(this).parent().prev().prev().attr('data-nidpr'),"frecuencia":jQuery(this).val()};
		//jQuery(this).parent().parent().prev().find('li').each(function(e,node){
			//console.log(jQuery(this).find('figure').attr('data-nId'));
			//nodeID.push(jQuery(this).find('figure').attr('data-nId'));
		//});
		//var objRAk= {"kitId":jQuery(this).attr('data-kitId'),'productId':nodeID,"frecuencia":jQuery(this).val()};
		console.log(objRAk);
		cookieR.push(objRAk);
		});
	}

		cookieR = JSON.stringify(cookieR);
		setCookie('carritoR',cookieR,3200);
	});

	//función para obtener la frecuencia por producto
	var cookieAr=getCookie('anonimocartAk');
	//Leer la cookie
	if(cookieAr!='empty' && cookieAr!=''){
	  cookieAr=JSON.parse(cookieAr);
	  if (cookieAr!=undefined) {
	    var find = false;
	    jQuery.each(cookieAr,function(e,value){
	    	console.log(value);
	    	//jQuery.each(value, function(key, value2){
	    		var nidPak='pid-'+value.productId;
	    		console.log(nidPak);
	    		jQuery('#'+nidPak).change().val(value.frecuencia);
	    	//});
	    });
	   /*for(var i = 0 ; i < l ; i++){
	   	console.log(cookieAr);
	      if(cookie.data[i].kitId==kitid){
	        cookie.data[i].frecuencia=frecu;
	        find = true;
	        break;
	      }
	    }
	    /*if (!find) {
	        var obj = {"kitId":kitid,"frecuencia":frecu};
	      cookie.data.push(obj);
	    }
	    cookie = JSON.stringify(cookie);
	    setCookie('anonimocart',cookie,15);*/
	  }
	}

	//Comprobar franquicia

jQuery('#numtc').blur(function(r){
	var numtc=jQuery('#numtc').val();
	if(numtc.length>10){
		jQuery.ajax({
		type: "POST",
		url: "/cart/checkccf",
		data:{
		  consul:numtc,
		  vartC: 'verify' 
		},

			success: function(data){
			  console.log(data);      
			  
			}
		});
	}
});

//No pasar de acá
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
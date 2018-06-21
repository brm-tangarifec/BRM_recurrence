jQuery(document).ready(function($){
	//console.log('Carrito');
	var cookieR=[];
	var banderaRecurrencia='N'; 
	jQuery('.btn-comprar').click(function(r){
		//console.log('Le dieron click');
	if(jQuery('.frecuencia').length>0){
		jQuery('.frecuencia').each(function(i,val){
			var nodeID=[];
			jQuery(this).parent().parent().prev().find('li').each(function(e,node){
				console.log(jQuery(this).find('figure').attr('data-nId'));
				nodeID.push(jQuery(this).find('figure').attr('data-nId'));
			});
			//console.log(nodeID);
			var objR= {"kitId":jQuery(this).attr('data-kitId'),'productId':nodeID,"frecuencia":jQuery(this).val()};
			cookieR.push(objR);

			if(jQuery(this).val()>0 ){
				banderaRecurrencia='S'; 
			}
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
		//console.log(objRAk);
		cookieR.push(objRAk);
		if(jQuery(this).val()>0){
			banderaRecurrencia='S'; 
		}
		});
	}

		if(banderaRecurrencia=='S'){
			cookieR = JSON.stringify(cookieR);
			setCookie('carritoR',cookieR,3200);
		}
	});

	//función para obtener la frecuencia por producto
	var cookieAr=getCookie('anonimocartAk');
	//Leer la cookie
	if(cookieAr!='empty' && cookieAr!=''){
	  cookieAr=JSON.parse(cookieAr);
	  if (cookieAr!=undefined) {
	    var find = false;
	    jQuery.each(cookieAr,function(e,value){
	    	//console.log(value);
	    	//jQuery.each(value, function(key, value2){
	    		var nidPak='pid-'+value.productId;
	    		//console.log(nidPak);
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
			  //console.log(data);      
			  jQuery('#franquicia').val(data);
			}
		});
	}
});
jQuery('.addcouponcustom').click(function(r){
	//console.log('Hola le di click');
	r.preventDefault();
	var coupon=jQuery('#cuponUser').val();
	if(coupon!=''){
		console.log(coupon);
		jQuery.ajax({
		type: "POST",
		url: "/cart/recurrence/addCoupon",
		data:{
		  consul:coupon,
		  vartC: 'addcp' 
		},

			success: function(data){
			  console.log(data);
			  if(data.title!='Desconocido'){
			  	jQuery('.titleCoupon').text(data.title);
			  	jQuery('#cuponUser').hide();
			  }
			  if(data.descuento!=0){
			  	jQuery('.descuentoCupon').text('');
			  	jQuery('.descuentoCupon').text(data.descuento);
			  }
			  jQuery('.help-block').html(data.mensaje);
			}
		});
	}
});

//Función para traer las direcciones
jQuery('#addressR').change(function(e){
	//console.log('Hola le di click');
	e.preventDefault();
	var idAdd=jQuery('#addressR').val();
	if(idAdd!=''){
		console.log(idAdd);
		jQuery.ajax({
		type: "POST",
		url: "/cart/recurrence/getAddress",
		data:{
		  consul:idAdd,
		  vartC: 'addr' 
		},
		success: function(data){
			console.log(data);
			//setTimeout(function(){
				jQuery.each(data,function( index, value ){
					console.log(value.delivery_first_name);
					//console.log(jQuery('#nombreEntrega').val());
					jQuery('#nombreEntrega').val(value.delivery_first_name);
					jQuery('#apellidoEntrega').val(value.delivery_last_name);
					jQuery('#direccionEntrega').val(value.delivery_street1);
					jQuery('#telefonoEntrega').val(value.delivery_phone);
					jQuery('#nombreFacturacion').val(value.billing_first_name);
					jQuery('#apellidoFacturacion').val(value.billing_last_name);
					jQuery('#direccionFacturacion').val(value.billing_street1);
					jQuery('#telefonoFacturacion').val(value.billing_phone);
				});
			//},2000);
		}
	});
	}
});
//Se activan la tarjeta registrada
jQuery('.tarjetaR').click(function(l){
	l.preventDefault();
	var tActive=jQuery(this).attr('data-reference');
	console.log(tActive);
	jQuery('#reference-c').val(tActive);
	jQuery('.datoshabiente').addClass('hidden');
});

//Pasar datos si está deschekeado mi direccion de facturación es la misma
jQuery('#facturacion').change(function(){
  if(jQuery(this).prop("checked")) {
    jQuery('.facturacionForm').addClass('hidden');
  } else {
    jQuery('.facturacionForm').removeClass('hidden');
  }
});


jQuery('#nombreEntrega').blur(function(){
	jQuery('#nombreFacturacion').val(jQuery('#nombreEntrega').val());
});
jQuery('#apellidoEntrega').blur(function(){
	jQuery('#apellidoFacturacion').val(jQuery('#apellidoEntrega').val());
});
jQuery('#direccionEntrega').blur(function(){
	jQuery('#direccionFacturacion').val(jQuery('#direccionEntrega').val());
});
jQuery('#telefonoEntrega').blur(function(){
	jQuery('#telefonoFacturacion').val(jQuery('#telefonoEntrega').val());
});
/*if(window.location.href.indexOf('recurrence') > -1){
	setTimeout(function(){
		console.log('Se recarga'); 
		jQuery("#subtotalPrice").load(location.href+ ' #subtotalPrice',"");
		jQuery("#subtotalPrice2").load(location.href+ ' #subtotalPrice2',"");
	}, 500);
	
}*/
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
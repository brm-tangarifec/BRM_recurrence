<?php

$orderRecurrence=$variables['carrito'];
$userR=user_load($orderRecurrence->uid);

// drupal_add_js(base_path() . path_to_theme() . '/js/libs/jquery.validate.js', array( 'scope' => 'footer', 'weight' => 5 , 'group' => JS_LIBRARY, 'preprocess' => FALSE));
// drupal_add_js(base_path() . path_to_theme() . '/js/brm.feedback.js', array( 'scope' => 'footer', 'weight' => 6 , 'group' => JS_LIBRARY, 'preprocess' => FALSE));

for ($i=0; $i <count($orderRecurrence->line_items) ; $i++) { 
	if($orderRecurrence->line_items[$i]['type']=='subtotal'){
		$subtotal=$orderRecurrence->line_items[$i]['amount'];		
	}
	if($orderRecurrence->line_items[$i]['type']=='tax'){
		$taxTitle=$orderRecurrence->line_items[$i]['title'];
		$ivamout=$orderRecurrence->line_items[$i]['amount'];
	}
	//printVar($orderRecurrence->line_items[$i]);
}
if($subtotal==0){
	header("Refresh:0");
}

//die();

 //función para calcular el costo del envío
 //printVar($orderRecurrence->order_id,'Se consulta');
$envio=shippingRecurrence($variables['idKits'],$subtotal);
$descuento=getCuponFromOrder($orderRecurrence->order_id);
//Se agrega el Valor del envío a la orden
updateLineShipping($orderRecurrence->order_id,$envio);
?>
<script type="text/javascript" src="https://maf.pagosonline.net/ws/fp/tags.js?id=${deviceSessionId}80200"></script> <noscript> <iframe style="width: 100px; height: 100px; border: 0; position: absolute; top: -5000px;" src="https://maf.pagosonline.net/ws/fp/tags.js?id=${deviceSessionId}80200"></iframe> </noscript>
<div class="row">
	<form action="/cart/recurrence/checkout-recurrence" class="uc-cart-checkout-form" method="POST" name='recurrenceForm' id="recurrenceForm">

		<fieldset class="form-wrapper">
			<legend >Resumen de Canasta</legend>

			<!-- Resumen de canasta -->
			<div class="fieldset-wrapper"><table class="cart-review sticky-enabled">
			 <thead><tr><th class="qty"><abbr title="Quantity">Cantidad</abbr></th><th class="products">Productos</th><th class="price">Precio</th> </tr></thead>
			<tbody>
			<?php
			foreach ($orderRecurrence->products as $key => $productos) { ?>
				<tr class="odd"><td class="qty"><?php print($productos->qty);?> ×</td><td class="products"><a href="<?php print(drupal_get_path_alias('node/'.$productos->nid)); ?>"><?php print($productos->title);?></a></td><td class="price"><span class="uc-price"><?php print(uc_currency_format($productos->price));?></span><span class="price-suffixes"></span></td> </tr>
			<?php }	?>
			 <tr class="subtotal odd"><td colspan="3" class="subtotal"><span id="subtotal-title">Subtotal:</span> <span class="uc-price" id="subtotalPrice"><?php  print(uc_currency_format($subtotal)); ?></span></td> </tr>
			</tbody>
			</table></div>
		</fieldset>
		<!--/- Resumen de canasta -->
		
		<!-- Info cliente -->
		<div class="form-wrapper">
			<legend >Información del cliente</legend>

			<p class="text-center">
				La información de la orden se enviará a su cuenta de correo electrónico que aparece a continuación.

			</p>
			<p class="text-center">
				<strong>Dirección de correo electrónico:</strong> <?php print($userR->mail);?> (<a href="/user/<?php print($orderRecurrence->uid)?>/edit">Editar</a>)
			</p>

		</div>
		<!--/- Info cliente -->

		<!-- Info de entrega -->
		<fieldset class="form-wrapper">
			<legend>Información de entrega</legend>
			<p class="text-center">Introduzca su dirección de entrega e información aquí</p>

			<div id="delivery-address-pane">
				<div class="uc-store-address-field form-wrapper" id="edit-panes-delivery-address"><div class="form-item form-type-textfield form-item-panes-delivery-delivery-first-name">
				  <label for="edit-panes-delivery-delivery-first-name"><span class="form-required" title="Este campo es obligatorio.">*</span> Nombre </label>
				 <input type="text" id="edit-panes-delivery-delivery-first-name" name="panes[delivery][delivery_first_name]" value="" size="32" maxlength="128" class="form-text">
				</div>
				<div class="form-item form-type-textfield form-item-panes-delivery-delivery-last-name">
				  <label for="edit-panes-delivery-delivery-last-name"><span class="form-required" title="Este campo es obligatorio.">*</span> Apellido </label>
				 <input type="text" id="edit-panes-delivery-delivery-last-name" name="panes[delivery][delivery_last_name]" value="" size="32" maxlength="128" class="form-text">
				</div><div class="clearfix"></div>
				<div class="form-item form-type-textfield form-item-panes-delivery-delivery-street1">
				  <label for="edit-panes-delivery-delivery-street1"><span class="form-required" title="Este campo es obligatorio.">*</span> Dirección </label>
				 <input type="text" id="edit-panes-delivery-delivery-street1" name="panes[delivery][delivery_street1]" value="" size="32" maxlength="128" class="form-text">
				</div>
				<div id="uc-store-address-delivery-zone-wrapper"><div class="form-item form-type-select form-item-panes-delivery-delivery-zone">
				  <label for="edit-panes-delivery-delivery-zone"><span class="form-required" title="Este campo es obligatorio.">*</span> Departamento </label>
				 <select id="edit-panes-delivery-delivery-zone" name="panes[delivery][delivery_zone]" class="form-select"><option value="0" selected="selected">- Seleccionar -</option><option value="92">Distrito Capital de Bogota</option></select>
				</div></div>
				<div class="clearfix"></div>
				<div class="form-item form-item-panes-delivery-delivery-city form-type-select">
				  <label for="edit-panes-delivery-delivery-city"><span class="form-required" title="Este campo es obligatorio.">*</span> Ciudad </label>
				 <select class="ciudadS form-select form-type-select" id="ciudadS"><option val="">--Seleccionar--</option>
				 	<option val="Bogotá">Bogotá</option></select><input type="text" id="edit-panes-delivery-delivery-city" name="panes[delivery][delivery_city]" value="Bogotá" size="32" maxlength="128" class="form-text hidden">
				</div>
				<div class="form-item form-type-select form-item-panes-delivery-delivery-country">
				  <label for="edit-panes-delivery-delivery-country"><span class="form-required" title="Este campo es obligatorio.">*</span> País </label>
				 <select id="edit-panes-delivery-delivery-country" name="panes[delivery][delivery_country]" class="form-select ajax-processed"><option value="170" selected="selected">Colombia</option></select>
				</div><div class="clearfix"></div>
				<div class="form-item form-type-textfield form-item-panes-delivery-delivery-phone">
				  <label for="edit-panes-delivery-delivery-phone">Teléfono </label>
				 <input type="text" id="edit-panes-delivery-delivery-phone" name="panes[delivery][delivery_phone]" value="" size="16" maxlength="32" class="form-text">
				</div>
				</div>
			</div>
		</fieldset>
		<!--/- Info de entrega -->


		<!-- Datos de facturación -->
		<fieldset class="form-wrapper" id="billing-pane"><legend><span class="fieldset-legend">Datos de facturación</span></legend><div class="fieldset-wrapper"><div class="fieldset-description">Ingrese su dirección de facturación e información aquí.</div><div class="form-item form-type-checkbox form-item-panes-billing-copy-address active">
		 <input type="checkbox" id="edit-panes-billing-copy-address" name="panes[billing][copy_address]" value="1" checked="checked" class="form-checkbox ajax-processed">  <label class="option" for="edit-panes-billing-copy-address">Mi información de facturación es la misma que mi información de entrega. </label>

		</div>
		<!-- Panel que se muestra cuando la información de facturación es diferente -->
		<div id="billing-address-pane"><div></div><div class="uc-store-address-field form-wrapper hidden" id="edit-panes-billing-address--2"><div class="form-item form-type-textfield form-item-panes-billing-billing-first-name">
		  <label for="edit-panes-billing-billing-first-name"><span class="form-required" title="Este campo es obligatorio.">*</span> Nombre </label>
		 <input type="text" id="edit-panes-billing-billing-first-name" name="panes[billing][billing_first_name]" value="" size="32" maxlength="128" class="form-text">
		</div>
		<div class="form-item form-type-textfield form-item-panes-billing-billing-last-name">
		  <label for="edit-panes-billing-billing-last-name"><span class="form-required" title="Este campo es obligatorio.">*</span> Apellido </label>
		 <input type="text" id="edit-panes-billing-billing-last-name" name="panes[billing][billing_last_name]" value="" size="32" maxlength="128" class="form-text">
		</div><div class="clearfix"></div>
		<div class="form-item form-type-textfield form-item-panes-billing-billing-street1">
		  <label for="edit-panes-billing-billing-street1"><span class="form-required" title="Este campo es obligatorio.">*</span> Dirección </label>
		 <input type="text" id="edit-panes-billing-billing-street1" name="panes[billing][billing_street1]" value="" size="32" maxlength="128" class="form-text">
		</div>
		<div id="uc-store-address-billing-zone-wrapper"><div class="form-item form-type-select form-item-panes-billing-billing-zone">
		  <label for="edit-panes-billing-billing-zone"><span class="form-required" title="Este campo es obligatorio.">*</span> Departamento </label>
		 <select id="edit-panes-billing-billing-zone" name="panes[billing][billing_zone]" class="form-select"><option value="0" selected="selected">- Seleccionar -</option><option value="92">Distrito Capital de Bogota</option></select>
		</div><div class="clearfix"></div>
		</div><div class="form-item form-item-panes-billing-billing-city form-type-select">
		  <label for="edit-panes-billing-billing-city"><span class="form-required" title="Este campo es obligatorio.">*</span> Ciudad </label>
		 <select class="ciudadSE form-select form-type-select" id="ciudadSE"><option val="">--Seleccionar--</option></select><input type="text" id="edit-panes-billing-billing-city" name="panes[billing][billing_city]" value="BOGOTA D.C." size="32" maxlength="128" class="form-text hidden">
		</div>
		<div class="form-item form-type-select form-item-panes-billing-billing-country">
		  <label for="edit-panes-billing-billing-country"><span class="form-required" title="Este campo es obligatorio.">*</span> País </label>
		 <select id="edit-panes-billing-billing-country" name="panes[billing][billing_country]" class="form-select ajax-processed"><option value="170" selected="selected">Colombia</option></select>
		</div><div class="clearfix"></div>
		<div class="form-item form-type-textfield form-item-panes-billing-billing-phone">
		  <label for="edit-panes-billing-billing-phone">Teléfono </label>
		 <input type="text" id="edit-panes-billing-billing-phone" name="panes[billing][billing_phone]" value="" size="16" maxlength="32" class="form-text">
		</div>
		</div></div>
		<!-- Panel que se muestra cuando la información de facturación es diferente -->


	</fieldset>
		<!--/- Datos de facturación -->

		<!-- Frecuencia -->
		<fieldset class="form-wrapper" id="recurrencia">

			<legend>Frecuencia de pago</legend>

			<div class="wrap-tarjetas">
				<h3 class="text-center">Tarjetas vinculadas</h3>
				<div class="col-md-4 col-md-offset-4">
					<!-- Acordeon tarjetas -->
					<div class="panel-group" id="accordionCard" role="tablist" aria-multiselectable="true">
					  <!--Desde acá-->
					  <?php
					  $getCard=getDataTokerPerUser($userR->uid);
					  //printVar($getCard);
					  foreach ($getCard as $key => $card) {
					  	printVar($card);
					  	$franquicia=$card['franchise'];
					  	$carMask=substr($card['card_mask'],-4);
					  	?>
					  	<div class="panel panel-default">
					    <div class="panel-heading tarjetaR" role="tab" id="heading<?php print($card['reference']);?>" data-reference="<?php print($card['reference']);?>">
					      <h4 class="panel-title">
					        <a class="collapsed">
					          <img src="<?php print(base_path().path_to_theme());?>/images/tarjetas/<?php print(strtolower($franquicia))?>.jpg" alt="Visa">
					          <?php print(ucfirst($franquicia))?> terminada en <?php print($carMask)?>
					        </a>
					      </h4>
					    </div>
					    
					  </div>
					  <?php }  ?>
					  <!--Hasta acá-->
					  <input type="hidden" name="reference-c" id="reference-c" value="">
					</div>
					<!--/- Acordeon tarjetas -->
				</div>
			
			</div>

			<div class="clearfix"></div>
			<div class="datoshabiente">
			<h3 class="text-center">
				Agrega una nueva Frecuencia de Pago
			</h3 class="center">

			<div class="fiedset-wrapper">
				
				<div class="form-item">
					<label for="franquicia">Nombre Tarjetahabiente</label>
					<input type="text" id="tarjetahabiente" name="tarjetahabiente">
				</div>
				<div class="form-item">
					<label for="franquicia">Cédula Tarjetahabiente</label>
					<input type="text" id="cedulahabiente" name="cedulahabiente">
				</div>
				<div class="form-item">
					<label for="franquicia">Cuotas</label>
					<input type="number" name="cuotas" step="1" min="1" max="36">
				</div>
				<div class="form-item">
					<label for="franquicia">Franquicia</label>
					<input type="text" id="franquicia" name="franquicia">
				</div>

				<div class="form-item">
					<label for="numtc">Número de Tarjeta de Crédito</label>
					<input type="num" id="numtc" name="numtc">
				</div>

				<div class="form-item form-fecha-tc">
					<label for="ven">Fecha de Vencimiento</label>
					<div class="clearfix"></div>
					<input class="form-tc" type="datetime" maxlength="2" id="mm" name="mm" placeholder="mm" pattern="[0-9]{2}">
					<input class="form-tc" type="num"  maxlength="4" id="aaaa" name="aaaa"  placeholder="aaaa" pattern="[0-9]{4}">
					<div class="clearfix"></div>
				</div>
				<div class="clearfix"></div>

				<div class="form-item push-half">
					<label for="cosec">Código de seguridad <small>(Es el código que se encuentra al respaldo de tu tarjeta de crédito)</small></label>
					<input type="num" id="cosec" name="cosec" maxlength="3"  pattern="[0-9]{3}" placeholder="123">

				</div>

				<div class="clearfix"></div>

			</div>
		</div>

		</fieldset>
		<!--/- Frecuencia -->

		<!-- Cupon -->
		<fieldset class="form-wrapper" id="coupon-pane"><legend><span class="fieldset-legend">Cupón de descuento</span></legend><div class="fieldset-wrapper"><div class="fieldset-description">Ingrese un cupón para este pedido</div><div class="form-item form-type-textfield form-item-panes-coupon-code">
		  <label for="edit-panes-coupon-code">Código del cupón  </label>
		 <input type="text" id="cuponUser" name="cuponUser" value="" size="25" maxlength="128" class="form-text">
		<div class="description"><span class="titleCoupon"></span><span class="messageCoupon">Ingresa un código de cupón y haz clic en "Aplicar al pedido" a continuación.</span></div>
		</div>
		<input type="button" id="aplicacupon" name="aplicacupon" value="Aplicar al pedido" class="addcouponcustom"><div id="coupon-messages"></div></div></fieldset>
		<!--/- Cupon -->

		<!-- Costo de envio -->
		<fieldset class="form-wrapper hidden" id="quotes-pane"><legend><span class="fieldset-legend">Calcular costo de envío</span></legend><div class="fieldset-wrapper"><div class="fieldset-description">Las cotizaciones de envío se generan automáticamente cuando se introduce su dirección y se puede actualizar manualmente con el botón de abajo.</div><input type="hidden" name="panes[quotes][uid]" value="2">
		<input type="button" id="edit-panes-quotes-quote-button" name="op" value="Calcular costo de envío" class="form-submit ajax-processed" style="background-color: rgb(27, 158, 173); color: white; text-transform: uppercase; padding: 10px 20px; border-radius: 6px;"><div id="quote" style="display: none;"><div class="form-item form-type-radio form-item-panes-quotes-quotes-quote-option active">
		 <input type="radio" id="edit-panes-quotes-quotes-quote-option-flatrate-6-0" name="panes[quotes][quotes][quote_option]" value="flatrate_6---0" checked="checked" class="form-radio ajax-processed">  <label class="option" for="edit-panes-quotes-quotes-quote-option-flatrate-6-0">Envío: $0 </label>

		</div>
		<input type="hidden" name="panes[quotes][quotes][flatrate_6---0][rate]" value="0">
		<div class="form-item form-type-radio form-item-panes-quotes-quotes-quote-option active">
		 <input type="radio" id="edit-panes-quotes-quotes-quote-option-flatrate-11-0" name="panes[quotes][quotes][quote_option]" value="flatrate_11---0" class="form-radio ajax-processed">  <label class="option" for="edit-panes-quotes-quotes-quote-option-flatrate-11-0">Envío: $0 </label>

		</div>
		<input type="hidden" name="panes[quotes][quotes][flatrate_11---0][rate]" value="0">
		<div id="edit-panes-quotes-quotes-quote-option" class="form-radios"></div></div></div></fieldset>
		<!--/- Costo de envio -->

		<!-- Metodo de pago -->
		<fieldset class="form-wrapper" id="payment-pane"><legend><span class="fieldset-legend">Método de pago</span></legend><div class="fieldset-wrapper"><div id="line-items-div"><table id="uc-order-total-preview" class="modificadaj"><tbody><tr class="line-item-subtotal"><td class="title">Subtotal:</td> <td class="price"><span class="uc-price" id="subtotalPrice2"><?php print(uc_currency_format($subtotal)); ?></span></td></tr><tr class="line-item-tax"><td class="title"><?php print($taxTitle); ?>:</td><td class="price"><span class="uc-price"><?php  print(uc_currency_format($ivamout)); ?></span></td></tr><tr class="line-item-total"><td class="title">Total productos:</td><td class="price"><span class="uc-price"><?php $totalSinEnvio=(float)$subtotal+(float)$ivamout; print(uc_currency_format($totalSinEnvio))?></span></td></tr><tr><td class="title">Envío:</td><td class="price"><span class="uc-price"><?php print(uc_currency_format($envio));?></span></td></tr><tr><td class="title">Cupón:</td><td class="price"><span class="uc-price descuentoCupon"><?php print(uc_currency_format($descuento));?></span></td></tr><tr><td class="title">Total:</td><td class="price"><span class="uc-price"><?php print(uc_currency_format($orderRecurrence->order_total)); ?></span></td></tr></tbody></table></div><div class="form-item form-type-radios form-item-panes-payment-payment-method form-disabled">
		  <label class="element-invisible" for="edit-panes-payment-payment-method">Método de pago <span class="form-required" title="Este campo es obligatorio.">*</span></label>
		 <div id="edit-panes-payment-payment-method" class="form-radios"><div class="form-item form-type-radio form-item-panes-payment-payment-method form-disabled active">
		 <input disabled="disabled" type="radio" id="edit-panes-payment-payment-method-payulatam" name="panes[payment][payment_method]" value="payulatam" checked="checked" class="form-radio ajax-processed">  <label class="option" for="edit-panes-payment-payment-method-payulatam"><img src="/sites/all/modules/uc_payulatam/img/logopayulatam.png" alt="PayU Latam"> </label>

		</div>
		</div>
		</div>
		<div id="payment-details" class="clearfix payment-details-payulatam">Continúe con el proceso para completar el pago.</div></div></fieldset>
		<!--/- Metodo de pago -->
		<input type="hidden" name="form_build_id" value="form-N0ZAGGLc18xkBOWEFGAEeYF-glgZKQ1QwnCl-v_2ttU">
		<input type="hidden" name="form_token" value="F5TaOhXZOyJIj406CDF3oLi0IlvqijKfO2KeWG1KJeM">
		<input type="hidden" name="form_id" value="uc_cart_checkout_form">
		<div class="form-actions form-wrapper" id="edit-actions"><input type="submit" id="edit-cancel" name="op" value="Cancelar" class="form-submit"><input type="submit" id="edit-continue" name="op" value="Ver orden" class="form-submit" style="background-color: rgb(27, 158, 173); color: white; text-transform: uppercase; padding: 10px 20px; border-radius: 6px;"></div>



		

	</form>
	
</div>

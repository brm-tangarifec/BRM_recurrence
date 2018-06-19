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
<!--Nuevo-->
<div class="container">
		<div class="row">
			<div class="cont">
				<legend class="text-title-recurrence">CANASTA</legend>
				<table class="table table-striped">
					<thead>
						<tr>
							<th>Cantidad</th>
							<th>Productos</th>
							<th>Precios</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($orderRecurrence->products as $key => $productos) { ?>
						<tr>
							<td><?php print($productos->qty);?> ×</td>
							<td>
								<a href="<?php print(drupal_get_path_alias('node/'.$productos->nid)); ?>"><?php print($productos->title);?></a>
							</td>
							<td><?php print(uc_currency_format($productos->price));?></td>
						</tr>
						<?php }	?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
				
	<!-- Info cliente -->
	<div class="container">
		<div class="row">
			<div class="cont text-center">
				<legend class="text-title-recurrence">Información del cliente</legend>
				<p>
					La información de la orden se enviará a su cuenta de correo electrónico que aparece a continuación.
				</p>
				<p>
					<strong>Dirección de correo electrónico:</strong> <?php print($userR->mail);?> (<a href="/user/<?php print($orderRecurrence->uid)?>/edit">Editar</a>)

				</p>
			</div>			
		</div>
	</div>
	<!--/- Info cliente -->

	<!-- Info de entrega -->
	<form action="/cart/recurrence/checkout-recurrence" class="uc-cart-checkout-form" method="POST" name='recurrenceForm' id="recurrenceForm">
	<div class="container">
		<div class="row">
			<fieldset class="cont text-center">
				<legend class="text-title-recurrence">Información de entrega</legend>
				<p>
					Introduzca su dirección de entrega e información aquí
				</p>

				<!-- Formulario 01 -->
				<form>
					<!-- Select one... -->
					<div class="row cont-form">
						<div class="form-group-select">
							<label for="exampleInputPassword1">Dirección Guardada</label>
							<div class="cont-select-dir">
								<select class="form-control-select">
									<option>- Selecct one -</option>
									<option>1</option>
								</select>								
							</div>
						</div>
					</div>
					<!-- Select one... -->
					<div class="row cont-form">
						<div class="form-group form-item">
							<label for="exampleInputEmail1">*Nombre</label>
							<input type="text" class="form-control-p" required/>
						</div>

						<div class="form-group">
							<label for="exampleInputPassword1">*Apellido</label>
							<input type="text" class="form-control-p" required/>
						</div>
					</div>

					<div class="row cont-form">            
						<div class="form-group">
							<label for="exampleInputPassword1">*Dirección</label>
							<input type="text" class="form-control-p" required/>
						</div>

						<div class="form-group">
							<label for="exampleInputPassword1">*Departamento</label>
							<div class="cont-select">
								<select class="form-control-p" required/>
									<option>- Seleccionar -</option>
									<option>1</option>
								</select>								
							</div>
						</div>
					</div>

					<div class="row cont-form">
						<div class="form-group">
							<label for="exampleInputPassword1">*Ciudad</label>
							<div class="cont-select">
								<select class="form-control-p" required/>
									<option>- Seleccionar -</option>
									<option>1</option>
								</select>								
							</div>
						</div>

						<div class="form-group">
							<label for="exampleInputPassword1">*Pais</label>
							<div class="cont-select">
								<select class="form-control-p" required/>
									<option>- Seleccionar -</option>
									<option>1</option>
								</select>								
							</div>
						</div>
					</div>

					<div class="row cont-form">
						<div class="form-group form-item">
							<label for="exampleInputEmail1">*Teléfono</label>
							<input type="tel" class="form-control-p" required/>
						</div>
					</div>
				</form>			
			</fieldset>
		</div>
	</div>
				<!--/- Formulario 01 -->
	<!--/- Info de entrega -->

	<!-- Datos facturación -->
	<div class="container">
		<div class="row">
			<fieldset class="cont text-center">
				<legend class="text-title-recurrence">DATOS DE FACTURACIÓN</legend>
				<p>
					Ingrese su dirección de facturación e información aquí.
				</p>
				<div class="row">
					<div class="checkbox">
					  <label>
					    <input type="checkbox" id="blankCheckbox" value="option1" aria-label="...">
					  </label>
					  <div class="text-check">
						  <p>
						  	Mi información de facturación es la misma que mi información de entrega.
						  </p>
					  </div>
					</div>
					
				</div>
				<!--/- Formulario 02 -->
				<!--/- Formulario 02 -->
			</fieldset>			
		</div>
	</div>
	<!--/- Datos facturación -->

	<!-- Cupón Descuento -->
	<div class="container">
		<div class="row">
			<fieldset class="cont text-center">
				<legend class="text-title-recurrence">CUPÓN DE DESCUENTO</legend>
				<p>
					Ingrese un cupón para este pedido.
				</p>
				<div class="row">
					<div class="cupon">
						<input type="text" class="form-control-p">
					</div>
				</div>
				<p class="help-block">
					Ingresa un código de cupón y haz clic en "Aplicar al pedido" a continuación.
				</p>
				<button type="submit" class="btn-recurrence">APLICAR AL PEDIDO</button>
			</fieldset>			
		</div>
	</div>
	<!--/- Cupón Descuento -->

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

	<!-- Metodo de pago -->
	<div class="container">
		<div class="row">
			<fieldset class="cont text-center">
				<legend class="text-title-recurrence">MÉTODO DE PAGO</legend>
				<table class="table table-bordered">
					<tbody>
						<tr>
							<td>Cupón:</td>
							<td><?php print(uc_currency_format($descuento));?></td>
						</tr>
						<tr>
							<td>Subtotal:</td>
							<td><?php print(uc_currency_format($subtotal)); ?></td>
						</tr>
						<tr>
							<td><?php print($taxTitle); ?>:</td>
							<td class="iva"><?php  print(uc_currency_format($ivamout)); ?></td>
						</tr>
						<tr>
							<td>Total productos:</td>
							<td><?php $totalSinEnvio=(float)$subtotal+(float)$ivamout; print(uc_currency_format($totalSinEnvio))?></td>
						</tr>
						<tr>
							<td>Envío:</td>
							<td><?php print(uc_currency_format($envio));?></td>
						</tr>
						<tr>
							<td>Total:</td>
							<td><?php print(uc_currency_format($orderRecurrence->order_total)); ?></td>
						</tr>
					</tbody>
				</table>
				<div class="cont-pago">
					<p class="text-dest">
						Método de pago *
					</p>
					<div class="cont-pasarela">
						<label>
							<input type="radio" name="optionsRadios" id="#" value="#" disabled checked>
						</label>
						<img src="images/logopayulatam.png">
					</div>
					<p>
						Continúe con el proceso para completar el pago.
					</p>
				</div>
			</fieldset>			
		</div>
	</div>
	<!--/- Metodo de pago -->

	<!-- Botones -->
	<div class="container">
		<div class="row">
			<fieldset class="cont-btns text-center">
				<button type="submit" class="btn-recurrence-dos">CANCELAR</button>
				<button type="submit" class="btn-recurrence-dos btn-active">VER ORDEN</button>
			</fieldset>			
		</div>
	</div>
</form>
	<!--/- Botones -->

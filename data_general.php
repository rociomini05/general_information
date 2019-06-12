<?$this->load->view("modal_msj/msj_advertencia_cambios");?>
<!-- JS para crear las mascaras en los input text-->
<script type="text/javascript" src="<?=base_url('plugins/form-inputmask/jquery.inputmask.bundle.min.js');?>"></script>	  <!-- Input Masks Plugin -->
<script type="text/javascript" src="<?=base_url('demo/demo-mask.js');?>"></script>

<!-- Definimos los valores del formulario para que pueda funcionar con la opcion Nuevo y la opcion Editar-->

<?
$inscripcion_dgr = '';
$cuit = '';
?>

<? if($accion == 'Nuevo'){
	$attributes = array('id' => 'formulario');
	echo form_open('documentos/guardar_nuevo',$attributes);
}?>

<? if($accion == 'Editar'){
	$attributes = array('id' => 'formulario');
	echo form_open('datos_generales/guardar_datos_generales',$attributes);
	//$var_nombre = $datos_revision->nombre;	
}
$active="";
if(!$permisos_especiales['editar_informacion'])
	$active=" disabled";
?>

<div class="page-heading">
	<h1><? if(isset($historial)) echo '<i class="fa fa-history fa-6" aria-hidden="true"></i> ';?><?=$empresa;?>
		<? if(isset($historial)) echo $fecha_formateada;?>
		<?php $this->load->view('referencias_nacionalidad');?>
		
	</h1>
</div>
<div class="referencias">
	<?php $this->load->view('referencias');?>
</div>

<?
	//var_dump($this->session->userdata('id_proveedores'));
	//var_dump($this->session->userdata('id_empresas'));
?>



<!-- Titulo del Formulario.
	El valor del titulo debe definirse desde el controlador que invoca la vista dentro de la variable $data['titulo'] -->
	<div class="container-fluid">
		<div class="row">
			<div class="col-xs-12">
				<!-- Mensaje de Confirmacion de Operacion -->
				<?if($this->session->flashdata('correcto')):?>
					<?$correcto = $this->session->flashdata('correcto');?>
					<div id="mensajeConfirmacion" class="alert <?=($correcto == true) ? 'alert-info':'alert-danger'; ?>">
						<i class="glyphicon glyphicon-<?=($correcto==true) ? 'info-sign':'remove-sign'?>"></i>
						&nbsp;&nbsp;
						<?= ($correcto == true)? 'El proceso finalizó de manera correcta':'Hubo un error en la solicitud procesada'; ?>
					</div>
					<?endif;?>

					<?if($datos_revision->estado_extra==0):?>
						<div class="alert alert-danger">
							El contratista se encuentra <strong>inhabilitado</strong> por lo siguiente:<br><?=str_replace(":","",$datos_revision->estado_extra_motivo)?>
							<a href="<?=base_url('empleados/listar')?>">Ir a empleados</a>
						</div>
						<?endif;?>
						<!-- Fin Mensaje Confirmacion -->
						<div class="panel panel-default">
							<?if($datos_revision->estado_admin==0):?>
								<div class="panel-heading" style="background-color: #FF0000;">
									<h5 style="color:#FFFFFF">CONTRATISTA INACTIVO</h5>
								</div>
								<?endif;?>
								<div class="panel-heading">
									<h3><?=$seccion;?> <?=$estado_seccion_general?></h3>
									<div class="options">

									</div>
								</div>
								<?=my_validation_errors(validation_errors()); ?>
								<div class="panel-body sin-padding-inferior">
									<!-- Si la accion es Editar, debemos recuperar el id del elemento a modificar -->
									<?php if($accion == 'Editar'){?>
									<input type="hidden" name="id_proveedores" id="id_proveedores" value="<?=$datos_revision->id_proveedores;?>" />

									<?php }?>
									<input type="hidden" name="tipo_empresa_old" id="tipo_empresa_old" value="<?=$datos_revision->tipo_empresa;?>" />
									<input type="hidden" name="estado_formulario" id="estado_formulario" value="<?=$datos_revision->estado_generales;?>" />					

									<!-- Campos del Formulario -->
									<div class="row">
										<? //if($datos_revision->lista_negra): ?>
							<!--<div class="col-md-12 form-group">
								<div class="alert alert-danger">
									<strong><span>Advertencia: El proveedor tiene prohibido el ingreso, por lo tanto no podrá hacer ingreso a planta, como así tampoco ninguno de sus empleados, vehículos o socios.</span></strong>
								</div>
							</div>-->
							<? //endif; ?>

							<? if($datos_revision->lista_negra && in_array($this->session->userdata('tipo_rol'), array('admin', 'controlador'))): ?>

								<div class="col-md-12">
									<span style="color: red;">ADVERTENCIA: El contratista tiene prohibido el ingreso, por lo tanto también sus empleados, vehículos y socios.</span><br><br>
								</div>

							<? endif; ?>

							<div class="col-md-4 form-group">
								<?
								$texto_cuil='CUIT';
								switch ($datos_revision->nacionalidad) {
								    case 'argentina': $texto_cuil='CUIT'; break;
								    case 'brasil': $texto_cuil='CNPJ'; break;
								    case 'chilena': $texto_cuil='RUT'; break;
								    case 'mexicana': $texto_cuil='RFC'; break;
								    case 'peru': $texto_cuil='RUC'; break;
								    case 'uruguay': $texto_cuil='RUT'; break;
								    case 'colombiana': $texto_cuil='NIT'; break;
								}
								?>
								<label><?= $texto_cuil; ?> (*)</label>
								<input class="form-control" id="cuit" name="cuit"  value="<?= set_value('cuit', $datos_revision->cuit);?>" disabled maxlength="100"/>
							</div>  

							<div class="col-md-4 form-group">
								<label>Nombre / Razón Social (*)</label>
								<input class="form-control" id="nombre_razon_social" name="nombre_razon_social" disabled value="<?= set_value('nombre_razon_social',$datos_revision->nombre_razon_social);?>" required/>
							</div>  

							<div class="col-md-4 form-group">
								<label>Nombre Comercial</label>
								<input class="form-control" <?=$active?> id="nombre_comercial" name="nombre_comercial"  value="<?= set_value('nombre_comercial',$datos_revision->nombre_comercial);?>"/>
							</div>



						</div>

						<div class="row">
							<div class="col-md-4 form-group">
								<label>Domicilio Legal (*)</label>
								<input class="form-control" <?=$active?> id="domicilio_legal" name="domicilio_legal"  value="<?= set_value('domicilio_legal',$datos_revision->domicilio_legal);?>" required/>
							</div>

							<div class="col-md-2 form-group">
								<label>Teléfono Fijo </label>
								<input type="text" <?=$active?> class="form-control mask" id="telefono_fijo" name="telefono_fijo" value="<?= set_value('telefono_fijo',$datos_revision->telefono_fijo);?>">
							</div>  

							<div class="col-md-2 form-group">
								<label>Teléfono Celular</label>
								<input type="text" <?=$active?> class="form-control mask" id="telefono_celular" name="telefono_celular" value="<?= set_value('telefono_celular',$datos_revision->telefono_celular);?>">
							</div>

							<div class="col-md-4 form-group">
								<label>Email (*)</label>
								<input type="email" <?=$active?> class="form-control" id="email" name="email"  value="<?= set_value('email',$datos_revision->email);?>" required/>
							</div>

						<!--<div class="col-md-4 form-group">
							<label>CBU </label>				 
							<input class="form-control" <?=$active?> id="cbu" name="cbu"  value="<?= set_value('cbu',$datos_revision->cbu);?>" />
						</div>-->

					</div>

					<div class="row">
						<div class="col-md-4 form-group">
							<label>Tipo de Empresa (*)</label>
							<select <?=$active?> name="tipo_empresa" onchange="cambiar_tipo_empresa(this.value)" required class="form-control" >
								<option value="">Seleccionar</option>
								<option value="fisica" <?php if($datos_revision->tipo_empresa == 'fisica')echo "selected";?>>Física</option>
								<option value="juridica" <?php if($datos_revision->tipo_empresa == 'juridica')echo "selected";?>>Jurídica</option>  
							</select>
						</div>	  

						<div class="col-md-4 form-group">
							<label>Nacionalidad (*)</label>
							<select <?=$active?> name="nacionalidad" required class="form-control" disabled>
								<option value="">Seleccionar</option>
								<option value="argentina" <?php if($datos_revision->nacionalidad == 'argentina')echo "selected";?>>Argentina</option>
								<option value="chilena" <?php if($datos_revision->nacionalidad == 'chilena')echo "selected";?>>Chile</option>  
							</select>
						</div>  
						<?if($this->funciones->puede_ver_modulo('29')){?>
						<div class="col-md-4 form-group">
							<label>Persona de Contacto (*)</label>
							<input type="text" <?=$active?> class="form-control" id="persona_contacto" name="persona_contacto"  value="<?= set_value('persona_contacto',$datos_revision->persona_contacto);?>" required/>
						</div>
						<?}?>

						<?if($this->funciones->puede_ver_modulo('32')){?>

						<div class="col-md-4 form-group">
							<label>Número de contrato (*)</label>
							<input type="text" class="form-control" value="<?= $datos_revision->nro_contrato; ?>" disabled/>
						</div>

						<div class="col-md-4 form-group">
							<label>Responsable del contrato (*)</label>
							<input type="text" class="form-control" value="<?= $datos_revision->responsable_contrato; ?>" disabled/>
						</div>

						<div class="col-md-4 form-group">
							<label>Área (*)</label>
							<input type="text" class="form-control" value="<?= $datos_revision->proveedor_area; ?>" disabled/>
						</div>

						<div class="col-md-4 form-group">
							<label>Unidad de negocio (*)</label>
							<input type="text" class="form-control" value="<?= $datos_revision->unidad_negocio; ?>" disabled/>
						</div>
						<?}?>
					</div>

					<div class="alert alert-danger" id="div_cambia_tipo_empresa" style="display:none"><b>Atención:</b> Va a modificar el tipo de empresa, esto puede provocar que los <strong>Datos Laborales</strong> los tenga que completar nuevamente.</div>

					<? if($datos_revision->estado_generales==1):?>
						<div class="alert alert-info"><b>Nota:</b> Para que el sistema detalle la documentación requerida complete los datos y presione "Guardar".</div>
						<?endif;?>

						<!-- Botones Guardar y Volver -->
						<?if(NOMBRE_SERVIDOR=='produccion'):?>
							<?if($permisos_especiales['editar_informacion']):?>
								<div class="row">
									<div class="col-md-12">
										<!-- <a href="<?=base_url('documentos/listar')?>" class="btn btn-default btn-md pull-left">Cancelar</a> -->
										<input type="submit" class="btn btn-success btn-md btn-guardar-rechazar" id="btn_guardar" value="Guardar" />
										<div id="guardando" style="display:none;">
											<b class="pull-left">Guardando...</b>
											<img src="<?=base_url('img/loading.gif');?>" width="40" class="pull-left" /> 
										</div>
									</div>
								</div>
								<?endif;?>
								<?endif;?>

								<div class="separador">
								</div>

								<!-- Cierre del formulario -->
								<?php echo form_close();?>

								<?php 
								$data['permisos_especiales']=$permisos_especiales;
								$this->load->view('revision_formularios',$data);?>

							</div><!-- Fin de panel-body-->
						</div><!-- Fin de panel-->
					</div><!-- Fin de col-md-12-->
				</div><!-- Fin de row-->

				<!-- Cargo los documentos de esta vista --> 
				<? 
				$data['estado_doc']=$estado_seccion_general_doc;
				$data['permisos_especiales']=$permisos_especiales;
				if($datos_revision->estado_generales!=1)
					$this->load->view('documentos',$data); 
				?>

			</div>

			<script type="text/javascript">

				var confirma_cambios = false;

				$("#formulario :input").change(function() {
					$("#formulario").data("changed", true);
				});

				function cambiar_tipo_empresa(tipo_empresa){	
					tipo_empresa_old=document.getElementById('tipo_empresa_old').value;

					if(tipo_empresa_old!="" && tipo_empresa!="" && tipo_empresa!=tipo_empresa_old)
						document.getElementById('div_cambia_tipo_empresa').style.display="block";
					else document.getElementById('div_cambia_tipo_empresa').style.display="none";
				}

				$("#btn_guardar").click(function(){  

					if(!validar_formulario())
						return;

					estado=document.getElementById('estado_formulario').value;

	/*if (document.getElementById('cbu').value!="") {
		if (!validarCBU()) {
			return;
		}
	}*/

	if (estado !== "1" && !$("#formulario").data("changed")) {
		$("#btn_guardar").hide();
		$("#guardando").show();
		event.preventDefault();
		location.reload();
		return;
	}

	if(estado==4 && !confirma_cambios){
		//var r = confirm("Si usted realiza el cambio en la sección Datos Generales, cambiará su estado de aprobado a pendiente de control, con lo cual quedará INHABILTADO COMO PROVEEDOR hasta que la información se controle nuevamente.\n¿Está seguro de que desea proceder?");
		$("#mensaje_modal").html("Si usted realiza el cambio en la sección Datos Generales, cambiará su estado de aprobado a pendiente de control, con lo cual quedará INHABILTADO COMO CONTRATISTA hasta que la información se controle nuevamente.<br>¿Está seguro de que desea proceder?");
		$("#msj_advertencia_cambios").modal("show");
		if (confirma_cambios == false) {
			return false;
		} 
	}		

	$("#formulario").submit(function(){
		$("#btn_guardar").hide();
		$("#guardando").show();
	});				 
}); 

				$('#btn_continuar_guardar').click(function(){
					confirma_cambios = true;
					$("#msj_advertencia_cambios").modal("hide");
					$("#btn_guardar").click();
				});

				function validar_formulario(){
					formulario=document.getElementById('formulario');
					var validacion=true;
					var elementos=formulario.length;
	//document.getElementById('cbu').setCustomValidity('');

	var i=0;
	while(i < elementos && validacion) {
		if(formulario[i].name!="") {
			formulario[i].setCustomValidity('');
			if(!formulario[i].checkValidity()) {   
				validacion = false;
				formulario[i].reportValidity();
			}					   
		} 

		i++;
	}

	return validacion;
}


function validarLargoCBU(cbu) {
	if (cbu.length != 22) { return false; }
	return true;
}

function validarCodigoBanco(codigo) {
	if (codigo.length != 8) { return false; }
	var banco = codigo.substr(0,3);
	var digitoVerificador1 = codigo[3];
	var sucursal = codigo.substr(4,3);
	var digitoVerificador2 = codigo[7];

	var ponderador = [3, 1, 7,9];

	var suma = 0;
	 //0720386088000035919436
	 var j = 0;

	 for (var i = 6; i >= 0; i--) {
	 	suma= suma + (codigo[i] * ponderador[j % 4]);
	 	j++;
	 }
	 var diferencia = (10 - suma % 10) % 10;

	 return diferencia == digitoVerificador2;
	}

	function validarCuenta(cuenta) {
		if (cuenta.length != 14) { return false; }
		var digitoVerificador = cuenta[13];
		var suma = cuenta[0] * 3 + cuenta[1] * 9 + cuenta[2] * 7 + cuenta[3] * 1 + cuenta[4] * 3 + cuenta[5] * 9 + cuenta[6] * 7 + cuenta[7] * 1 + cuenta[8] * 3 + cuenta[9] * 9 + cuenta[10] * 7 + cuenta[11] * 1 + cuenta[12] * 3;
		var diferencia = 10 - (suma % 10);
		return diferencia == digitoVerificador;
	}

	function validarCBU(cbu) {
		cbu=document.getElementById('cbu').value;
	 //$("#cbu")[0].setCustomValidity('');
	 document.getElementById('cbu').setCustomValidity('');  
	 rta=validarLargoCBU(cbu) && validarCodigoBanco(cbu.substr(0,8)) && validarCuenta(cbu.substr(8,14));
	 if(!rta)
	 {
	 	document.getElementById('cbu').setCustomValidity('Ingrese un CBU válido');
	 	return false;
	 }
	 else return true;
	}		 

</script>





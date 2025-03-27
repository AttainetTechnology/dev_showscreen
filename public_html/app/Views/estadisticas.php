<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>

<?

use App\Models\Menu_familias_model; ?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Panel de Control - Offertiles</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-3 col-md-6">
			<a href="<?php echo site_url('Lista_produccion/pendientes') ?>">
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-clock-o fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="display-3">
									<?
									echo $pendientes;
									?>
								</div>
								<div>Pendientes de material</div>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<span class="pull-left">Ver partes</span>
						<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
						<div class="clearfix"></div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-lg-3 col-md-6">
			<a href="<?php echo site_url('Lista_produccion/enmarcha/0') ?>">
				<div class="panel panel-info">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-tasks fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="display-3">
									<?
									echo $en_cola;
									?>
								</div>
								<div>Partes en Cola</div>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<span class="pull-left">Ver partes</span>
						<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
						<div class="clearfix"></div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-lg-3 col-md-6">
			<a href="<?php echo site_url('Lista_produccion/enmaquina/0') ?>">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-wrench fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="display-3">
									<?
									echo $en_maquina;
									?>
								</div>
								<div>Partes en máquina</div>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<span class="pull-left">Ver partes</span>
						<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
						<div class="clearfix"></div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-lg-3 col-md-6">
			<a href="<?php echo site_url('Lista_produccion/terminados') ?>">
				<div class="panel panel-success">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<i class="fa fa-check fa-5x"></i>
							</div>
							<div class="col-xs-9 text-right">
								<div class="display-3">
									<?
									echo $terminados;
									?>
								</div>
								<div>Partes terminados</div>
							</div>
						</div>
					</div>
					<div class="panel-footer">
						<span class="pull-left">Ver partes</span>
						<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
						<div class="clearfix"></div>
					</div>
				</div>
		</div>
		</a>
	</div>
	<div class="row">
		<div class="col-lg-8">
			<div class="panel panel-default <? echo $clase; ?>">
				<div class="panel-heading">
					<i class="fa fa-bar-chart-o fa-fw"></i> <? echo $titulo; ?>
					<div class="pull-right">
						<div class="btn-group">
							<button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
								Otras gráficas
							</button>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="<?php echo site_url('Index/pendientes') ?>">En espera de material</a></li>
								<li><a class="dropdown-item" href="<?php echo site_url('Index/enmarcha') ?>">En cola de producción</a></li>
								<li><a class="dropdown-item" href="<?php echo site_url('Index/enmaquina') ?>">En máquina</a></li>
								<li><a class="dropdown-item" href="<?php echo site_url('Index/terminados') ?>">Terminadas</a></li>
							</ul>
						</div>
					</div>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-12">
							<div class="table-responsive">
								<table class="table table-bordered table-hover table-striped">
									<thead>
										<tr>
											<th>Sección</th>
											<th>Piezas</th>
											<th>Gráfica</th>
											<?
											if ($nivel > '6') {
											?>
												<th>Importe</th>
											<? } ?>
										</tr>
									</thead>
									<tbody>
										<?
										$linea = "";
										$mayor = "0";
										if (isset($piezasfamilia)) {
											foreach ($piezasfamilia as $pz) :
												if ($mayor < $pz['total_piezas']) :
													$mayor = $pz['total_piezas'];
												endif;
											endforeach;
											foreach ($piezasfamilia as $pz) :
												$ancho = ($mayor > 0) ? ((100 * $pz['total_piezas']) / $mayor) : 0;
												$linea .= "<tr>
                            			<td>" . $pz['nombre'] . "</td>
                            			<td>" . $pz['total_piezas'] . "</td>
        								<td><img src=" . site_url('public/assets/uploads/files/grafico.png') . " height='25px' width='" .  $ancho . "%'></td>";
												if ($nivel > 6) :
													$euros = number_format($pz['total_euros'], 0, ',', '.');
													$linea .= "<td><strong>" . $euros . " €</strong></td>";
												endif;
												$linea .= "</tr>";
											endforeach;
										}
										echo $linea;
										?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4">
			<? if (isset($rutas)) : ?>
				<div class="panel panel-default">
					<div class="panel-heading">
						<i class="fa fa-truck fa-fw"></i> Rutas de Transporte
					</div>
					<div class="panel-body">
						<? echo view('rutas_home'); ?>
					</div>
				</div>
			<? endif; ?>
			<div class="panel panel-default">
				<div class="panel-heading">
					<i class="fa fa-users fa-fw"></i> Incidencias
				</div>
				<!-- /.panel-heading -->
				<div class="panel-body">
					<table>
						<thead>
							<tr>
								<th>Usuario</th>
								<th>Incidencia</th>
							</tr>
						</thead>
						<tbody>
							<?php if (!empty($incidencias)): ?>
								<?php foreach ($incidencias as $incidencia): ?>
									<tr>
										<td><?= $incidencia->nombre_usuario ?></td>
										<td><?= $incidencia->incidencia ?></td>

									</tr>
								<?php endforeach; ?>
							<?php else: ?>
								<tr>
									<td colspan="3">No se encontraron incidencias en los últimos 7 días.</td>
								</tr>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Editar Incidencia -->
<div class="modal fade" id="editIncidenciaModal" tabindex="-1" role="dialog" aria-labelledby="editIncidenciaModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editIncidenciaModalLabel">Editar Incidencia</h5>
				<button type="button" class="btn-close-custom" aria-label="Close" onclick="window.location.href='<?= base_url() ?>'">
					&times;
				</button>
			</div>
			<div class="modal-body">
				<form id="editIncidenciaForm" action="<?= base_url('index/guardar') ?>" method="post">
					<input type="hidden" id="incidenciaId" name="id">
					<div class="form-group">
						<label for="entradaFecha">Fecha de Entrada</label>
						<input type="date" class="form-control" id="entradaFecha" name="entrada_fecha" required>
					</div>
					<div class="form-group">
						<label for="entradaHora">Hora de Entrada</label>
						<input type="time" class="form-control" id="entradaHora" name="entrada_hora" required>
					</div>
					<div class="form-group">
						<label for="salidaFecha">Fecha de Salida</label>
						<input type="date" class="form-control" id="salidaFecha" name="salida_fecha">
					</div>
					<div class="form-group">
						<label for="salidaHora">Hora de Salida</label>
						<input type="time" class="form-control" id="salidaHora" name="salida_hora">
					</div>
					<br>
					<button type="submit" class="btn btn-primary float-end">Guardar</button>
				</form>
			</div>
		</div>
	</div>
</div>
<script>
	document.addEventListener('DOMContentLoaded', function() {
		$('#editIncidenciaModal').on('show.bs.modal', function(event) {
			var button = $(event.relatedTarget);
			var id = button.data('id');
			var entrada_fecha = button.data('entrada-fecha');
			var entrada_hora = button.data('entrada-hora');
			var salida_fecha = button.data('salida-fecha');
			var salida_hora = button.data('salida-hora');

			var modal = $(this);
			modal.find('#incidenciaId').val(id);
			modal.find('#entradaFecha').val(entrada_fecha);
			modal.find('#entradaHora').val(entrada_hora);
			modal.find('#salidaFecha').val(salida_fecha);
			modal.find('#salidaHora').val(salida_hora);

		});

	});
</script>
<?= $this->endSection() ?>
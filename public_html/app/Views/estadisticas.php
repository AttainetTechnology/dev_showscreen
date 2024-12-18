<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<style>
	.row {
    --bs-gutter-x: 0.5rem !important;

}
</style>
<?

use App\Models\Menu_familias_model; ?>

<div id="page-wrapper">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header">Panel de Control - Offertiles</h1>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-2 col-md-2 col-sm-2">
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
								<div style="font-size: 12px;">Pendientes</div>
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
		<div class="col-lg-2 col-md-2 col-sm-2">
			<a href="<?php echo site_url('Lista_produccion/enmarcha/0') ?>">
				<div class="panel enCola">
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
								<div style="font-size: 12px;">Partes en Cola</div>
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
		<div class="col-lg-2 col-md-2 col-sm-2">
			<a href="<?php echo site_url('Lista_produccion/enmaquina/0') ?>">
				<div class="panel enMaquina">
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
								<div style="font-size: 12px;">Partes en máquina</div>
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
		<div class="col-lg-2 col-md-2 col-sm-2">
			<a href="<?php echo site_url('Lista_produccion/terminados') ?>">
				<div class="panel enTerminados">
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
								<div style="font-size: 12px;">Partes terminados</div>
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
		<div class="col-lg-2 col-md-2 col-sm-2">
			<a href="<?php echo site_url('Lista_produccion/todoslospartes') ?>">
				<div class="panel enEntregados">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<svg xmlns="http://www.w3.org/2000/svg" width="65" height="65" viewBox="0 0 58 58"
									fill="none">
									<path
										d="M32.5163 18.0163C33.0269 17.5301 33.7064 17.2613 34.4115 17.2667C35.1165 17.272 35.7919 17.551 36.2951 18.0448C36.7984 18.5387 37.0901 19.2087 37.1087 19.9135C37.1273 20.6183 36.8714 21.3028 36.395 21.8225L21.9313 39.9113C21.6825 40.1791 21.3824 40.3941 21.0487 40.5433C20.715 40.6926 20.3546 40.773 19.9892 40.7797C19.6237 40.7865 19.2606 40.7195 18.9216 40.5827C18.5826 40.446 18.2747 40.2422 18.0163 39.9838L8.42451 30.392C8.15739 30.1431 7.94315 29.843 7.79455 29.5095C7.64595 29.176 7.56605 28.8159 7.55961 28.4509C7.55317 28.0859 7.62032 27.7232 7.75706 27.3847C7.8938 27.0462 8.09732 26.7387 8.35549 26.4805C8.61366 26.2223 8.92118 26.0188 9.25971 25.8821C9.59825 25.7453 9.96085 25.6782 10.3259 25.6846C10.6909 25.6911 11.051 25.771 11.3845 25.9195C11.718 26.0681 12.0181 26.2824 12.267 26.5495L19.8578 34.1366L32.4438 18.096L32.5163 18.0163ZM29.1813 36.6488L32.5163 39.9838C32.7747 40.2417 33.0824 40.4449 33.421 40.5814C33.7596 40.7178 34.1223 40.7846 34.4873 40.7778C34.8524 40.7711 35.2123 40.6909 35.5457 40.542C35.879 40.3931 36.179 40.1786 36.4276 39.9113L50.8986 21.8225C51.1586 21.5659 51.3641 21.2595 51.5031 20.9216C51.642 20.5837 51.7114 20.2214 51.7071 19.8561C51.7028 19.4908 51.625 19.1301 51.4783 18.7956C51.3315 18.4611 51.1189 18.1596 50.853 17.9091C50.5871 17.6585 50.2735 17.4642 49.9309 17.3376C49.5882 17.211 49.2235 17.1547 48.8587 17.1721C48.4938 17.1896 48.1362 17.2804 47.8072 17.4391C47.4781 17.5979 47.1845 17.8213 46.9438 18.096L34.3541 34.1366L32.596 32.3749L29.1813 36.6488Z"
										fill="#006192" stroke="#006192" stroke-width="2" />
								</svg>
							</div>

							<div class="col-xs-9 text-right">
								<div class="display-3">
									<br>
								</div>
								<div style="font-size: 12px;">Partes entregados</div>
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
		<div class="col-lg-2 col-md-2 col-sm-2">
			<a href="<?php echo site_url('Lista_produccion/todoslospartes') ?>">
				<div class="panel enAnulados">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-3">
								<svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 26 26">
									<path
										d="M0.841754 0.841754C1.01012 0.672962 1.21013 0.539044 1.43033 0.447671C1.65053 0.356297 1.8866 0.309265 2.125 0.309265C2.36341 0.309265 2.59948 0.356297 2.81968 0.447671C3.03988 0.539044 3.23989 0.672962 3.40825 0.841754L13 10.4371L22.5918 0.841754C22.7603 0.673235 22.9603 0.539559 23.1805 0.448357C23.4007 0.357155 23.6367 0.310214 23.875 0.310214C24.1133 0.310214 24.3493 0.357155 24.5695 0.448357C24.7897 0.539559 24.9897 0.673235 25.1583 0.841754C25.3268 1.01027 25.4604 1.21033 25.5517 1.43051C25.6429 1.65069 25.6898 1.88668 25.6898 2.125C25.6898 2.36333 25.6429 2.59931 25.5517 2.81949C25.4604 3.03968 25.3268 3.23974 25.1583 3.40825L15.5629 13L25.1583 22.5918C25.3268 22.7603 25.4604 22.9603 25.5517 23.1805C25.6429 23.4007 25.6898 23.6367 25.6898 23.875C25.6898 24.1133 25.6429 24.3493 25.5517 24.5695C25.4604 24.7897 25.3268 24.9897 25.1583 25.1583C24.9897 25.3268 24.7897 25.4604 24.5695 25.5517C24.3493 25.6429 24.1133 25.6898 23.875 25.6898C23.6367 25.6898 23.4007 25.6429 23.1805 25.5517C22.9603 25.4604 22.7603 25.3268 22.5918 25.1583L13 15.5629L3.40825 25.1583C3.23974 25.3268 3.03968 25.4604 2.81949 25.5517C2.59931 25.6429 2.36333 25.6898 2.125 25.6898C1.88668 25.6898 1.65069 25.6429 1.43051 25.5517C1.21033 25.4604 1.01027 25.3268 0.841754 25.1583C0.673235 24.9897 0.539559 24.7897 0.448357 24.5695C0.357155 24.3493 0.310214 24.1133 0.310214 23.875C0.310214 23.6367 0.357155 23.4007 0.448357 23.1805C0.539559 22.9603 0.673235 22.7603 0.841754 22.5918L10.4371 13L0.841754 3.40825C0.672962 3.23989 0.539044 3.03988 0.447671 2.81968C0.356297 2.59948 0.309265 2.36341 0.309265 2.125C0.309265 1.8866 0.356297 1.65053 0.447671 1.43033C0.539044 1.21013 0.672962 1.01012 0.841754 0.841754Z"
										fill="#006192" stroke="#006192" stroke-width="1" />
								</svg>
							</div>
							<div class="col-xs-9 text-right">
								<div class="display-3">
								</div>
								<div class="display-3">
									<br>
								</div>
								<div style="font-size: 12px;">Partes Anulados</div>
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
	</div>
	<div class="row">
		<div class="col-lg-8">
			<div class="panel panel-default <? echo $clase; ?>">
				<div class="panel-heading">
					<i class="fa fa-bar-chart-o fa-fw"></i> <? echo $titulo; ?>
					<div class="pull-right">
						<div class="btn-group">
							<button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown"
								aria-expanded="false">
								Otras gráficas
							</button>
							<ul class="dropdown-menu">
								<li><a class="dropdown-item" href="<?php echo site_url('Index/pendientes') ?>">En espera
										de material</a></li>
								<li><a class="dropdown-item" href="<?php echo site_url('Index/enmarcha') ?>">En cola de
										producción</a></li>
								<li><a class="dropdown-item" href="<?php echo site_url('Index/enmaquina') ?>">En
										máquina</a></li>
								<li><a class="dropdown-item"
										href="<?php echo site_url('Index/terminados') ?>">Terminadas</a></li>
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
											foreach ($piezasfamilia as $pz):
												if ($mayor < $pz['total_piezas']):
													$mayor = $pz['total_piezas'];
												endif;
											endforeach;
											foreach ($piezasfamilia as $pz):
												$ancho = ($mayor > 0) ? ((100 * $pz['total_piezas']) / $mayor) : 0;
												$linea .= "<tr>
                            			<td>" . $pz['nombre'] . "</td>
                            			<td>" . $pz['total_piezas'] . "</td>
        								<td><img src=" . site_url('public/assets/uploads/files/grafico.png') . " height='25px' width='" . $ancho . "%'></td>";
												if ($nivel > 6):
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
			<? if (isset($rutas)): ?>
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
					Próximamente
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal Editar Incidencia -->
<div class="modal fade" id="editIncidenciaModal" tabindex="-1" role="dialog" aria-labelledby="editIncidenciaModalLabel"
	aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editIncidenciaModalLabel">Editar Incidencia</h5>
				<button type="button" class="btn-close-custom" aria-label="Close"
					onclick="window.location.href='<?= base_url() ?>'">
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
	document.addEventListener('DOMContentLoaded', function () {
		$('#editIncidenciaModal').on('show.bs.modal', function (event) {
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
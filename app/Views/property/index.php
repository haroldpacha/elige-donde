<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Sección de filtrado de búsqueda -->
<section class="filter-section py-3 px-4 px-lg-5 bg-light d-none d-sm-block">
	<div class="container" style="max-width: 1400px;">
		<form class="d-flex wrap justify-content-center" style="gap: 5px;">

			<!-- Input de búsqueda -->
			<div class="bio  d-none d-md-block w-50">
				<div class="input-group">
					<input type="text" class="form-control custom-input businput"
						   placeholder="Ingresa departamentos o distritos" aria-label="Buscar">
				</div>
			</div>

			<!-- Botón de filtro de tipo -->
			<div class="bio  d-none d-md-block">
				<select class="form-select custom-button" aria-label="Filtrar por tipo">
					<option value="venta" <?= (isset($filters['transaction_type']) && $filters['transaction_type'] === 'venta') ? 'selected' : '' ?>>Venta</option>
                    <option value="alquiler" <?= (isset($filters['transaction_type']) && $filters['transaction_type'] === 'alquiler') ? 'selected' : '' ?>>Alquiler</option>
                    <option value="anticresis" <?= (isset($filters['transaction_type']) && $filters['transaction_type'] === 'anticresis') ? 'selected' : '' ?>>Anticresis</option>
				</select>
			</div>

			<!-- Botón de filtro de propiedad -->
			<div class=" d-none d-md-block">
				<select class="form-select custom-button" aria-label="Filtrar por propiedad">
					<?php foreach ($property_types as $type) : ?>
                        <option value="<?= esc($type['id']) ?>" <?= (isset($filters['property_type_id']) && (int)$filters['property_type_id'] === (int)$type['id']) ? 'selected' : '' ?>><?= esc($type['name']) ?></option>
                    <?php endforeach; ?>
				</select>
			</div>

			<!-- Filtro de dormitorios -->
			<div class="bio  d-none d-lg-block" id="filtro-dormitorios">
				<div class="btn-group">
					<button type="button" class="btn custom-button dropdown-toggle" data-bs-toggle="dropdown"
							aria-haspopup="true" aria-expanded="false">
						Dormitorios
					</button>
					<div class="dropdown-menu" style="padding-right: 10px;">
						<div class="d-flex">
							<div class="dropdown-item">
								<select class="form-select" aria-label="Dormitorios mínimo" style="width: auto;">
									<option selected>Sin mínimo</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5+</option>
								</select>
							</div>
							<div class="dropdown-item">
								<select class="form-select" aria-label="Dormitorios máximo" style="width: auto;">
									<option selected>Sin máximo</option>
									<option value="1">1</option>
									<option value="2">2</option>
									<option value="3">3</option>
									<option value="4">4</option>
									<option value="5">5+</option>
								</select>
							</div>
						</div>
						<div class="dropdown-divider"></div>
						<div class="d-flex justify-content-between mt-2">
							<button type="button" class="btn" onclick="limpiarBusqueda()">Limpiar Búsqueda</button>
							<button type="button" class="btn btn-primary" onclick="cerrarDropdown()">Ver Resultados
							</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Filtro de precios -->
			<div class="bio  d-none d-xl-block " id="filtro-precios">
				<div class="btn-group">
					<button type="button" class="btn custom-button dropdown-toggle" data-bs-toggle="dropdown"
							aria-haspopup="true" aria-expanded="false">
						Precio
					</button>
					<div class="dropdown-menu " style="padding-right: 10px;">
						<div class="dropdown-item d-flex justify-content-between ">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="currency" id="soles" value="soles">
								<label class="form-check-label" for="soles">Soles</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="currency" id="dolares"
									   value="dolares" checked>
								<label class="form-check-label" for="dolares">Dólares</label>
							</div>
						</div>
						<div class="dropdown-item">
							<div class="d-flex w-100">
								<div class="col-md-6">
									<select class="form-select" aria-label="Desde" style="width: auto;">
										<option selected>Desde</option>
										<option value="1">500</option>
										<option value="2">1,500</option>
										<option value="3">2,500</option>
										<option value="4">3,200</option>
										<option value="5">4,000</option>
										<option value="6">6,000</option>
										<option value="7">8,000</option>
										<option value="8">12,000</option>
									</select>
								</div>
								<div class="col-md-6">
									<select class="form-select" aria-label="Hasta" style="width: auto;">
										<option selected>Hasta</option>
										<option value="1">500</option>
										<option value="2">1,500</option>
										<option value="3">2,500</option>
										<option value="4">3,200</option>
										<option value="5">4,000</option>
										<option value="6">6,000</option>
										<option value="7">8,000</option>
										<option value="8">12,000</option>
									</select>
								</div>
							</div>
						</div>
						<div class="d-flex justify-content-between mt-2">
							<button type="button" class="btn" onclick="limpiarBusqueda()">Limpiar Búsqueda</button>
							<button type="button" class="btn btn-primary" onclick="cerrarDropdown()">Ver Resultados
							</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Más filtros -->
			<div class="bio d-none d-sm-block">
				<div class="btn-group">
					<button type="button" class="btn custom-button dropdown-toggle" data-bs-toggle="dropdown"
							aria-haspopup="true" data-bs-auto-close="outside" aria-expanded="false">
						Más Filtros
					</button>

					<div class="dropdown-menu p-3 anchofil">

						<!-- Input de búsqueda -->
						<div class="bio" id="filtro-busqueda">
							<h6 class="dropdown-header">Ubicación</h6>
							<div class="input-group">
								<input type="text" class="form-control custom-input businput" aria-label="Buscar">
							</div>
						</div>

						<!-- Botón de filtro de tipo -->
						<div class="bio" id="filtro-tipo">
							<h6 class="dropdown-header">Tipo de Operación</h6>
							<select class="form-select custom-button" aria-label="Filtrar por tipo">
								<option value="alquilar">Alquilar</option>
								<option value="comprar">Comprar</option>
								<option value="proyectos">Proyectos</option>
								<option value="traspaso">Traspaso</option>
							</select>
						</div>

						<!-- Botón de filtro de propiedad -->
						<div class="bio" id="filtro-propiedad">
							<h6 class="dropdown-header">Tipo de Propiedad</h6>
							<select class="form-select custom-button" aria-label="Filtrar por propiedad">
								<option value="casa">Casa</option>
								<option value="departamento">Departamento</option>
								<option value="terreno">Terreno</option>
							</select>
						</div>
						<!-- Dormitorios -->
						<div class="bio" id="filtro-dormitorios">
							<h6 class="dropdown-header">Dormitorios</h6>
							<div class="d-flex">
								<div class="dropdown-item">
									<select class="form-select" aria-label="Dormitorios mínimo" style="width: auto;">
										<option selected>Sin mínimo</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5+</option>
									</select>
								</div>
								<div class="dropdown-item">
									<select class="form-select" aria-label="Dormitorios máximo" style="width: auto;">
										<option selected>Sin máximo</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5+</option>
									</select>
								</div>
							</div>
						</div>

						<!-- Precios -->
						<div class="bio" id="filtro-precios">
							<h6 class="dropdown-header">Precio</h6>
							<div class="dropdown-item d-flex justify-content-between">
								<div class="form-check">
									<input class="form-check-input" type="radio" name="currency" id="soles"
										   value="soles">
									<label class="form-check-label" for="soles">Soles</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="currency" id="dolares"
										   value="dolares" checked>
									<label class="form-check-label" for="dolares">Dólares</label>
								</div>
							</div>
							<div class="d-flex w-100" style="align-content: space-evenly;">
								<div class="col-md-6">
									<select class="form-select" aria-label="Desde" style="width: auto;">
										<option selected>Desde</option>
										<option value="1">500</option>
										<option value="2">1,500</option>
										<option value="3">2,500</option>
										<option value="4">3,200</option>
										<option value="5">4,000</option>
										<option value="6">6,000</option>
										<option value="7">8,000</option>
										<option value="8">12,000</option>
									</select>
								</div>
								<div class="col-md-6">
									<select class="form-select" aria-label="Hasta" style="width: auto;">
										<option selected>Hasta</option>
										<option value="1">500</option>
										<option value="2">1,500</option>
										<option value="3">2,500</option>
										<option value="4">3,200</option>
										<option value="5">4,000</option>
										<option value="6">6,000</option>
										<option value="7">8,000</option>
										<option value="8">12,000</option>
									</select>
								</div>
							</div>
						</div>
						<!-- Características -->
						<h6 class="dropdown-header">Características</h6>
						<div class="input-group mb-3">
							<input type="text" class="form-control" aria-label="Características"
								   placeholder="Ej. Piscina, amueblado, etc">
						</div>

						<!-- Superficie -->
						<h6 class="dropdown-header">Superficie</h6>
						<div class="dropdown-item d-flex justify-content-between">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="superficie" id="techada"
									   value="metros">
								<label class="form-check-label" for="techada">Techada</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="superficie" id="total" value="metros"
									   checked>
								<label class="form-check-label" for="total">Total</label>
							</div>
						</div>
						<div class="input-group mb-3">
							<select class="form-select" aria-label="Desde" style="width: 50px !important">
								<option selected>m2</option>
								<option value="1">HA</option>
							</select>
							<input type="text" class="form-control" aria-label="Superficie desde" placeholder="Desde">
							<input type="text" class="form-control" aria-label="Superficie hasta" placeholder="Hasta">
						</div>

						<!-- Baños -->
						<h6 class="dropdown-header">Baños</h6>
						<div class="btn-group  w-100" role="group" aria-label="Baños">
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarBano(this); evitarCierre(event)">1+
							</button>
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarBano(this); evitarCierre(event)">2+
							</button>
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarBano(this); evitarCierre(event)">3+
							</button>
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarBano(this); evitarCierre(event)">4+
							</button>
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarBano(this); evitarCierre(event)">5+
							</button>
						</div>

						<!-- Estacionamiento -->
						<h6 class="dropdown-header">Estacionamiento</h6>
						<div class="btn-group w-100" role="group" aria-label="Estacionamiento">
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarEstacionamiento(this); evitarCierre(event)">0
							</button>
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarEstacionamiento(this); evitarCierre(event)">1+
							</button>
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarEstacionamiento(this); evitarCierre(event)">2+
							</button>
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarEstacionamiento(this); evitarCierre(event)">3+
							</button>
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarEstacionamiento(this); evitarCierre(event)">4+
							</button>
						</div>


						<!-- Tipo de anunciante -->
						<h6 class="dropdown-header">Tipo de anunciante</h6>
						<div class="dropdown-item">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="tipo_anunciante" id="todos"
									   value="todos" checked>
								<label class="form-check-label" for="todos">Todos</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="tipo_anunciante" id="inmobiliaria"
									   value="inmobiliaria">
								<label class="form-check-label" for="inmobiliaria">Inmobiliaria</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="tipo_anunciante" id="dueno"
									   value="dueno">
								<label class="form-check-label" for="dueno">Dueño directo</label>
							</div>
						</div>

						<!-- Antigüedad -->
						<h6 class="dropdown-header">Antigüedad</h6>
						<div class="dropdown-item">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="antiguedad" id="en_construccion"
									   value="en_construccion" checked>
								<label class="form-check-label" for="en_construccion">En construcción</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="antiguedad" id="a_estrenar"
									   value="a_estrenar">
								<label class="form-check-label" for="a_estrenar">A estrenar</label>
							</div>
							<div id="mas_opciones1" style="display: none;">
								<div class="form-check">
									<input class="form-check-input" type="radio" name="antiguedad" id="hasta_10"
										   value="hasta_10">
									<label class="form-check-label" for="hasta_10">Hasta 10 años</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="antiguedad" id="mas_50"
										   value="mas_50">
									<label class="form-check-label" for="mas_50">Más de 10 años</label>
								</div>
							</div>

							<button type="button" class="btn btn-link" id="toggleButtonDesktop"
									onclick="toggleOpciones(event, 'mas_opciones1','toggleButtonDesktop')"
									style="color: #03669c;">Ver más
							</button>
						</div>


						<!-- Fecha de publicación -->
						<h6 class="dropdown-header">Fecha de publicación</h6>
						<div class="dropdown-item">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="fecha_publicacion" id="desde_ayer"
									   value="desde_ayer" checked>
								<label class="form-check-label" for="desde_ayer">Desde ayer</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="fecha_publicacion" id="hoy"
									   value="hoy">
								<label class="form-check-label" for="hoy">Hoy</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="fecha_publicacion" id="ultima_semana"
									   value="ultima_semana">
								<label class="form-check-label" for="ultima_semana">Última semana</label>
							</div>

							<!-- Opciones adicionales ocultas -->
							<div id="mas_opciones_fecha" style="display: none;">
								<div class="form-check">
									<input class="form-check-input" type="radio" name="fecha_publicacion"
										   id="ultimos_15_dias" value="ultimos_15_dias">
									<label class="form-check-label" for="ultimos_15_dias">Últimos 15 días</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="fecha_publicacion"
										   id="ultimos_30_dias" value="ultimos_30_dias">
									<label class="form-check-label" for="ultimos_30_dias">Últimos 30 días</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="fecha_publicacion"
										   id="ultimos_45_dias" value="ultimos_45_dias">
									<label class="form-check-label" for="ultimos_45_dias">Últimos 45 días</label>
								</div>
							</div>


							<button type="button" class="btn btn-link" id="toggleButtonFecha"
									onclick="toggleOpcionesFecha(event,'mas_opciones_fecha','toggleButtonFecha' )"
									style="color: #03669c">Ver más
							</button>
						</div>


						<!-- Ambientes -->
						<h6 class="dropdown-header">Ambientes</h6>
						<div class="dropdown-item">
							<div id="ambientes-container">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="bodega" id="ambiente-bodega">
									<label class="form-check-label" for="ambiente-bodega">Bodega</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="patio" id="ambiente-patio">
									<label class="form-check-label" for="ambiente-patio">Patio</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="bano_propio"
										   id="ambiente-bano-propio">
									<label class="form-check-label" for="ambiente-bano-propio">Baño Propio</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="deposito"
										   id="ambiente-deposito">
									<label class="form-check-label" for="ambiente-deposito">Depósito</label>
								</div>

								<div id="more-ambientes" style="display: none;">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="hidromasaje"
											   id="ambiente-hidromasaje">
										<label class="form-check-label" for="ambiente-hidromasaje">Hidromasaje</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="piscina_cubierta"
											   id="ambiente-piscina-cubierta">
										<label class="form-check-label" for="ambiente-piscina-cubierta">Piscina
											cubierta</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="salon_usos_multiples"
											   id="ambiente-salon-usos-multiples">
										<label class="form-check-label" for="ambiente-salon-usos-multiples">Salón de
											usos múltiples</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="suite"
											   id="ambiente-suite">
										<label class="form-check-label" for="ambiente-suite">Suite</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="turco"
											   id="ambiente-turco">
										<label class="form-check-label" for="ambiente-turco">Turco</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="atico"
											   id="ambiente-atico">
										<label class="form-check-label" for="ambiente-atico">Ático</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="areas_cafeteria"
											   id="ambiente-areas-cafeteria">
										<label class="form-check-label" for="ambiente-areas-cafeteria">Áreas de
											cafetería</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="area_comun"
											   id="ambiente-area-comun">
										<label class="form-check-label" for="ambiente-area-comun">Área común</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="cuarto_juegos"
											   id="ambiente-cuarto-juegos">
										<label class="form-check-label" for="ambiente-cuarto-juegos">Cuarto de
											juegos</label>
									</div>
								</div>
							</div>
							<button type="button" class="btn btn-link" id="toggleButtonAmbientes"
									onclick="toggleAmbientes(event,'more-ambientes','toggleButtonAmbientes')"
									style="color: #004473;">Ver más
							</button>
						</div>


						<div class="dropdown-divider"></div>
						<div class="d-flex justify-content-between mt-2">
							<button type="button" class="btn" onclick="limpiarBusqueda()">Limpiar Búsqueda</button>
							<button type="button" class="btn btn-primary" onclick="cerrarDropdown()">Ver Resultados
							</button>
						</div>
					</div>
				</div>
			</div>

			<!-- Botón para Ver Mapa, visible solo en pantallas móviles -->
			<button type="button" class="btn btn-primary d-none " onclick="verMapa()">
				Ver Mapa
			</button>

			<!-- Botón para Crear Alerta, visible solo en pantallas móviles -->
			<button type="button" class="btn btn-primary d-none " onclick="crearAlerta()">
				Crear Alerta
			</button>

			<div>
		</form>


	</div>

</section>

<section class="d-block d-sm-none text-center mt-2">
	<!-- Botón para abrir el modal -->
	<button type="button" class="btn custom-button" data-bs-toggle="modal" data-bs-target="#filtrosModal">
		Más Filtros
	</button>

	<!-- Modal -->
	<div class="modal fade text-start" id="filtrosModal" tabindex="-1" aria-labelledby="filtrosModalLabel"
		 aria-hidden="true">
		<div class="modal-dialog modal-fullscreen">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="filtrosModalLabel">Filtros</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<form class="d-flex row" style="gap: 5px;">

						<!-- Input de búsqueda -->
						<div class="bio" id="filtro-busqueda">
							<h6 class="dropdown-header">Ubicación</h6>
							<div class="input-group">
								<input type="text" class="form-control custom-input businput" aria-label="Buscar">
							</div>
						</div>

						<!-- Botón de filtro de tipo -->
						<div class="bio" id="filtro-tipo">
							<h6 class="dropdown-header">Tipo de Operación</h6>
							<select class="form-select custom-button" aria-label="Filtrar por tipo">
								<option value="alquilar">Alquilar</option>
								<option value="comprar">Comprar</option>
								<option value="proyectos">Proyectos</option>
								<option value="traspaso">Traspaso</option>
							</select>
						</div>

						<!-- Botón de filtro de propiedad -->
						<div class="bio" id="filtro-propiedad">
							<h6 class="dropdown-header">Tipo de Propiedad</h6>
							<select class="form-select custom-button" aria-label="Filtrar por propiedad">
								<option value="casa">Casa</option>
								<option value="departamento">Departamento</option>
								<option value="terreno">Terreno</option>
							</select>
						</div>
						<!-- Dormitorios -->
						<div class="bio" id="filtro-dormitorios">
							<h6 class="dropdown-header">Dormitorios</h6>
							<div class="d-flex">
								<div class="dropdown-item">
									<select class="form-select" aria-label="Dormitorios mínimo">
										<option selected>Sin mínimo</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5+</option>
									</select>
								</div>
								<div class="dropdown-item">
									<select class="form-select" aria-label="Dormitorios máximo">
										<option selected>Sin máximo</option>
										<option value="1">1</option>
										<option value="2">2</option>
										<option value="3">3</option>
										<option value="4">4</option>
										<option value="5">5+</option>
									</select>
								</div>
							</div>
						</div>

						<!-- Precios -->
						<div class="bio" id="filtro-precios">
							<h6 class="dropdown-header">Precio</h6>
							<div class="dropdown-item d-flex" style="justify-content: space-around;">
								<div class="form-check">
									<input class="form-check-input" type="radio" name="currency" id="soles"
										   value="soles">
									<label class="form-check-label" for="soles">Soles</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="currency" id="dolares"
										   value="dolares" checked>
									<label class="form-check-label" for="dolares">Dólares</label>
								</div>
							</div>
							<div class="d-flex">
								<div class="dropdown-item">
									<select class="form-select" aria-label="Desde">
										<option selected>Desde</option>
										<option value="1">500</option>
										<option value="2">1,500</option>
										<option value="3">2,500</option>
										<option value="4">3,200</option>
										<option value="5">4,000</option>
										<option value="6">6,000</option>
										<option value="7">8,000</option>
										<option value="8">12,000</option>
									</select>
								</div>
								<div class="dropdown-item">
									<select class="form-select" aria-label="Hasta">
										<option selected>Hasta</option>
										<option value="1">500</option>
										<option value="2">1,500</option>
										<option value="3">2,500</option>
										<option value="4">3,200</option>
										<option value="5">4,000</option>
										<option value="6">6,000</option>
										<option value="7">8,000</option>
										<option value="8">12,000</option>
									</select>
								</div>
							</div>
						</div>
						<!-- Características -->
						<h6 class="dropdown-header" style="margin-left: 13px;">Características</h6>
						<div class="input-group mb-3">
							<input type="text" class="form-control" aria-label="Características"
								   placeholder="Ej. Piscina, amueblado, etc">
						</div>

						<!-- Superficie -->
						<h6 class="dropdown-header" style="margin-left: 13px;">Superficie</h6>
						<div class="dropdown-item d-flex" style="justify-content: space-around;">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="superficie" id="techada"
									   value="metros">
								<label class="form-check-label" for="techada">Techada</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="superficie" id="total" value="metros"
									   checked>
								<label class="form-check-label" for="total">Total</label>
							</div>
						</div>
						<div class="input-group mb-3">
							<select class="form-select" aria-label="Desde">
								<option selected>m2</option>
								<option value="1">HA</option>
							</select>
							<input type="text" class="form-control" aria-label="Superficie desde" placeholder="Desde">
							<input type="text" class="form-control" aria-label="Superficie hasta" placeholder="Hasta">
						</div>

						<!-- Baños -->
						<h6 class="dropdown-header" style="margin-left: 13px;">Baños</h6>
						<div class="btn-group  w-100" role="group" aria-label="Baños">
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarBano(this); evitarCierre(event)">1+
							</button>
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarBano(this); evitarCierre(event)">2+
							</button>
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarBano(this); evitarCierre(event)">3+
							</button>
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarBano(this); evitarCierre(event)">4+
							</button>
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarBano(this); evitarCierre(event)">5+
							</button>
						</div>

						<!-- Estacionamiento -->
						<h6 class="dropdown-header" style="margin-left: 13px;">Estacionamiento</h6>
						<div class="btn-group w-100" role="group" aria-label="Estacionamiento">
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarEstacionamiento(this); evitarCierre(event)">0
							</button>
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarEstacionamiento(this); evitarCierre(event)">1+
							</button>
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarEstacionamiento(this); evitarCierre(event)">2+
							</button>
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarEstacionamiento(this); evitarCierre(event)">3+
							</button>
							<button type="button" class="btn btn-outline-primary"
									onclick="seleccionarEstacionamiento(this); evitarCierre(event)">4+
							</button>
						</div>


						<!-- Tipo de anunciante -->
						<h6 class="dropdown-header" style="margin-left: 13px;">Tipo de anunciante</h6>
						<div class="dropdown-item" style="margin-left: 13px;">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="tipo_anunciante" id="todos"
									   value="todos" checked>
								<label class="form-check-label" for="todos">Todos</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="tipo_anunciante" id="inmobiliaria"
									   value="inmobiliaria">
								<label class="form-check-label" for="inmobiliaria">Inmobiliaria</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="tipo_anunciante" id="dueno"
									   value="dueno">
								<label class="form-check-label" for="dueno">Dueño directo</label>
							</div>
						</div>

						<!-- Antigüedad -->
						<h6 class="dropdown-header" style="margin-left: 13px;">Antigüedad</h6>
						<div class="dropdown-item" style="margin-left: 13px;">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="antiguedad" id="en_construccion"
									   value="en_construccion" checked>
								<label class="form-check-label" for="en_construccion">En construcción</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="antiguedad" id="a_estrenar"
									   value="a_estrenar">
								<label class="form-check-label" for="a_estrenar">A estrenar</label>
							</div>
							<div id="mas_opciones2" style="display: none;">
								<div class="form-check">
									<input class="form-check-input" type="radio" name="antiguedad" id="hasta_10"
										   value="hasta_10">
									<label class="form-check-label" for="hasta_10">Hasta 10 años</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="antiguedad" id="mas_50"
										   value="mas_50">
									<label class="form-check-label" for="mas_50">Más de 10 años</label>
								</div>
							</div>

							<button type="button" class="btn btn-link" id="toggleButtonMobile"
									onclick="toggleOpciones(event,'mas_opciones2','toggleButtonMobile')"
									style="color: #03669c;">Ver más
							</button>
						</div>


						<!-- Fecha de publicación -->
						<h6 class="dropdown-header" style="margin-left: 13px;">Fecha de publicación</h6>

						<div class="dropdown-item" style="margin-left: 13px;">
							<div class="form-check">
								<input class="form-check-input" type="radio" name="fecha_publicacion" id="desde_ayer"
									   value="desde_ayer" checked>
								<label class="form-check-label" for="desde_ayer">Desde ayer</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="fecha_publicacion" id="hoy"
									   value="hoy">
								<label class="form-check-label" for="hoy">Hoy</label>
							</div>
							<div class="form-check">
								<input class="form-check-input" type="radio" name="fecha_publicacion" id="ultima_semana"
									   value="ultima_semana">
								<label class="form-check-label" for="ultima_semana">Última semana</label>
							</div>

							<!-- Opciones adicionales ocultas -->
							<div id="mas_opciones_fecha2" style="display: none;">
								<div class="form-check">
									<input class="form-check-input" type="radio" name="fecha_publicacion"
										   id="ultimos_15_dias" value="ultimos_15_dias">
									<label class="form-check-label" for="ultimos_15_dias">Últimos 15 días</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="fecha_publicacion"
										   id="ultimos_30_dias" value="ultimos_30_dias">
									<label class="form-check-label" for="ultimos_30_dias">Últimos 30 días</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="radio" name="fecha_publicacion"
										   id="ultimos_45_dias" value="ultimos_45_dias">
									<label class="form-check-label" for="ultimos_45_dias">Últimos 45 días</label>
								</div>
							</div>


							<button type="button" class="btn btn-link" id="toggleButtonFechaMobile"
									onclick="toggleOpcionesFecha(event,'mas_opciones_fecha2','toggleButtonFechaMobile')"
									style="color: #03669c">Ver más
							</button>
						</div>


						<!-- Ambientes -->
						<h6 class="dropdown-header" style="margin-left: 13px;">Ambientes</h6>
						<div class="dropdown-item" style="margin-left: 13px;">
							<div id="ambientes-container">
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="bodega" id="ambiente-bodega">
									<label class="form-check-label" for="ambiente-bodega">Bodega</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="patio" id="ambiente-patio">
									<label class="form-check-label" for="ambiente-patio">Patio</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="bano_propio"
										   id="ambiente-bano-propio">
									<label class="form-check-label" for="ambiente-bano-propio">Baño Propio</label>
								</div>
								<div class="form-check">
									<input class="form-check-input" type="checkbox" value="deposito"
										   id="ambiente-deposito">
									<label class="form-check-label" for="ambiente-deposito">Depósito</label>
								</div>

								<div id="more-ambientes2" style="display: none;">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="hidromasaje"
											   id="ambiente-hidromasaje">
										<label class="form-check-label" for="ambiente-hidromasaje">Hidromasaje</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="piscina_cubierta"
											   id="ambiente-piscina-cubierta">
										<label class="form-check-label" for="ambiente-piscina-cubierta">Piscina
											cubierta</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="salon_usos_multiples"
											   id="ambiente-salon-usos-multiples">
										<label class="form-check-label" for="ambiente-salon-usos-multiples">Salón de
											usos múltiples</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="suite"
											   id="ambiente-suite">
										<label class="form-check-label" for="ambiente-suite">Suite</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="turco"
											   id="ambiente-turco">
										<label class="form-check-label" for="ambiente-turco">Turco</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="atico"
											   id="ambiente-atico">
										<label class="form-check-label" for="ambiente-atico">Ático</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="areas_cafeteria"
											   id="ambiente-areas-cafeteria">
										<label class="form-check-label" for="ambiente-areas-cafeteria">Áreas de
											cafetería</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="area_comun"
											   id="ambiente-area-comun">
										<label class="form-check-label" for="ambiente-area-comun">Área común</label>
									</div>
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="cuarto_juegos"
											   id="ambiente-cuarto-juegos">
										<label class="form-check-label" for="ambiente-cuarto-juegos">Cuarto de
											juegos</label>
									</div>
								</div>
							</div>
							<button type="button" class="btn btn-link" id="toggleButtonAmbientesmobile"
									onclick="toggleAmbientes(event,'more-ambientes2','toggleButtonAmbientesmobile')"
									style="color: #004473;">Ver más
							</button>
						</div>

					</form>


				</div>
				<div class="modal-footer">
					<button type="button" class="btn" onclick="limpiarBusqueda()">Limpiar Búsqueda</button>
					<button type="button" class="btn btn-primary" onclick="cerrarDropdown()">Ver Resultados</button>
				</div>
			</div>
		</div>
	</div>

</section>

<!-- Últimas Propiedades-->
<section class="bio px-4 px-md-5 mx-auto" style="max-width: 1400px;">
	<p class="fs-5"><span style="color: #004473;"><?= number_format($pagination['total_results'], 0, '', ',') ?></span> Departamentos en venta en Perú</p>
	<div class="text-center mx-auto" style="max-width: 1400px;">
		<div class="container-fluid px-0">
			<div class="g-5">

            <?php if (empty($properties)) : ?>
                <div class="alert alert-info" role="alert">
                    No se encontraron propiedades que coincidan con tu búsqueda.
                </div>
            <?php else : ?>
                <?php foreach ($properties as $property) : ?>
				<!-- Propiedad -->
				<div class="w-100 mb-5">
					<div class="card1 d-flex flex-column flex-md-row">
						<!-- Carrusel pantallas grandes -->
						<div id="carouselExample1" class="carousel slide d-none d-md-block" data-bs-ride="carousel"
							 style="min-width: 423px; max-width: 423px;">
							<div class="carousel-inner h-100">
                                <?php foreach ($property['images'] as $index => $image): ?>
                                <div class="carousel-item h-100 <?= $index === 0 ? 'active' : '' ?>">
                                    <img src="<?= base_url('uploads/properties/images/' . $image['image_url']) ?>" class="d-block w-100" alt="foto1">
                                </div>
                                <?php endforeach; ?>
							</div>
							<button class="carousel-control-prev" type="button" data-bs-target="#carouselExample1"
									data-bs-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Previous</span>
							</button>
							<button class="carousel-control-next" type="button" data-bs-target="#carouselExample1"
									data-bs-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Next</span>
							</button>
						</div>

						<!-- Carrusel pantallas pequeñas -->
						<div id="carouselExample3" class="carousel slide d-md-none" data-bs-ride="carousel">
							<div class="carousel-inner">
                                <?php foreach ($property['images'] as $index => $image): ?>
                                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                    <img src="<?= base_url('uploads/properties/images/' . $image['image_url']) ?>" class="d-block w-100" alt="foto1">
                                </div>
                                <?php endforeach; ?>
							</div>
							<button class="carousel-control-prev" type="button" data-bs-target="#carouselExample3"
									data-bs-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Previous</span>
							</button>
							<button class="carousel-control-next" type="button" data-bs-target="#carouselExample3"
									data-bs-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Next</span>
							</button>
						</div>

						<!-- Parte texto de los cards -->
						<div class="card-body px-3 py-2">
							<p class="price">S/ <?= number_format($property['price_pen'], 2) ?> - USD <?= number_format($property['price_usd'], 2) ?></p>
							<p class="location"><?= esc($property['address']) ?></p>
							<p class="location">
								<img src="<?= base_url('images/ubibas.svg') ?>" alt="icono ubicación" style="max-width: 20px;"> <?= esc($property['location_name']) ?>,
								<?= esc($property['district']) ?>
							</p>
							<div class="d-flex justify-content-between">
								<div class="d-flex">
									<p class="terr1"><?= esc($property['land_area']) ?>m2</p>
									<p class="terr1"><?= esc($property['bedrooms']) ?> dorm.</p>
									<p class="terr1"><?= esc($property['bathrooms']) ?> baños</p>
								</div>
							</div>
							<p class="text-start"><?= esc($property['description']) ?></p>
							<div class="d-flex align-items-center">
								<div class="d-flex w-100">
									<img src="<?= base_url('images/asesor1.png') ?>" class="advisor-img" alt="Asesor">
								</div>
								<div class="d-flex align-items-end" style="gap: 5px;">
									<a href="tel:+51<?= esc($property['agent_phone']) ?>" class="btnllamar"><img src="<?= base_url('images/btnllamada.svg') ?>"
																					 alt="Propiedad 1"></a>
									<a href="/propiedad/<?= esc($property['property_code']) ?>" class="btn btn-primary">
										Conocer
									</a>
								</div>
							</div>
						</div>
					</div>
				</div>
                <?php endforeach; ?>
            <?php endif; ?>
			</div>
		</div>
	</div>
</section>
<?= $this->endSection() ?>


<?= $this->section('scripts') ?>
<script>
  function evitarCierre(event) {
    event.preventDefault(); // Prevenir el comportamiento predeterminado
  }

  function cerrarDropdown() {
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    const bsDropdown = bootstrap.Dropdown.getInstance(dropdownToggle);
    if (bsDropdown) {
      bsDropdown.hide();
    }
  }

  function limpiarBusqueda() {
    // Limpia los inputs de texto
    const inputs = document.querySelectorAll('.bio input[type="text"], .bio input[type="number"]');
    inputs.forEach(input => {
      input.value = '';
    });

    // Reinicia los select y radios
    const selects = document.querySelectorAll('.bio select');
    selects.forEach(select => {
      select.selectedIndex = 0; // Selecciona la opción por defecto
    });

    const radios = document.querySelectorAll('.bio input[type="radio"]');
    radios.forEach(radio => {
      radio.checked = radio.value === 'todos'; // Reinicia a 'todos'
    });

    const checkboxes = document.querySelectorAll('.bio input[type="checkbox"]');
    checkboxes.forEach(checkbox => {
      checkbox.checked = false; // Desmarcar todos los checkboxes
    });

    // Reiniciar botones de Baños y Estacionamiento
    const buttonsBanos = document.querySelectorAll('.btn-group[role="group"] button');
    buttonsBanos.forEach(button => {
      button.classList.remove('active'); // Quitar clase activa
    });
  }

  // Aquí puedes agregar las funciones seleccionarBano y seleccionarEstacionamiento
  function seleccionarBano(button) {
    // Lógica para seleccionar baños
    resetButtonGroup('btn-group[role="group"]'); // Resetear otros botones
    button.classList.add('active'); // Marcar botón como activo
  }

  function seleccionarEstacionamiento(button) {
    // Lógica para seleccionar estacionamiento
    resetButtonGroup('btn-group[role="group"]'); // Resetear otros botones
    button.classList.add('active'); // Marcar botón como activo
  }

  function resetButtonGroup(groupSelector) {
    const buttons = document.querySelectorAll(groupSelector + ' button');
    buttons.forEach(btn => {
      btn.classList.remove('active'); // Desmarcar todos
    });
  }

  // Agrega eventos a las opciones
  document.querySelectorAll('.dropdown-item a, .dropdown-item select').forEach(item => {
    item.addEventListener('click', evitarCierre);
  });


  //Antiguedad
  function toggleOpciones(event, id_opciones, id_button) {
    event.stopPropagation(); // Evita que el clic cierre el menú desplegable
    const opciones = document.getElementById(id_opciones);
    const button = document.getElementById(id_button);

    if (opciones.style.display === "none" || opciones.style.display === "") {
      opciones.style.display = "block";
      button.textContent = "Ver menos";
    } else {
      opciones.style.display = "none";
      button.textContent = "Ver más";
    }
  }


  // Estacionamiento

  // Función para seleccionar estacionamiento
  function seleccionarEstacionamiento(boton) {
    // Remover la clase 'active' de todos los botones de estacionamiento
    const botonesEstacionamiento = document.querySelectorAll('[aria-label="Estacionamiento"] .btn');
    botonesEstacionamiento.forEach(b => b.classList.remove('active'));
    // Agregar la clase 'active' al botón seleccionado
    boton.classList.add('active');
    // Obtener el valor seleccionado (texto del botón)
    const valorEstacionamiento = boton.innerText;
    console.log(`Estacionamiento seleccionado: ${valorEstacionamiento}`);
    // Aquí puedes agregar la lógica para filtrar o hacer algo con el valor
  }

  // Función para seleccionar baños
  function seleccionarBano(boton) {
    // Remover la clase 'active' de todos los botones de baños
    const botonesBanos = document.querySelectorAll('[aria-label="Baños"] .btn');
    botonesBanos.forEach(b => b.classList.remove('active'));
    // Agregar la clase 'active' al botón seleccionado
    boton.classList.add('active');
    // Obtener el valor seleccionado (texto del botón)
    const valorBanos = boton.innerText;
    console.log(`Baños seleccionados: ${valorBanos}`);
    // Aquí puedes agregar la lógica para filtrar o hacer algo con el valor
  }

  //Ver mapa
  function verMapa() {
    alert("Abriendo el mapa...");
    // Aquí puedes agregar lógica para mostrar el mapa
  }

  //Boton crear alerta
  function crearAlerta() {
    alert("¡Alerta creada con éxito!");
    // Aquí puedes agregar lógica para crear una alerta personalizada
  }

  //Ver mas ambientes
  function toggleAmbientes(event, id_opciones, id_button) {
    event.stopPropagation(); // Evita que el clic cierre el menú desplegable
    const opciones = document.getElementById(id_opciones);
    const button = document.getElementById(id_button);
    if (opciones.style.display === "none" || opciones.style.display === "") {
      opciones.style.display = "block";
      button.textContent = "Ver menos";
    } else {
      opciones.style.display = "none";
      button.textContent = "Ver más";
    }
  }

  // Función para mostrar/ocultar fechas
  function toggleOpcionesFecha(event, id_opciones, id_button) {
    event.stopPropagation(); // Evita que el clic cierre el menú desplegable
    const opciones = document.getElementById(id_opciones);
    const button = document.getElementById(id_button);
    if (opciones.style.display === "none" || opciones.style.display === "") {
      opciones.style.display = "block";
      button.textContent = "Ver menos";
    } else {
      opciones.style.display = "none";
      button.textContent = "Ver más";
    }
  }

  // Capturar el valor seleccionado del grupo de botones de fecha
  const radioButtons = document.querySelectorAll('input[name="fecha_publicacion"]');

  radioButtons.forEach(radio => {
    radio.addEventListener('change', function () {
      if (this.checked) {
        const valorSeleccionado = this.value;
        console.log(`Fecha de publicación seleccionada: ${valorSeleccionado}`);
        // Aquí puedes agregar lógica para usar este valor, por ejemplo, filtrar una lista
      }
    });
  });

  function keepDropdownOpen(event) {
    event.preventDefault(); // Prevenir el comportamiento predeterminado
    // Aquí puedes agregar cualquier lógica adicional que necesites al seleccionar la opción
    console.log(event.target.textContent + ' seleccionada');
  }
</script>
<?= $this->endSection() ?>
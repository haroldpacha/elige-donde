<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- PAntallas lg -->
<section class="banner bio row d-none d-sm-block ">
	<div class="mx-auto" style="max-width: 1000px;">
		<div class="container text-center">
			<h1 class="banner-title fw-semibold pt-4 pb-4">Encuentra el lugar ideal</h1>
			<div class="btn-group" role="group" aria-label="Basic radio toggle button group">
				<input type="radio" class="btn-check" name="options" id="venta" autocomplete="off" checked>
				<label class="btn btn-custom" for="venta">Venta</label>

				<input type="radio" class="btn-check" name="options" id="alquiler" autocomplete="off">
				<label class="btn btn-custom" for="alquiler">Alquiler</label>

				<input type="radio" class="btn-check" name="options" id="anticresis" autocomplete="off">
				<label class="btn btn-custom" for="anticresis">Anticresis</label>
			</div>
		</div>


		<div class="custom-container mt-5">

			<div class="d-flex align-items-center flex-grow-1 flex-fill">

				<!-- Tipo de inmueble -->
				<div class="custom-button-group" style="width: 22%; padding-left: 5px;">
					<select id="tipo-inmueble" class="form-select" style="border-color: transparent;" required>
						<option selected disabled value="">Tipo de inmueble</option>
						<?php foreach ($property_types as $type): ?>
							<option value="<?= $type['id'] ?>"><?= esc($type['name']) ?></option>
						<?php endforeach; ?>
					</select>
				</div>

				<div>
					<hr class="lineaVertical" width="2px" style="background-color: #0a0a0a; height: 40px;">
				</div>

				<!-- Lugar -->
				<div style="width: 40%;">
					<input type="text" id="lugar" placeholder="¿En dónde buscas?" class="form-control"
						   style="border-color: transparent;">
				</div>

				<!-- Divider -->
				<div>
					<hr class="lineaVertical" style="width: 2px; background-color: #0a0a0a; height: 40px;">
				</div>

				<!-- Precio Mínimo -->
				<div style="width: 17%;">
					<input type="number" id="precio-min" placeholder="Precio mínimo" class="form-control"
						   style="border-color: transparent; white-space: nowrap; width: 100%;">
				</div>

				<div>
					<hr class="lineaVertical" width="2px" style="background-color: #0a0a0a; height: 40px;">
				</div>

				<!-- Precio Máximo -->
				<div style="width: 17%;">
					<input type="number" id="precio-max" placeholder="Precio máximo" class="form-control"
						   style="border-color: transparent;">
				</div>
			</div>


			<!-- Botón Buscar -->
			<div class=" text-center">
				<button class="btn-azul rounded-pill" type="button" onclick="buscarPropiedadesDesktop()">Buscar</button>
			</div>
		</div>

		<div class="mt-5 d-flex gap-3 justify-content-center flex-shrink-1">
			<button class="btn-cerca">Buscar cerca de mí</button>
			<button class="btn-cerca">Propiedades recientes</button>
			<button class="btn-cerca">Bajaron de precio</button>
		</div>

	</div>
</section>

<!-- PAntallas sm -->
<section class="d-block d-sm-none  bio banner">
	<div class="text-center px-2">
		<h3 class="banner-title">Encuentra el lugar ideal</h3>

		<!-- Selección de tipo de inmueble -->
		<div class="custom-button-group w-100 mb-3">
			<select id="tipo-inmueble" class="form-select "
					style="background-color: #03669c; border-color: transparent; color: #ffffff;">
				<?php foreach ($property_types as $type): ?>
					<option value="<?= $type['id'] ?>"><?= esc($type['name']) ?></option>
				<?php endforeach; ?>
			</select>
		</div>

		<!-- Campo para el lugar -->
		<div class="w-100 mb-3">
			<input type="text" id="lugar" placeholder="Elegir dirección" class="form-control ctext"
				   style=" background-color: #03669c; border-color: transparent;">
		</div>

		<!-- Campos para el monto y selección de moneda -->
		<div class="d-flex justify-content-center align-items-center rounded mb-3 text-white" style="max-width: 600px;">
			<div class="me-3">
				<input type="number" class="form-control ctext" id="monto-min" placeholder="Monto mínimo"
					   style="background-color: #03669c; border-color: transparent;">
			</div>
			<div class="me-3">
				<input type="number" class="form-control ctext" id="monto-max" placeholder="Monto máximo"
					   style="background-color: #03669c; border-color: transparent;">
			</div>
			<div class="me-3">
				<select class="form-select " id="moneda"
						style="min-width: 100px; background-color: #03669c; border-color: transparent; color: #ffffff;">
					<option value="soles" style="color: #ffffff;">Soles</option>
					<option value="dolares" style="color: #ffffff;">Dólares</option>
				</select>
			</div>
		</div>

		<!-- Botones de acción -->
		<button class="btn-azul w-100 mb-3" onclick="buscarPropiedades()">Buscar</button>
		<button class="btn btn-orange w-100" onclick="buscarCercaDeMi()">Buscar alrededor mío</button>

	</div>

</section>

<!-- Banner -->
<a href="https://api.whatsapp.com/send?phone=<?= esc($settings['social_whatsapp'] ?? '') ?>&text=Hola.%20Quiero%20m%C3%A1s%20informaci%C3%B3n%20de%20Elige%20Donde."
   target="_blank">
	<img src="images/banner-desktop.jpg" alt="" class="w-100 d-none d-md-inline">
	<img src="images/banner-mobile.jpg" alt="" class="w-100 d-inline d-md-none">
</a>

<!-- Últimas Propiedades-->
<section class="ultimas-propiedades my-1 bio px-4 px-md-5">
	<div class="text-center mx-auto" style="max-width: 1400px;">
		<h2 class="section-title">Últimas propiedades</h2>
		<div class="line"></div>
		<div class="container-fluid px-0">
			<div class="row g-5">
				<?php foreach ($featured_properties as $property): ?>
				<!-- Card 1 -->
				<div class="col-12 col-sm-6 col-md-4 col-lg-3">
					<div class="card position-relative">
						<!-- Cuadro "Vendido" -->
						<div class="sold-badge position-absolute w-100 d-flex justify-content-center align-items-center"
							 style="top: 20%; left: 10px;">
							<span class="text-center text-danger"
								  style="border: 4px solid red; padding: 5px 20px; background-color: white; color: red; font-weight: 900; border-radius: 5px; font-size: x-large;">
								VENDIDO
							</span>
						</div>
						<img src="<?= base_url('uploads/properties/images/' . $property['main_image']['image_url']) ?>" class="card-img-top rounded-top" alt="<?= esc($property['title']) ?>">
						<div class="card-body">
							<div class="id-property ">
								ID: <?= $property['property_code'] ?>
							</div>
							<p class="price ">S/ <?= number_format($property['price_pen'], 2) ?> - USD <?= number_format($property['price_usd'], 2) ?></p>
							<p class="location"><i class="fas fa-map-marker-alt"></i> <?= esc($property['location_name']) ?>, <?= esc($property['district']) ?></p>
							<hr class="separator">
							<div class="d-flex justify-content-between">
								<div class="d-flex">
									<img src="images/tlibre.svg" alt="Terreno libre" class="icono-area"
										 style="max-width: 40px !important; padding-right: 10px;">
									<div class="justify-content-between row" style="color: #666666; font-weight: 600;">
										<p class="terr" style="display: block;">Área de terreno:</p>
										<p class="terr" style="color: black; display: block;"><?= number_format($property['land_area']) ?> m²</p>
									</div>
								</div>
								<div class="d-flex">
									<img src="images/Construction.svg" alt="Terreno libre" class="icono-area"
										 style="max-width: 40px !important; padding-right: 10px;">
									<div class="justify-content-between row" style="color: #666666; font-weight: 600;">
										<p class="terr" style="display: block;">Área de terreno:</p>
										<p class="terr" style="color: black; display: block;"><?= number_format($property['built_area']) ?> m²</p>
									</div>
								</div>
							</div>
							<a href="<?= base_url('propiedad/' . $property['property_code']) ?>" class="btn btn-primary mt-3">
								Conocer
							</a>
							<div class="py-4">
							</div>
						</div>
						<div class="advisor mt-2 position-absolute w-100 z-3" style="bottom: -35px;">
							<?php if ($property['agent_photo']): ?>
								<img src="<?= base_url('assets/images/agents/' . $property['agent_photo']) ?>"
										alt="<?= esc($property['agent_first_name']) ?>"
										class="advisor-img">
							<?php else: ?>
								<img src="<?= base_url('assets/images/agent-placeholder.jpg') ?>"
										alt="Asesor" class="advisor-img">
							<?php endif; ?>
						</div>
					</div>
					<div class="py-4">
					</div>
				</div>
				<?php endforeach; ?>
			</div>
		</div>

		<p class="mt-4 fs-4 mb-1">
			Buscar entre más de <span class="text-primary">1,130</span> propiedades
		</p>

		<div>
			<a href="<?= base_url('buscar-propiedades') ?>" class="btn btn-primary btn-lg px-4">
				Ver más propiedades
			</a>
		</div>

	</div>
</section>

<!-- patnallas grandes -->
<section class="d-none d-sm-block encuentra-tu-lugar bio mx-auto"
		 style="background-image: url('./images/Banner2.png');">
	<div style="max-width: 1400px;">
		<div class="square overlay d-flex flex-column justify-content-end">
			<div class="container text-white">
				<h2 class="titulo">ENCUENTRA TU LUGAR IDEAL</h2>
				<p class="subtitulo">Más de <span style="color: #004473 !important;">3,084</span> Asesores calificados
					para tu búsqueda.</p>
				<a href="<?= base_url('contacto') ?>" class="btnye w-100">
					Contactanos
				</a>
			</div>
		</div>
	</div>
</section>

<section class="d-none d-sm-block encuentra-tu-lugar bio mx-auto"
		 style="background-image: url('./images/postula.png'); ">
	<div style="max-width: 1400px;">
		<div class="square overlay d-flex flex-column justify-content-end">
			<div class="container text-white">
				<h2 class="titulo">NUESTRAS OFICINAS EN TODO EL PERÚ</h2>
				<p class="subtitulo">Estamos cerca de ti, busca nuestras oficinas</p>
				<button class="btnye mt-3 w-100">Buscar Oficina</button>
			</div>
		</div>
	</div>
</section>

<!-- pantallas chicas -->
<section class="d-block d-sm-none encuentra-tu-lugar1 bio"
		 style="background-image: url('./images/Banner2.png'); object-position: left;">
	<div class="overlay d-flex flex-column justify-content-end">
		<div class="text-white">
			<h2 class="titulo">ENCUENTRA TU LUGAR IDEAL</h2>
			<p class="subtitulo">Más de <span style="color: #004473 !important;">3,084</span> Asesores calificados para
				tu búsqueda.</p>
			<button class="btnye w-100">Contactanos</button>
		</div>
	</div>
</section>

<section class="d-block d-sm-none encuentra-tu-lugar1 bio" style="background-image: url('./images/postula.png');">
	<div class="overlay d-flex flex-column justify-content-end" max-width: 1400px;>
		<div class="text-white">
			<h2 class="titulo">NUESTRAS OFICINAS EN TODO EL PERÚ</h2>
			<p class="subtitulo">Estamos cerca de ti, busca nuestras oficinas</p>
			<button class="btnye mt-3 w-100">Buscar Oficina</button>
		</div>
	</div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  function buscarPropiedadesDesktop() {
	// Obtener valores de los filtros
	const tipoInmueble = document.getElementById('tipo-inmueble').value;
	const lugar = document.getElementById('lugar').value;
	const precioMin = document.getElementById('precio-min').value;
	const precioMax = document.getElementById('precio-max').value;
	// Obtener opción de transacción seleccionada
	let transactionType = '';
	if (document.getElementById('venta').checked) transactionType = 'venta';
	else if (document.getElementById('alquiler').checked) transactionType = 'alquiler';
	else if (document.getElementById('anticresis').checked) transactionType = 'anticresis';

	// Construir la URL de búsqueda
	let url = 'buscar-propiedades?';
	const params = [];
	if (tipoInmueble) params.push('property_type_id=' + encodeURIComponent(tipoInmueble));
	if (lugar) params.push('search=' + encodeURIComponent(lugar));
	if (precioMin) params.push('price_min=' + encodeURIComponent(precioMin));
	if (precioMax) params.push('price_max=' + encodeURIComponent(precioMax));
	if (transactionType) params.push('transaction_type=' + encodeURIComponent(transactionType));

	// Redirigir a la página de resultados
	window.location.href = url + params.join('&');
  }

  function buscarCercaDeMi() {
	alert("Buscando propiedades cerca de tu ubicación...");
	// Aquí puedes agregar lógica adicional para manejar la búsqueda cerca de la ubicación
  }

  function verPropiedadesRecientes() {
	alert("Mostrando propiedades recientes...");
	// Aquí puedes agregar lógica adicional para manejar la visualización de propiedades recientes
  }

  function verBajaronDePrecio() {
	alert("Mostrando propiedades que bajaron de precio...");
	// Aquí puedes agregar lógica adicional para manejar la visualización de propiedades con descuento
  }

  function buscarPropiedades() {
	const tipoInmueble = document.getElementById('tipo-inmueble').value;
	const lugar = document.getElementById('lugar').value;
	const montoMin = document.getElementById('monto-min').value;
	const montoMax = document.getElementById('monto-max').value;
	const moneda = document.getElementById('moneda').value;

	alert(`Buscando ${tipoInmueble} en ${lugar} con un monto entre ${montoMin} y ${montoMax} en ${moneda}`);
	// Aquí puedes agregar la lógica para realizar la búsqueda con los valores ingresados
  }
</script>

<!-- Social media links div at the mid start of the page -->
<div class="position-fixed h-100 start-0 d-none d-md-block" style="z-index: 1000; top: 27vh;">
	<div class="py-3 d-flex gap-2 flex-column align-items-center justify-content-center" style="background-color: #004473; padding: 10px; border-radius: 0 10px 10px 0;">
		<a href="https://api.whatsapp.com/send?phone=<?= esc($settings['social_whatsapp'] ?? '') ?>&text=Hola.%20Quiero%20m%C3%A1s%20informaci%C3%B3n%20de%20Elige%20Donde."
		   target="_blank"
		   class="rounded-circle text-white position-relative"
		   style="background-color: white; width: 40px; height: 40px;">
			<div class="position-absolute d-flex align-items-center justify-content-center h-100 w-100">
				<i class="fab fa-whatsapp text-primary fs-3"></i>
			</div>
		</a>

		<a href="<?= esc($settings['social_facebook'] ?? '') ?>"
		   target="_blank"
		   class="rounded-circle text-white position-relative"
		   style="background-color: white; width: 40px; height: 40px;">
			<div class="position-absolute d-flex align-items-center justify-content-center h-100 w-100">
				<i class="fab fa-facebook-f text-primary fs-5"></i>
			</div>
		</a>
		<a href="<?= esc($settings['social_instagram'] ?? '') ?>"
		   target="_blank"
		   class="rounded-circle text-white position-relative"
		   style="background-color: white; width: 40px; height: 40px;">
			<div class="position-absolute d-flex align-items-center justify-content-center h-100 w-100">
				<i class="fab fa-instagram text-primary fs-4"></i>
			</div>
		</a>
		<a href="<?= esc($settings['social_tiktok'] ?? '') ?>"
		   target="_blank"
		   class="rounded-circle text-white position-relative"
		   style="background-color: white; width: 40px; height: 40px;">
			<div class="position-absolute d-flex align-items-center justify-content-center h-100 w-100">
				<i class="fab fa-tiktok text-primary fs-5"></i>
			</div>
		</a>
		<div class="text-white fw-semibold" style="writing-mode: vertical-lr; transform: rotate(180deg);">
			Contáctanos ------
		</div>
	</div>
</div>
<?= $this->endSection() ?>

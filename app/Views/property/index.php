<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Sección de filtrado de búsqueda -->
<section class="filter-section py-3 px-4 px-lg-5 bg-light d-none d-sm-block">
    <div class="container" style="max-width: 1400px;">
        <form action="<?= site_url('buscar-propiedades') ?>" method="GET" class="d-flex wrap justify-content-center" style="gap: 5px;">

            <!-- Input de búsqueda -->
            <div class="bio d-none d-md-block w-50">
                <div class="input-group">
                    <input type="text" name="search" class="form-control custom-input businput"
                           placeholder="Ingresa departamentos o distritos" aria-label="Buscar" value="<?= esc($filters['search'] ?? '') ?>">
                </div>
            </div>

            <!-- Botón de filtro de tipo -->
            <div class="bio d-none d-md-block">
                <select name="transaction_type" class="form-select custom-button" aria-label="Filtrar por tipo">
                    <option value="">Operación</option>
                    <option value="venta" <?= (isset($filters['transaction_type']) && $filters['transaction_type'] === 'venta') ? 'selected' : '' ?>>Venta</option>
                    <option value="alquiler" <?= (isset($filters['transaction_type']) && $filters['transaction_type'] === 'alquiler') ? 'selected' : '' ?>>Alquiler</option>
                    <option value="anticresis" <?= (isset($filters['transaction_type']) && $filters['transaction_type'] === 'anticresis') ? 'selected' : '' ?>>Anticresis</option>
                </select>
            </div>

            <!-- Botón de filtro de propiedad -->
            <div class="d-none d-md-block">
                <select name="property_type_id" class="form-select custom-button" aria-label="Filtrar por propiedad">
                    <option value="">Tipo de Propiedad</option>
                    <?php foreach ($property_types as $type) : ?>
                        <option value="<?= esc($type['id']) ?>" <?= (isset($filters['property_type_id']) && (int)$filters['property_type_id'] === (int)$type['id']) ? 'selected' : '' ?>><?= esc($type['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Filtro de dormitorios -->
            <div class="bio d-none d-lg-block" id="filtro-dormitorios-desktop">
                <div class="btn-group">
                    <button type="button" class="btn custom-button dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside"
                            aria-haspopup="true" aria-expanded="false">
                        Dormitorios
                    </button>
                    <div class="dropdown-menu p-2" style="width: 200px;">
                        <div class="mb-2">
                            <label for="bedrooms_desktop" class="form-label">Dormitorios (mínimo)</label>
                            <select name="bedrooms" id="bedrooms_desktop" class="form-select" aria-label="Dormitorios mínimo">
                                <option value="">Cualquiera</option>
                                <option value="1" <?= (isset($filters['bedrooms']) && $filters['bedrooms'] == '1') ? 'selected' : '' ?>>1+</option>
                                <option value="2" <?= (isset($filters['bedrooms']) && $filters['bedrooms'] == '2') ? 'selected' : '' ?>>2+</option>
                                <option value="3" <?= (isset($filters['bedrooms']) && $filters['bedrooms'] == '3') ? 'selected' : '' ?>>3+</option>
                                <option value="4" <?= (isset($filters['bedrooms']) && $filters['bedrooms'] == '4') ? 'selected' : '' ?>>4+</option>
                                <option value="5" <?= (isset($filters['bedrooms']) && $filters['bedrooms'] == '5') ? 'selected' : '' ?>>5+</option>
                            </select>
                        </div>
                        <div class="dropdown-divider"></div>
                        <div class="d-flex justify-content-end mt-2">
                            <button type="submit" class="btn btn-primary btn-sm">Aplicar</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filtro de precios -->
            <div class="bio d-none d-xl-block" id="filtro-precios-desktop">
                <div class="btn-group">
                    <button type="button" class="btn custom-button dropdown-toggle" data-bs-toggle="dropdown"
                            aria-haspopup="true" data-bs-auto-close="outside" aria-expanded="false">
                        Precio
                    </button>
                    <div class="dropdown-menu p-3" style="width: 300px;">
                        <div class="mb-2">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="currency" id="soles_desktop" value="pen" <?= (isset($filters['currency']) && $filters['currency'] === 'pen') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="soles_desktop">Soles</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="currency" id="dolares_desktop" value="usd" <?= (!isset($filters['currency']) || $filters['currency'] === 'usd') ? 'checked' : '' ?>>
                                <label class="form-check-label" for="dolares_desktop">Dólares</label>
                            </div>
                        </div>
                        <div class="d-flex w-100 align-items-center">
                            <div class="col-md-6 pe-1">
                                <input name="price_min" type="number" class="form-control" placeholder="Desde" value="<?= esc($filters['price_min'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 ps-1">
                                <input name="price_max" type="number" class="form-control" placeholder="Hasta" value="<?= esc($filters['price_max'] ?? '') ?>">
                            </div>
                        </div>
                        <div class="d-flex justify-content-end mt-2">
                            <button type="submit" class="btn btn-primary btn-sm">Aplicar</button>
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
                        <!-- Baños -->
                        <h6 class="dropdown-header">Baños (mínimo)</h6>
                        <div class="btn-group w-100 mb-3" role="group" aria-label="Baños">
                            <input type="radio" class="btn-check" name="bathrooms" id="bath1" value="1" autocomplete="off" <?= (isset($filters['bathrooms']) && $filters['bathrooms'] == '1') ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="bath1">1+</label>

                            <input type="radio" class="btn-check" name="bathrooms" id="bath2" value="2" autocomplete="off" <?= (isset($filters['bathrooms']) && $filters['bathrooms'] == '2') ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="bath2">2+</label>

                            <input type="radio" class="btn-check" name="bathrooms" id="bath3" value="3" autocomplete="off" <?= (isset($filters['bathrooms']) && $filters['bathrooms'] == '3') ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="bath3">3+</label>

                            <input type="radio" class="btn-check" name="bathrooms" id="bath4" value="4" autocomplete="off" <?= (isset($filters['bathrooms']) && $filters['bathrooms'] == '4') ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="bath4">4+</label>
                        </div>
                        
                        <!-- Aquí se podrían agregar más filtros que el backend soporte -->

                        <div class="dropdown-divider"></div>
                        <div class="d-flex justify-content-between mt-2">
                            <a href="<?= site_url('buscar-propiedades') ?>" class="btn">Limpiar</a>
                            <button type="submit" class="btn btn-primary">Ver Resultados</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bio">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
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
                <form action="<?= site_url('buscar-propiedades') ?>" method="GET">
                    <div class="modal-header">
                        <h5 class="modal-title" id="filtrosModalLabel">Filtros</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                        <!-- Input de búsqueda -->
                        <div class="bio mb-3" id="filtro-busqueda-mobile">
                            <h6 class="dropdown-header">Ubicación</h6>
                            <div class="input-group">
                                <input type="text" name="search" class="form-control custom-input businput" aria-label="Buscar" value="<?= esc($filters['search'] ?? '') ?>">
                            </div>
                        </div>

                        <!-- Tipo de Operación -->
                        <div class="bio mb-3" id="filtro-tipo-mobile">
                            <h6 class="dropdown-header">Tipo de Operación</h6>
                            <select name="transaction_type" class="form-select custom-button" aria-label="Filtrar por tipo">
                                <option value="">Cualquiera</option>
                                <option value="venta" <?= (isset($filters['transaction_type']) && $filters['transaction_type'] === 'venta') ? 'selected' : '' ?>>Venta</option>
                                <option value="alquiler" <?= (isset($filters['transaction_type']) && $filters['transaction_type'] === 'alquiler') ? 'selected' : '' ?>>Alquiler</option>
                                <option value="anticresis" <?= (isset($filters['transaction_type']) && $filters['transaction_type'] === 'anticresis') ? 'selected' : '' ?>>Anticresis</option>
                            </select>
                        </div>

                        <!-- Tipo de Propiedad -->
                        <div class="bio mb-3" id="filtro-propiedad-mobile">
                            <h6 class="dropdown-header">Tipo de Propiedad</h6>
                            <select name="property_type_id" class="form-select custom-button" aria-label="Filtrar por propiedad">
                                <option value="">Cualquiera</option>
                                <?php foreach ($property_types as $type) : ?>
                                    <option value="<?= esc($type['id']) ?>" <?= (isset($filters['property_type_id']) && (int)$filters['property_type_id'] === (int)$type['id']) ? 'selected' : '' ?>><?= esc($type['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Dormitorios -->
                        <div class="bio mb-3" id="filtro-dormitorios-mobile">
                            <h6 class="dropdown-header">Dormitorios (mínimo)</h6>
                            <select name="bedrooms" class="form-select" aria-label="Dormitorios mínimo">
                                <option value="">Cualquiera</option>
                                <option value="1" <?= (isset($filters['bedrooms']) && $filters['bedrooms'] == '1') ? 'selected' : '' ?>>1+</option>
                                <option value="2" <?= (isset($filters['bedrooms']) && $filters['bedrooms'] == '2') ? 'selected' : '' ?>>2+</option>
                                <option value="3" <?= (isset($filters['bedrooms']) && $filters['bedrooms'] == '3') ? 'selected' : '' ?>>3+</option>
                                <option value="4" <?= (isset($filters['bedrooms']) && $filters['bedrooms'] == '4') ? 'selected' : '' ?>>4+</option>
                                <option value="5" <?= (isset($filters['bedrooms']) && $filters['bedrooms'] == '5') ? 'selected' : '' ?>>5+</option>
                            </select>
                        </div>

                        <!-- Precios -->
                        <div class="bio mb-3" id="filtro-precios-mobile">
                            <h6 class="dropdown-header">Precio</h6>
                            <div class="d-flex justify-content-around mb-2">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="currency" id="soles_mobile" value="pen" <?= (isset($filters['currency']) && $filters['currency'] === 'pen') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="soles_mobile">Soles</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="currency" id="dolares_mobile" value="usd" <?= (!isset($filters['currency']) || $filters['currency'] === 'usd') ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="dolares_mobile">Dólares</label>
                                </div>
                            </div>
                            <div class="d-flex">
                                <div class="pe-1">
                                    <input name="price_min" type="number" class="form-control" placeholder="Desde" value="<?= esc($filters['price_min'] ?? '') ?>">
                                </div>
                                <div class="ps-1">
                                    <input name="price_max" type="number" class="form-control" placeholder="Hasta" value="<?= esc($filters['price_max'] ?? '') ?>">
                                </div>
                            </div>
                        </div>

                        <!-- Baños -->
                        <h6 class="dropdown-header">Baños (mínimo)</h6>
                        <div class="btn-group w-100" role="group" aria-label="Baños">
                            <input type="radio" class="btn-check" name="bathrooms" id="bath1_mobile" value="1" autocomplete="off" <?= (isset($filters['bathrooms']) && $filters['bathrooms'] == '1') ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="bath1_mobile">1+</label>

                            <input type="radio" class="btn-check" name="bathrooms" id="bath2_mobile" value="2" autocomplete="off" <?= (isset($filters['bathrooms']) && $filters['bathrooms'] == '2') ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="bath2_mobile">2+</label>

                            <input type="radio" class="btn-check" name="bathrooms" id="bath3_mobile" value="3" autocomplete="off" <?= (isset($filters['bathrooms']) && $filters['bathrooms'] == '3') ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="bath3_mobile">3+</label>

                            <input type="radio" class="btn-check" name="bathrooms" id="bath4_mobile" value="4" autocomplete="off" <?= (isset($filters['bathrooms']) && $filters['bathrooms'] == '4') ? 'checked' : '' ?>>
                            <label class="btn btn-outline-primary" for="bath4_mobile">4+</label>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <a href="<?= site_url('buscar-propiedades') ?>" class="btn">Limpiar</a>
                        <button type="submit" class="btn btn-primary">Ver Resultados</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Últimas Propiedades-->
<section class="bio px-4 px-md-5 mx-auto" style="max-width: 1400px;">
	<p class="fs-5"><span style="color: #004473;"><?= number_format($pagination['total_results'], 0, '', ',') ?></span> resultados</p>
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
						<div id="carousel-<?= esc($property['id']) ?>-desktop" class="carousel slide d-none d-md-block" data-bs-ride="carousel"
							 style="min-width: 423px; max-width: 423px;">
							<div class="carousel-inner h-100">
                                <?php if (!empty($property['images'])): ?>
                                    <?php foreach ($property['images'] as $index => $image): ?>
                                    <div class="carousel-item h-100 <?= $index === 0 ? 'active' : '' ?>">
                                        <img src="<?= base_url('uploads/properties/images/' . $image['image_url']) ?>" class="d-block w-100" alt="foto1">
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="carousel-item active h-100">
                                        <img src="<?= base_url('images/logo-base.png') ?>" class="d-block w-100" alt="Sin imagen">
                                    </div>
                                <?php endif; ?>
							</div>
							<button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?= esc($property['id']) ?>-desktop"
									data-bs-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Previous</span>
							</button>
							<button class="carousel-control-next" type="button" data-bs-target="#carousel-<?= esc($property['id']) ?>-desktop"
									data-bs-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Next</span>
							</button>
						</div>

						<!-- Carrusel pantallas pequeñas -->
						<div id="carousel-<?= esc($property['id']) ?>-mobile" class="carousel slide d-md-none" data-bs-ride="carousel">
							<div class="carousel-inner">
                                <?php if (!empty($property['images'])): ?>
                                    <?php foreach ($property['images'] as $index => $image): ?>
                                    <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                                        <img src="<?= base_url('uploads/properties/images/' . $image['image_url']) ?>" class="d-block w-100" alt="foto1">
                                    </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <div class="carousel-item active">
                                        <img src="<?= base_url('images/logo-base.png') ?>" class="d-block w-100" alt="Sin imagen">
                                    </div>
                                <?php endif; ?>
							</div>
							<button class="carousel-control-prev" type="button" data-bs-target="#carousel-<?= esc($property['id']) ?>-mobile"
									data-bs-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="visually-hidden">Previous</span>
							</button>
							<button class="carousel-control-next" type="button" data-bs-target="#carousel-<?= esc($property['id']) ?>-mobile"
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
							<p class="text-start"><?= esc(word_limiter($property['description'], 20)) ?></p>
							<div class="d-flex align-items-center">
                                <?php if(isset($property['primary_agent'])): ?>
								<div class="d-flex w-100">
									<img src="<?= base_url('assets/images/agents/'. $property['agent_photo']) ?>" class="advisor-img" alt="Asesor">
								</div>
								<div class="d-flex align-items-end" style="gap: 5px;">
									<a href="tel:+51<?= esc($property['agent_phone']) ?>" class="btnllamar"><img src="<?= base_url('images/btnllamada.svg') ?>"
																					 alt="Propiedad 1"></a>
									<a href="/propiedad/<?= esc($property['property_code']) ?>" class="btn btn-primary">
										Conocer
									</a>
								</div>
                                <?php endif; ?>
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
    document.addEventListener('DOMContentLoaded', function () {
        // Función para limpiar el formulario de búsqueda
        function limpiarBusqueda(form) {
            if (!form) return;

            // Limpiar inputs de texto y número
            form.querySelectorAll('input[type="text"], input[type="number"]').forEach(input => {
                input.value = '';
            });

            // Reiniciar selects
            form.querySelectorAll('select').forEach(select => {
                select.selectedIndex = 0;
            });

            // Desmarcar radios y checkboxes
            form.querySelectorAll('input[type="radio"], input[type="checkbox"]').forEach(input => {
                input.checked = false;
            });

            // Quitar clase 'active' de botones en grupos
            form.querySelectorAll('.btn-group .btn').forEach(button => {
                button.classList.remove('active');
            });
            
            // Redirigir a la página sin filtros
            window.location.href = "<?= site_url('buscar-propiedades') ?>";
        }

        // Asignar evento al botón de limpiar en el dropdown de desktop
        const desktopForm = document.querySelector('.filter-section form');
        const desktopCleanBtn = desktopForm.querySelector('.btn.btn-link, .btn[onclick*="limpiarBusqueda"]');
        if(desktopCleanBtn) {
            desktopCleanBtn.addEventListener('click', function(e) {
                e.preventDefault();
                limpiarBusqueda(desktopForm);
            });
        }

        // Asignar evento al botón de limpiar en el modal de mobile
        const mobileForm = document.querySelector('#filtrosModal form');
        const mobileCleanBtn = mobileForm.querySelector('.btn.btn-link, .btn[onclick*="limpiarBusqueda"]');
        if(mobileCleanBtn) {
            mobileCleanBtn.addEventListener('click', function(e) {
                e.preventDefault();
                limpiarBusqueda(mobileForm);
            });
        }
    });

    function toggleOpciones(event, id_opciones, id_button) {
        event.stopPropagation();
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
</script>
<?= $this->endSection() ?>

<style>
    .card1 {
        border: 1px solid #ccc;
        border-radius: 8px;
        overflow: hidden;
        transition: box-shadow 0.3s;
    }

    .card1:hover {
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .card1 .carousel-item img {
        height: 280px;
        object-fit: cover;
    }

    .card-body {
        padding: 1rem;
    }

    .price {
        font-size: 1.25rem;
        font-weight: bold;
        color: #333;
    }

    .location {
        font-size: 1rem;
        color: #666;
    }

    .terr1 {
        background-color: #f2f2f2;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        margin-right: 0.5rem;
        font-size: 0.875rem;
    }

    .advisor-img {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        margin-right: 1rem;
    }

    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }

    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #0056b3;
    }

    .btnllamar {
        display: inline-block;
    }

    .btnllamar img {
        width: 40px;
        height: 40px;
    }

    .filter-section {
        background-color: #f8f9fa;
    }

    .custom-input {
        border-radius: 20px;
        border: 1px solid #ced4da;
        padding: 0.5rem 1rem;
    }

    .custom-button {
        border-radius: 20px;
        background-color: #fff;
        border: 1px solid #ced4da;
        padding: 0.5rem 1rem;
    }

    .custom-button:hover {
        background-color: #e9ecef;
    }

    .dropdown-menu {
        border-radius: 15px;
    }

    .anchofil {
        width: 500px;
    }

    @media (max-width: 768px) {
        .anchofil {
            width: auto;
        }
    }

    .btn-check + .btn-outline-primary {
        color: #0d6efd;
        border-color: #0d6efd;
    }

    .btn-check:checked + .btn-outline-primary {
        color: #fff;
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
</style>

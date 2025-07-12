<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- Fotos-->
<section class="bio">
	<div class="mx-auto" style="max-width: 1400px;">
		<div class="container mt-4 d-none d-md-block">
			<div class="row">
				<div class="col-lg-6">
					<img id="mainImage" src="<?= base_url('uploads/properties/images/' . $images[0]['image_url']) ?>" alt="Imagen Grande" class="main-image">
				</div>
				<div class="col-lg-6">
					<div class="row">
                        <?php foreach ($images as $index => $image): ?>
                        <?php if($index == 0) continue; ?>
						<div class="col-6 mb-2">
							<img src="<?= base_url('uploads/properties/images/' . $image['image_url']) ?>" alt="Miniatura 1" class="thumbnail"
								 data-bs-toggle="modal" data-bs-target="#imageModal"
								 onclick="setModalImage('<?= base_url('uploads/properties/images/' . $image['image_url']) ?>')">
						</div>
                        <?php endforeach; ?>
					</div>
				</div>
			</div>
			<!-- Modal fotos-->
			<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true"
				 data-bs-backdrop="true">
				<div class="modal-dialog modal-lg modal-dialog-centered">
					<div class="modal-content" style="background-color: transparent;">
						<div class="modal-header text-white justify-content-end text-white"
							 style="border-bottom-color: transparent">
							<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
						</div>
						<div class="modal-body">
							<div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
								<div class="carousel-inner">
									<!-- El contenido del carrusel se generará dinámicamente -->
								</div>
								<button class="carousel-control-prev" type="button"
										data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
									<span class="carousel-control-prev-icon" aria-hidden="true"></span>
									<span class="visually-hidden">Previous</span>
								</button>
								<button class="carousel-control-next" type="button"
										data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
									<span class="carousel-control-next-icon" aria-hidden="true"></span>
									<span class="visually-hidden">Next</span>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>


		</div>

		<div id="carouselExample" class="carousel slide d-md-none" data-bs-ride="carousel">
			<div class="carousel-inner">
                <?php foreach ($images as $index => $image): ?>
				<div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
					<img src="<?= base_url('uploads/properties/images/' . $image['image_url']) ?>" class="d-block w-100" alt="foto1">
				</div>
                <?php endforeach; ?>
			</div>
			<button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
				<span class="carousel-control-prev-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Previous</span>
			</button>
			<button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
				<span class="carousel-control-next-icon" aria-hidden="true"></span>
				<span class="visually-hidden">Next</span>
			</button>
		</div>
	</div>
</section>


<!-- Ubicacion -->
<section>
	<div class="mx-auto" style="max-width: 1400px; padding: 10px;">
		<div class="row"> <!-- Añadido: Contenedor de fila -->
			<div class="col-lg-8 cont1" style="padding: 20px;">
				<div class="d-flex  align-items-center">
					<div class="tipo"><?= strtoupper(str_replace('_', ' ', $property['transaction_type'])) ?> en <?= strtoupper($property['property_type_name']) ?></div>
					<div class="nroid">ID:<?= $property['property_code'] ?></div>
				</div>
				<p class="tit"><?= esc($property['title']) ?></p>
				<p class="price2">S/ <?= number_format($property['price_pen'], 2) ?>
                    <?php if ($property['price_usd']): ?>
                        - USD <?= number_format($property['price_usd'], 2) ?>
                    <?php endif; ?>
                </p>
				<p class="location"><i class="fas fa-map-marker-alt"></i> <?= $property['address'] ?></p>

				<div style="position: relative; width: 100%; padding-top: 37.5%; /* 2:1 aspect ratio reduced */">
					<iframe
						src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3901.3674966144513!2d-76.93800892522714!3d-12.08697488815296!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x9105c724074f3b23%3A0xe1ece2ce18de8f31!2sSantiago%20De%20Compostela%20157%2C%20Lima%2015026!5e0!3m2!1ses-419!2spe!4v1728578284291!5m2!1ses-419!2spe"
						style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
						allowfullscreen=""
						loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
				</div>
				<hr class="separator">
				<div class="row " style="align-items: flex-end; justify-content: center;">
					<div class="col-4 col-sm-2 mt-3 text-center justify-content-center">
						<img src="<?= base_url('images/Ruler.svg') ?>" alt="Terreno libre" class="icono-area mb-3"
							 style="max-width: 55px !important">
						<p class="terr" style="display: block; color: #666666; font-weight: 600;"><?= number_format($property['land_area'], 2) ?>m2 tot.</p>
					</div>
					<div class="col-4 col-sm-2  mt-3 justify-content-center text-center">
						<img src="<?= base_url('images/espac.svg') ?>" alt="Terreno libre" class="icono-area mb-3"
							 style="max-width: 55px !important">
						<p class="terr" style="display: block; color: #666666; font-weight: 600;"><?= number_format($property['built_area'], 2) ?>m2 tot.</p>
					</div>
					<div class="col-4 col-sm-2  mt-3 justify-content-center text-center">
						<img src="<?= base_url('images/Bathtub.svg') ?>" alt="Terreno libre" class="icono-area mb-3"
							 style="max-width: 55px !important">
						<p class="terr" style="display: block; color: #666666; font-weight: 600;"><?= $property['bathrooms'] ?: 0 ?> baños</p>
					</div>
					<div class="col-4 col-sm-2  mt-3 justify-content-center text-center">
						<img src="<?= base_url('images/bed.svg') ?>" alt="Terreno libre" class="icono-area mb-3"
							 style="max-width: 55px !important; padding-right: 10px;">
						<p class="terr" style="display: block; color: #666666; font-weight: 600;"><?= $property['bedrooms'] ?: 0 ?> dormitorios</p>
					</div>
					<div class="col-4 col-sm-2  mt-3 justify-content-center text-center">
						<img src="<?= base_url('images/Calendar.svg') ?>" alt="Terreno libre" class="icono-area mb-3"
							 style="max-width: 55px !important">
						<p class="terr" style="display: block; color: #666666; font-weight: 600;"><?= $property['age_years'] ?: 0 ?> años</p>
					</div>
				</div>
				<hr class="separator">
				<div class="w-100">
					<div class="container mt-4 bio">
						<h2 class="text-center">CONOCE ESTE INMUEBLE</h2>
						<nav class="nav nav-pills flex-nowrap overflow-auto justify-content-between">
							<button class="btncon nav-link active "
									onclick="selectOption ('Caracteristicas generales')">
								<img src="<?= base_url('images/Document.svg') ?>" alt="documento"> Características generales
							</button>
							<button class="btncon nav-link" onclick="selectOption('Servicios')">
								<img src="<?= base_url('images/foco.svg') ?>" alt="foco"> Servicios
							</button>
							<button class="btncon nav-link" onclick="selectOption('Ambientes')">
								<img src="<?= base_url('images/Door.svg') ?>" alt="door"> Ambientes
							</button>
							<button class="btncon nav-link" onclick="selectOption('Exteriores')">
								<img src="<?= base_url('images/Tree.svg') ?>" alt="tree"> Exteriores
							</button>
						</nav>
						<div id="selectedLine" class="selected-line"></div>
						<div id="details" class="mt-4 text-center" style="color: #666666; font-size: small;"></div>
						<div class="text-center mt-4">
							<a href="ruta/a/tu/ficha-tecnica.pdf" class="btnaz1" download>
								Descargar Ficha Técnica
							</a>
						</div>
					</div>
				</div>
			</div>

			<div class="col-lg-4 cont2 bio">
				<div class="col-lg-12 col-md-7 mx-auto">
					<div class="mt-4 contacto">
						<h2 class="fw-semibold bio" style="max-width: 400px; color: #004473;">Contacta al
							anunciante</h2>
						<form class="consulta-box" id="contactForm" enctype="multipart/form-data" required>
                            <input type="hidden" name="property_id" value="<?= $property['id'] ?>">
							<div class="container-fluid px-0" style="color: #999;">
								<div class="row g-3 bio">
									<div class="col-12">
										<div class="form-floating">
											<select name="agent_id" class="form-select" required>
                                                <option value="">Seleccionar asesor</option>
                                                <?php foreach ($property['agents'] as $agent): ?>
                                                    <option value="<?= $agent['agent_id'] ?>" <?= $agent['is_primary'] ? 'selected' : '' ?>>
                                                        <?= esc($agent['first_name'] . ' ' . $agent['last_name']) ?>
                                                        <?= $agent['is_primary'] ? ' (Principal)' : ' (' . ucfirst($agent['role']) . ')' ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
											<label for="email">Contactar a</label>
										</div>
									</div>
                                    <div class="col-12">
										<div class="form-floating">
											<input type="email" class="form-control" id="email" name="email"
												   placeholder="Correo electrónico" required>
											<label for="email">Email</label>
										</div>
									</div>
									<div class="col-12">
										<div class="form-floating">
											<input type="text" class="form-control" id="fullName" name="name"
												   placeholder="Nombre y Apellido" required>
											<label for="fullName">Nombre</label>
										</div>
									</div>
									<div class="col-12">
										<div class="form-floating">
											<input type="text" class="form-control" id="cellphoneNumber"
												   name="phone" placeholder="Celular" required>
											<label for="cellphoneNumber">Telefono</label>
										</div>
									</div>
									<div class="col-12">
										<div class="form-floating">
											<input type="text" class="form-control" id="documentId" name="document_id"
												   placeholder="DNI" required>
											<label for="documentId">DNI</label>
										</div>
									</div>
                                    <div class="col-12">
										<div class="form-floating">
											<select name="inquiry_type" class="form-select">
                                                <option value="info">Solicitar información</option>
                                                <option value="visit">Agendar visita</option>
                                                <option value="call">Solicitar llamada</option>
                                            </select>
											<label for="documentId">Tipo</label>
										</div>
									</div>
									<div class="col-12">
										<div class="form-floating">
											<textarea class="form-control" placeholder="Escribe aquí tus comentarios..."
													  id="comment" name="message" style="height: 100px"
													  required></textarea>
											<label for="comment" class="form-label">Mensaje</label>
										</div>
									</div>
									<div class="text-center">
										<button type="submit" class="btnye btn-lg px-5 w-100">Contactar</button>
									</div>
									<div class="text-center">
										<button type="submit" class="btnaz btn-lg px-5 w-100">Contactar por Whatsapp
										</button>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="message">
						<h4>Tu consulta ha sido enviada exitosamente. Nos pondremos en contacto contigo pronto.</h4>
					</div>
				</div>

				<div id="asesorCarousel" class="carousel slide carousel-dark" data-bs-ride="carousel">
					<div class="carousel-inner">
                        <?php foreach ($property['agents'] as $index => $agent): ?>
                        
						<!-- Asesor 1 -->
						<div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
							<div class="col-12 col-sm-6 col-md-4 col-lg-12 mx-auto">
								<div class="cardasesor bio">
									<div class="card-body">
										<h3 class="text-center fw-semibold" style="color: #004473;">CONTACTA AL
											ASESOR</h3>
										<div class="advisor mt-2 w-50 mx-auto">
											<img src="<?= base_url('assets/images/agents/' . $agent['photo']) ?>" class="advisor1-img" alt="<?= esc($agent['first_name']) ?>">
										</div>
										<p class="text-center larger mt-2 mx-auto"
										   style="color: #03669c; max-width: 200px; font-weight: 700;"><?= esc($agent['first_name'] . ' ' . $agent['last_name']) ?></p>
										<p class="text-center font-weight-light" style="color: #999;">
											<?= esc($agent['email']) ?></p>
										<p class="text-center larger mt-2 mx-auto"
										   style="color: #03669c; max-width: 200px;">
											<span class="fw-semibold">Celular:</span> <?= esc($agent['cell_phone']) ?>
										</p>
										<div class="advisor mt-2 d-flex justify-content-center">
											<a href="tel:+51<?= esc($agent['cell_phone']) ?>" target="_blank" aria-label="Celular">
												<img src="<?= base_url('images/cel.svg') ?>" class="ico" alt="Celular"
													 style="padding-left: 10px;">
											</a>
											<a href="https://wa.me/+51<?= esc($agent['cell_phone']) ?>" target="_blank" aria-label="WhatsApp">
												<img src="<?= base_url('images/Whatsapp.svg') ?>" class="ico" alt="WhatsApp"
													 style="padding-left: 10px;">
											</a>
											<a href="mailto:<?= esc($agent['email']) ?>" target="_blank" aria-label="Email">
												<img src="<?= base_url('images/Email.svg') ?>" class="ico" alt="Email"
													 style="padding-left: 10px;">
											</a>
										</div>
										<button class="btnaz mt-3 w-100">Propiedad del asesor</button>
									</div>
								</div>
							</div>
						</div>
                        <?php endforeach; ?>

					</div>

					<button class="carousel-control-prev custom-carousel-control" type="button"
							data-bs-target="#asesorCarousel" data-bs-slide="prev">
						<span class="carousel-control-prev-icon" aria-hidden="true"></span>
						<span class="visually-hidden">Previous</span>
					</button>
					<button class="carousel-control-next custom-carousel-control" type="button"
							data-bs-target="#asesorCarousel" data-bs-slide="next">
						<span class="carousel-control-next-icon" aria-hidden="true"></span>
						<span class="visually-hidden">Next</span>
					</button>
				</div>


			</div>
		</div>

		<script>


          document.addEventListener('DOMContentLoaded', function () {
            // Seleccionar la opción "Características generales" por defecto al cargar la página
            selectOption("Caracteristicas generales");
          });

          function selectOption(option) {
            const details = {
              "Caracteristicas generales": ["Terraza", "Seguridad", "Caseta de Guardia"],
              "Servicios": ["Luz", "Agua"],
              "Ambientes": ["Dormitorio", "Baño", "Cocina"],
              "Exteriores": ["Cochera", "Patio"]
            };

            // Actualizar el texto de detalles
            const detailsDiv = document.getElementById('details');
            detailsDiv.innerHTML = `<ul>${details[option].map(item => `<li>${item}</li>`).join('')}</ul>`;

            // Remover la clase active de todos los botones
            const buttons = document.querySelectorAll('.nav-link');
            buttons.forEach(button => {
              button.classList.remove('active');
            });

            // Actualizar la clase activa
            const links = document.querySelectorAll('.nav-link');
            links.forEach(link => {
              link.classList.remove('active');
            });

            // Establecer la clase activa en el botón seleccionado
            const activeLink = Array.from(links).find(link => {
              const text = link.textContent.trim();
              return text === option || (text === "Características generales" && option === "Caracteristicas generales");
            });
            if (activeLink) {
              activeLink.classList.add('active');

              // Agregar la clase active al botón correspondiente
              const activeButton = Array.from(buttons).find(button => button.textContent.includes(option));
              if (activeButton) {
                activeButton.classList.add('active');

                // Obtener posición y tamaño del botón activo
                const rect = activeButton.getBoundingClientRect();
                const nav = activeButton.parentElement; // Obtener el contenedor de los botones
                const navRect = nav.getBoundingClientRect(); // Obtener la posición del contenedor

                // Establecer las propiedades de la línea
                const line = document.getElementById('selectedLine');
                line.style.display = 'block';
                line.style.width = `${rect.width}px`;
                line.style.left = `${rect.left - navRect.left}px`;
              }
            }
          }

		</script>
</section>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
  const images = [
    <?php foreach ($images as $index => $image): ?>
    <?php if($index == 0) continue; ?>
    '<?= base_url('uploads/properties/images/' . $image['image_url']) ?>',
    <?php endforeach; ?>
  ];

  function setModalImage(imageSrc) {
    const carouselInner = document.querySelector('.carousel-inner');
    carouselInner.innerHTML = '';

    images.forEach((imgSrc, index) => {
      const newImage = document.createElement('div');
      newImage.className = `carousel-item ${imgSrc === imageSrc ? 'active' : ''}`;
      newImage.innerHTML = `<img src="${imgSrc}" class="d-block" alt="Imagen ${index + 1}">`;
      carouselInner.appendChild(newImage);
    });
  }

</script>
<?= $this->endSection() ?>

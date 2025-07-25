<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<section id="contact" class="px-4 px-md-5 pb-5" style="padding-top: 130px">
	<div class="container-fluid px-0 mx-auto" style="max-width: 1400px;">
		<div class="row g-5">
			<div class="col-12 col-md-5">
				<div class="rounded-5 p-5" style="background-color: #92CCF0">
					<h2 class="fw-extrabold" style="color: #0F5F9C;">
						Contáctanos en:
					</h2>
					<div class="d-flex align-items-center my-4">
						<div>
							<img src="<?= base_url('icons/direccion.svg') ?>" alt="" width="30" style="margin-right: 5px;">
						</div>
						<div class="ps-4">
							<h3 class="fw-extrabold fs-5 mb-1" style="color: #12609D;">
								Dirección
							</h3>
							<p class="fs-5 mb-0 text-dark">
								Asoc. San Francisco Mz. 116 Lt. 01 esquina
								Av. Municipal c/ Calle Los Álamos - Distr.
								Gregorio Albarracín Lanchipa Tacna, Peru
							</p>
						</div>
					</div>
					<div class="d-flex align-items-center my-4">
						<div>
							<img src="<?= base_url('icons/celular.svg') ?>" alt="" width="35">
						</div>
						<div class="ps-4">
							<h3 class="fw-extrabold fs-5 mb-1" style="color: #12609D;">
								N° de celular
							</h3>
							<p class="fs-5 mb-0 text-dark">
								915 292 959
							</p>
						</div>
					</div>
					<div class="d-flex align-items-center my-4">
						<div>
							<img src="<?= base_url('icons/correo.svg') ?>" alt="" width="35">
						</div>
						<div class="ps-4">
							<h3 class="fw-extrabold fs-5 mb-1" style="color: #12609D;">
								Correo
							</h3>
							<p class="fs-5 mb-0 text-dark">
								eligedonde@gmail.com
							</p>
						</div>
					</div>
				</div>
			</div>
			<div class="col-12 col-md-7">
				<h2 class="fw-semibold" style="max-width: 400px; color: #92CCF0;">
					<span class="text-primary">Escríbenos aquí</span><br>
					<span class="text-secondary fw-extrabold fs-1">tu consulta</span>
				</h2>
				<div class="mt-4">
					<form class="consulta-box" id="contactForm" enctype="multipart/form-data" action="/contacto" method="post" required>
                        <?= csrf_field() ?>
						<div class="container-fluid px-0">
							<div class="row g-4">
								<div class="col-12 col-sm-6">
									<div class="form-floating">
										<input type="text" class="form-control" id="fullName" name="full_name"
											   placeholder="Nombre y Apellido" required>
										<label for="fullName">Nombre Completo:</label>
									</div>
								</div>
								<div class="col-12 col-sm-6">
									<div class="form-floating">
										<input type="text" class="form-control" id="documentId" name="document_id"
											   placeholder="DNI" required>
										<label for="documentId">Documento de Identidad:</label>
									</div>
								</div>
								<div class="col-12 col-sm-6">
									<div class="form-floating">
										<input type="text" class="form-control" id="cellphoneNumber"
											   name="cellphone_number" placeholder="Celular" required>
										<label for="cellphoneNumber">Número de Celular:</label>
									</div>
								</div>
								<div class="col-12 col-sm-6">
									<div class="form-floating">
										<input type="email" class="form-control" id="email" name="email"
											   placeholder="Correo electrónico" required>
										<label for="email">Email:</label>
									</div>
								</div>
								<div class="col-12">
									<div class="form-floating">
										<input type="text" class="form-control" id="subject" name="subject"
											   placeholder="Tipo de consulta" required>
										<label for="subject" class="form-label">Tipo de consulta</label>
									</div>
								</div>
								<div class="col-12">
									<div class="form-floating">
                                            <textarea class="form-control" placeholder="Escribe aquí tus comentarios..."
													  id="comment" name="comment" style="height: 100px"
													  required></textarea>
										<label for="comment" class="form-label">Escribe aquí tus
											comentarios...</label>
									</div>
								</div>
								<div class="col-12">
									<div class="form-check">
										<input class="form-check-input" type="checkbox" value="" id="flexCheckDefault">
										<label class="form-check-label fs-5" for="flexCheckDefault">
											He leído y autorizo el tratamiento de mis datos personales de acuerdo a la
											<a class="fw-semibold text-primary" target="_blank"
											   href="">
												“Política de Tratamiento de Datos Personales”
											</a>
										</label>
									</div>
								</div>
								<div class="text-end">
									<button type="submit" class="btn btn-primary btn-lg rounded-pill px-5" id="submitBtn" disabled>
										Enviar
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
		</div>

	</div>
</section>
<?= $this->endSection() ?>

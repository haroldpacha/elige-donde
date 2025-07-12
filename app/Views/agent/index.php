<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<!-- asesor -->

<section class="ultimas-propiedades  bio px-5">
	<div class="text-center mx-auto" style="max-width: 1400px;">
		<h2 class="section-title">Asesores</h2>
		<div class="line"></div>
		<div class="container-fluid px-0">
			<div class="row g-5">

            <?php foreach ($agents as $agent) : ?>
				<!-- Asesor 1 -->
				<div class="col-12 col-sm-6 col-lg-4 col-xl-3">
					<div class="card position-relative">
						<div class="card-body bio">
							<div class="advisor mt-2 w-100">
								<img src="images/asesor1.png
                                    " class="advisor1-img" alt="Asesor">
							</div>

							<p class="text-center larger mt-2 mx-auto "
							   style="color: #03669c; max-width: 200px; font-weight: 700;">
								<?= esc($agent['first_name']) ?><br><?= esc($agent['last_name']) ?>
							</p>
							<div class="advisor mt-2 w-100 mb-3">
								<a href="https://wa.me/+51<?= esc($agent['cell_phone']) ?>" target="_blank" aria-label="WhatsApp">
									<img src="<?= base_url('images/Whatsapp.svg') ?>" class="ico" alt="WhatsApp"
										 style="padding-left: 10px;">
								</a>
								<a href="mailto:<?= esc($agent['email']) ?>" target="_blank" aria-label="Email">
									<img src="<?= base_url('images/Email.svg') ?>" class="ico" alt="Email" style="padding-left: 10px;">
								</a>
								<a href="tel:+123456789" target="_blank" aria-label="Celular">
									<img src="<?= base_url('images/cel.svg') ?>" class="ico" alt="Celular" style="padding-left: 10px;">
								</a>
							</div>
							<div class="d-flex">
								<img src="<?= base_url('images/emailplomo.svg') ?>" class="icon" alt="Email"
									 style="width: 30px !important;">
								<p class="text-center font-weight-light" style="color: #999;">
									<?= esc($agent['email']) ?></p>
							</div>
							<div class="d-flex">
								<img src="<?= base_url('images/llamadagray.svg') ?>" class="icon" alt="cel">
								<p class="text-center font-weight-light" style="color: #999;"><?= esc($agent['phone']) ?></p>
							</div>
							<div class="d-flex">
								<img src="<?= base_url('images/ubicacion.svg') ?>" class="icon" alt="cel"
									 style="width: 40px !important;">
								<p class="font-weight-light" style="color: #999; text-align: start;">Asoc. San Francisco
									Mz. 116 Lt. 01 esquina Av. Municipal c/ Calle Los Álamos - Distr. Gregorio
									Albarracín Lanchipa, Tacna, Peru
								</p>
							</div>

							<button class="btnaz mt-3 w-100">Ver propiedades</button>

						</div>
					</div>

				</div>
            <?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
<?= $this->endSection() ?>

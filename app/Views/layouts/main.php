<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1"/>
	<title><?= $title ?? 'Elige Donde - Nadie en el mundo vende más bienes raíces que Elige Donde' ?></title>

	<meta name="description" content="Generando bienestar"/>

	<!-- Custom css -->
	<link rel="stylesheet" href="<?= base_url('css/style.css') ?>"/>
	<!-- Font css -->
	<link href="https://fonts.googleapis.com/css2?family=Bio+Sans&display=swap" rel="stylesheet">

	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

	<!-- Bootstrap -->
	<link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css') ?>"/>
	<script src="<?= base_url('js/bootstrap.bundle.min.js') ?>"></script>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>

</head>
<body>

<header class="bg-primary text-white bio ">
	<div class="container d-flex justify-content-between align-items-center py-3 mx-auto" style="max-width: 1400px;">
		<a href="<?= base_url() ?>">
			<img src="<?= base_url('images/logo-white.png') ?>" alt="Logo" class="logo">
		</a>

		<!-- Menú en pantallas de escritorio -->
		<nav class="desktop-menu">
			<ul class="nav">
				<li><a class="nav-link text-white active" href="<?= base_url() ?>">INICIO</a></li>
				<hr class="my-2">
				<li><a class="nav-link text-white" href="<?= base_url('buscar-propiedades') ?>">BUSCAR PROPIEDADES</a></li>
				<hr class="my-2">
				<li><a class="nav-link text-white" href="<?= base_url('asesores') ?>">ASESORES</a></li>
				<hr class="my-2">
				<li><a class="nav-link text-white" href="<?= base_url('contacto') ?>">CONTACTANOS</a></li>
			</ul>
		</nav>

		<!-- Botón de menú hamburguesa para móviles -->
		<button class="navbar-toggler mobile-menu " type="button" data-bs-toggle="modal" data-bs-target="#menuModal"
				aria-label="Toggle navigation">
			<img src="<?= base_url('images/menuham.png') ?>" alt="menuham" class="menuham" style="width: 25px">
		</button>
	</div>
</header>

<!-- Modal menu -->
<div class="modal " id="menuModal" tabindex="-1" aria-labelledby="menuModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-fullscreen bio ">
		<div class="modal-content  " style="padding: 20px; background: rgb(17,3,55);
		background: -moz-linear-gradient(0deg, rgba(17,3,55,0.7931547619047619) 0%, rgba(255,255,255,1) 0%, rgba(163,199,219,1) 5%, rgba(3,102,156,1) 13%, rgba(3,102,156,1) 24%, rgba(0,68,115,1) 52%, rgba(0,68,115,1) 98%);
		background: -webkit-linear-gradient(0deg, rgba(17,3,55,0.7931547619047619) 0%, rgba(255,255,255,1) 0%, rgba(163,199,219,1) 5%, rgba(3,102,156,1) 13%, rgba(3,102,156,1) 24%, rgba(0,68,115,1) 52%, rgba(0,68,115,1) 98%);
		background: linear-gradient(0deg, rgba(17,3,55,0.7931547619047619) 0%, rgba(255,255,255,1) 0%, rgba(163,199,219,1) 5%, rgba(3,102,156,1) 13%, rgba(3,102,156,1) 24%, rgba(0,68,115,1) 52%, rgba(0,68,115,1) 98%);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr= #110337,endColorstr=#004473,GradientType=1); ">
			<div class="modal-header justify-content-end"
				 style="border-bottom-color: transparent; background-color: #004473 ">
				<div type="button" class=" text-white " data-bs-dismiss="modal" aria-label="Close"> X</div>
			</div>
			<div class="modal-body menumod " style="margin-top: 20%;">
				<ul class="list-unstyled text-white">
					<li><a class="nav-link text-white" href="<?= base_url() ?>">INICIO</a></li>
					<hr class="my-2">
					<li><a class="nav-link text-white" href="<?= base_url('buscar-propiedades') ?>">BUSCAR PROPIEDADES</a></li>
					<hr class="my-2">
					<hr class="my-2">
					<li><a class="nav-link text-white" href="<?= base_url('asesores') ?>">ASESORES</a></li>
					<hr class="my-2">
					<li><a class="nav-link text-white" href="<?= base_url('contacto') ?>">CONTACTANOS</a></li>
				</ul>

				<div class="modimagen" style="border-top-color: transparent;">
					<img src="<?= base_url('images/logo-white.png') ?>" alt="Logo" class="logo" style="width: 100px;">
					<!-- Ajusta el tamaño según necesites -->
				</div>
			</div>

		</div>
	</div>
</div>

<?= $this->renderSection('content') ?>

<!-- Foooter -->
<section class="footer-links bio" style="background-color: rgba(102, 102, 102, 0.7);">
	<div class="contwhite" style="background-color: #ffffff;">

	</div>
	<div class="mx-auto" style="max-width: 1400px;">
		<div class="container row mx-auto " style="margin-top: -20px;">
			<div class="redessociales text-center " style="width: auto;">
				<a href="https://www.google.com/url?sa=t&source=web&rct=j&opi=89978449&url=https://www.facebook.com/EligeDondeInmobiliaria/%3Flocale%3Des_LA&ved=2ahUKEwirh5Cz5NeLAxVal5UCHSPANEkQFnoECBgQAQ&usg=AOvVaw0ZW2Y-Csj6-cvL98Jm8FH9" target="_blank" aria-label="Facebook" class="social-icon">
					<img src="<?= base_url('images/facebook.svg') ?>" alt="redessociale" class="icono-rs"
						 style="max-width: 26px !important; padding-right: 10px;">
				</a>
				<a href="https://www.youtube.com" target="_blank" aria-label="YouTube" class="social-icon">
					<img src="<?= base_url('images/Youtube.svg') ?>" alt="redessociale" class="icono-rs"
						 style="max-width: 40px !important; padding-right: 10px;">
				</a>
				<a href="https://twitter.com" target="_blank" aria-label="Twitter" class="social-icon">
					<img src="<?= base_url('images/Twitter.svg') ?>" alt="redessociale" class="icono-rs"
						 style="max-width: 40px !important; padding-right: 10px;">
				</a>
				<a href="https://www.instagram.com" target="_blank" aria-label="Instagram" class="social-icon">
					<img src="<?= base_url('images/Instagram.svg') ?>" alt="redessociale" class="icono-rs"
						 style="max-width: 40px !important; padding-right: 10px;">
				</a>
			</div>
		</div>
		<div class="row justify-content-between mx-auto" style="font-weight: 400; padding: 10px;">
			<!-- Primera columna -->
			<div class="col-md-4 text-white" style="padding: 20px;">

				<ul class="list-unstyled">
					<li><a href="#" class="text-white sr">NOSOTROS</a></li>
					<li><a href="#" class="text-white sr">ADQUIERE UNA FRANQUICIA</a></li>
					<li><a href="<?= base_url('contacto') ?>" class="text-white sr">CONTACTANOS</a></li>
					<li><a href="#" class="text-white sr">SÉ UN ASESOR</a></li>
				</ul>
			</div>
			<!-- Segunda columna -->
			<div class="col-md-4 text-white" style="padding: 20px;">

				<ul class="list-unstyled">
					<li><a href="#" class="text-white sr">BUSCAR UN ASESOR</a></li>
					<li><a href="#" class="text-white sr">PROPIEDADES RECIENTES</a></li>
					<li><a href="#" class="text-white sr">BAJARON UN PRECIO</a></li>
					<li><a href="#" class="text-white sr">POLITICAS DE PRIVACIDAD</a></li>
				</ul>
			</div>
			<!-- Tercera columna -->
			<div class="col-md-4 text-white" style="padding: 20px;">
				<h5 style="color: #004473;">INFORMES</h5>
				<div class="d-flex align-items-center">
					<img src="<?= base_url('images/telefono.svg') ?>" alt="redessociale" class="icono-rs"
						 style="max-width: 40px !important; padding-right: 10px;">
					<div class="justify-content-between row " style=" font-weight: 400;">
						<p class="info text-white" style="display: block;">Telefono:</p>
						<p class="info" style="color: #004473; display: block;">(51) 915 292959</p>
					</div>
				</div>
				<div class="d-flex align-items-center">
					<img src="<?= base_url('images/Emails.svg') ?>" alt="redessociale" class="icono-rs"
						 style="max-width: 40px !important; padding-right: 10px;">
					<div class="justify-content-between row" style="font-weight: 400;">
						<p class="info text-white" style="display: block;">Email:</p>
						<p class="info" style="color: #004473; display: block;">contacto@eligedonde.com</p>
					</div>
				</div>

			</div>
		</div>
	</div>
	</div>
</section>

<section style="background-color:  #03669c;">
	<div class="ventas-section" style="max-width: 1400px; text-align: start; ">
		<div class="column col-lg-4 col-md-6 col-12 text-white smaller" style="text-align: start; ">
			<ul>
				<li><a href="#" class="text-white sinli">Venta de departamento en Tacna</a></li>
				<li><a href="#" class="text-white sinli">Venta de departamento en Alto de Alianza</a></li>
				<li><a href="#" class="text-white sinli">Venta de departamento en Palca</a></li>
				<li><a href="#" class="text-white sinli">Venta de departamento en Calana</a></li>
				<li><a href="#" class="text-white sinli">Venta de departamento en Ciudad Nueva</a></li>
				<li><a href="#" class="text-white sinli">Venta de departamento en Coronel Gregorio Albarracín
					Lanchipa</a></li>
				<li><a href="#" class="text-white sinli">Venta de departamento en Inclán</a></li>
				<li><a href="#" class="text-white sinli">Venta de departamento en La Yarada - Los Palos</a></li>
				<li><a href="#" class="text-white sinli">Venta de departamento en Pachía</a></li>
				<li><a href="#" class="text-white sinli">Venta de departamento en Pocollay</a></li>
				<li><a href="#" class="text-white sinli">Venta de departamento en Sama</a></li>
			</ul>
		</div>

		<div class="column col-lg-4 col-md-6 col-12 text-white smaller" style="text-align: start">
			<ul>
				<li><a href="#" class="text-white sinli">Alquiler de departamento en Tacna</a></li>
				<li><a href="#" class="text-white sinli">Alquiler de departamento en Alto de Alianza</a></li>
				<li><a href="#" class="text-white sinli">Alquiler de departamento en Palca</a></li>
				<li><a href="#" class="text-white sinli">Alquiler de departamento en Calana</a></li>
				<li><a href="#" class="text-white sinli">Alquiler de departamento en Ciudad Nueva</a></li>
				<li><a href="#" class="text-white sinli">Alquiler de departamento en Coronel Gregorio Albarracín
					Lanchipa</a></li>
				<li><a href="#" class="text-white sinli">Alquiler de departamento en Inclán</a></li>
				<li><a href="#" class="text-white sinli">Alquiler de departamento en La Yarada - Los Palos</a></li>
				<li><a href="#" class="text-white sinli">Alquiler de departamento en Pachía</a></li>
				<li><a href="#" class="text-white sinli">Alquiler de departamento en Pocollay</a></li>
				<li><a href="#" class="text-white sinli">Alquiler de departamento en Sama</a></li>
			</ul>
		</div>

		<div class="column col-lg-4 col-md-6 col-12 text-white smaller" style="text-align: start">
			<ul>
				<li><a href="#" class="text-white sinli">Venta de casa en Tacna</a></li>
				<li><a href="#" class="text-white sinli">Venta de casa en Alto de Alianza</a></li>
				<li><a href="#" class="text-white sinli">Venta de casa en Palca</a></li>
				<li><a href="#" class="text-white sinli">Venta de casa en Calana</a></li>
				<li><a href="#" class="text-white sinli">Venta de casa en Ciudad Nueva</a></li>
				<li><a href="#" class="text-white sinli">Venta de casa en Coronel Gregorio Albarracín Lanchipa</a></li>
				<li><a href="#" class="text-white sinli">Venta de casa en Inclán</a></li>
				<li><a href="#" class="text-white sinli">Venta de casa en La Yarada - Los Palos</a></li>
				<li><a href="#" class="text-white sinli">Venta de casa en Pachía</a></li>
				<li><a href="#" class="text-white sinli">Venta de casa en Pocollay</a></li>
				<li><a href="#" class="text-white sinli">Venta de casa en Sama</a></li>
			</ul>
		</div>
	</div>
</section>
    <!-- Additional Scripts -->
    <?= $this->renderSection('scripts') ?>
</body>

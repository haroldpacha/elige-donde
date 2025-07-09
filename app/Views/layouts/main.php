<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'RE/MAX Perú - Nadie en el mundo vende más bienes raíces que RE/MAX' ?></title>

    <!-- Meta tags -->
    <meta name="description" content="RE/MAX Perú - Encuentra tu hogar ideal con la red inmobiliaria más grande del mundo. Casas, departamentos, oficinas y más.">
    <meta name="keywords" content="inmobiliaria, bienes raíces, casas, departamentos, venta, alquiler, RE/MAX, Perú">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css') ?>">

    <!-- Favicons -->
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/images/favicon.ico') ?>">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <!-- Top Bar -->
        <div class="top-bar bg-primary text-white py-2">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center">
                            <i class="fas fa-phone me-2"></i>
                            <span class="me-3">Sé un Asesor</span>
                            <i class="fas fa-building me-2"></i>
                            <span>Franquicias</span>
                        </div>
                    </div>
                    <div class="col-md-6 text-end">
                        <div class="social-links">
                            <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                            <a href="#" class="text-white me-3"><i class="fab fa-youtube"></i></a>
                            <a href="#" class="text-white me-3"><i class="fab fa-tiktok"></i></a>
                            <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                            <span class="ms-3">Mi RE/MAX</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="<?= base_url() ?>">
                    <img src="<?= base_url('assets/images/remax-logo.png') ?>" alt="RE/MAX Perú" height="50">
                </a>

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="<?= base_url() ?>">INICIO</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="<?= base_url('buscar-propiedades') ?>">BUSCAR PROPIEDADES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="<?= base_url('oficinas') ?>">OFICINAS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="<?= base_url('asesores') ?>">ASESORES</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold" href="<?= base_url('contacto') ?>">CONTÁCTANOS</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main>
        <?= $this->renderSection('content') ?>
    </main>

    <!-- Footer -->
    <footer class="footer bg-dark text-white mt-5">
        <div class="container py-5">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <img src="<?= base_url('assets/images/remax-logo-white.png') ?>" alt="RE/MAX Perú" height="60" class="mb-3">
                    <p>Nadie en el mundo vende más bienes raíces que RE/MAX. Encontremos tu hogar ideal juntos.</p>
                    <div class="social-links">
                        <a href="#" class="text-white me-3"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-youtube"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-tiktok"></i></a>
                        <a href="#" class="text-white me-3"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 mb-4">
                    <h5>Propiedades</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url('buscar-propiedades?transaction_type=venta') ?>" class="text-white-50">En Venta</a></li>
                        <li><a href="<?= base_url('buscar-propiedades?transaction_type=alquiler') ?>" class="text-white-50">En Alquiler</a></li>
                        <li><a href="<?= base_url('buscar-propiedades?property_type_id=1') ?>" class="text-white-50">Casas</a></li>
                        <li><a href="<?= base_url('buscar-propiedades?property_type_id=2') ?>" class="text-white-50">Departamentos</a></li>
                    </ul>
                </div>

                <div class="col-lg-2 mb-4">
                    <h5>Empresa</h5>
                    <ul class="list-unstyled">
                        <li><a href="#" class="text-white-50">Nosotros</a></li>
                        <li><a href="#" class="text-white-50">Franquicias</a></li>
                        <li><a href="#" class="text-white-50">Carreras</a></li>
                        <li><a href="#" class="text-white-50">Prensa</a></li>
                    </ul>
                </div>

                <div class="col-lg-4 mb-4">
                    <h5>Contacto</h5>
                    <div class="mb-2">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Lima, Perú
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-phone me-2"></i>
                        +51 1 234-5678
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-envelope me-2"></i>
                        info@remax-peru.com
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="row">
                <div class="col-md-6">
                    <p class="mb-0">&copy; <?= date('Y') ?> RE/MAX Perú. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="#" class="text-white-50 me-3">Términos y Condiciones</a>
                    <a href="#" class="text-white-50">Política de Privacidad</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Custom JavaScript -->
    <script src="<?= base_url('assets/js/main.js') ?>"></script>

    <!-- Additional Scripts -->
    <?= $this->renderSection('scripts') ?>
</body>
</html>

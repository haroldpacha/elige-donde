<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Dashboard - Admin Elige Donde' ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">

    <!-- Additional CSS -->
    <?= $this->renderSection('styles') ?>
</head>
<body class="admin-body">
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top">
        <div class="container-fluid">
            <!-- Brand -->
            <a class="navbar-brand d-flex align-items-center" href="<?= base_url('admin') ?>">
                <img src="<?= base_url('images/logo-base.png') ?>" alt="Elige Donde" height="30" class="me-2">
                <span class="fw-bold">Admin Panel</span>
            </a>

            <!-- Mobile Toggle -->
            <button class="navbar-toggler d-lg-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Right Side Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <!-- Notifications -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="notificationsDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-bell"></i>
                            <span class="badge bg-danger rounded-pill">3</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header">Notificaciones</h6></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-home me-2"></i>Nueva propiedad agregada</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-envelope me-2"></i>Consulta recibida</a></li>
                            <li><a class="dropdown-item" href="#"><i class="fas fa-user me-2"></i>Nuevo agente registrado</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-center" href="#">Ver todas</a></li>
                        </ul>
                    </li>

                    <!-- User Menu -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
                            <i class="fas fa-user-circle me-2"></i>
                            <?= session()->get('admin_name') ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><h6 class="dropdown-header"><?= session()->get('admin_email') ?></h6></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/auth/profile') ?>">
                                <i class="fas fa-user me-2"></i>Mi Perfil
                            </a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/configuracion') ?>">
                                <i class="fas fa-cog me-2"></i>Configuración
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="<?= base_url() ?>" target="_blank">
                                <i class="fas fa-external-link-alt me-2"></i>Ver Sitio Web
                            </a></li>
                            <li><a class="dropdown-item" href="<?= base_url('admin/auth/logout') ?>">
                                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                            </a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <nav class="sidebar">
            <div class="sidebar-content">
                <!-- Main Navigation -->
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link <?= current_url() === base_url('admin') ? 'active' : '' ?>" href="<?= base_url('admin') ?>">
                            <i class="fas fa-tachometer-alt"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    <!-- Properties Section -->
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(current_url(), 'admin/propiedades') !== false ? 'active' : '' ?>"
                           href="<?= base_url('admin/propiedades') ?>">
                            <i class="fas fa-home"></i>
                            <span>Propiedades</span>
                        </a>
                    </li>

                    <!-- Agents Section -->
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(current_url(), 'admin/agentes') !== false ? 'active' : '' ?>"
                           href="<?= base_url('admin/agentes') ?>">
                            <i class="fas fa-users"></i>
                            <span>Agentes</span>
                        </a>
                    </li>

                    <!-- Inquiries Section -->
                    <li class="nav-item">
                        <a class="nav-link <?= strpos(current_url(), 'admin/consultas') !== false ? 'active' : '' ?>"
                           href="<?= base_url('admin/consultas') ?>">
                            <i class="fas fa-envelope"></i>
                            <span>Consultas</span>
                            <span class="badge bg-danger ms-auto">5</span>
                        </a>
                    </li>

                    <!-- Content Management -->
                    <li class="nav-section">
                        <span class="nav-section-title">Gestión de Contenido</span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= strpos(current_url(), 'admin/tipos-propiedades') !== false ? 'active' : '' ?>"
                           href="<?= base_url('admin/tipos-propiedades') ?>">
                            <i class="fas fa-tags"></i>
                            <span>Tipos de Propiedades</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= strpos(current_url(), 'admin/ubicaciones') !== false ? 'active' : '' ?>"
                           href="<?= base_url('admin/ubicaciones') ?>">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Ubicaciones</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= strpos(current_url(), 'admin/caracteristicas') !== false ? 'active' : '' ?>"
                           href="<?= base_url('admin/caracteristicas') ?>">
                            <i class="fas fa-list"></i>
                            <span>Características</span>
                        </a>
                    </li>

                    <!-- Reports -->
                    <li class="nav-section">
                        <span class="nav-section-title">Reportes y Análisis</span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= strpos(current_url(), 'admin/analiticas') !== false ? 'active' : '' ?>"
                           href="<?= base_url('admin/analiticas') ?>">
                            <i class="fas fa-chart-bar"></i>
                            <span>Analíticas</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= strpos(current_url(), 'admin/reportes') !== false ? 'active' : '' ?>"
                           href="<?= base_url('admin/reportes') ?>">
                            <i class="fas fa-file-chart-column"></i>
                            <span>Reportes</span>
                        </a>
                    </li>

                    <!-- System -->
                    <li class="nav-section">
                        <span class="nav-section-title">Sistema</span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= strpos(current_url(), 'admin/usuarios') !== false ? 'active' : '' ?>"
                           href="<?= base_url('admin/usuarios') ?>">
                            <i class="fas fa-user-cog"></i>
                            <span>Usuarios Admin</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= strpos(current_url(), 'admin/configuracion') !== false ? 'active' : '' ?>"
                           href="<?= base_url('admin/configuracion') ?>">
                            <i class="fas fa-cog"></i>
                            <span>Configuración</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link <?= strpos(current_url(), 'admin/actividad') !== false ? 'active' : '' ?>"
                           href="<?= base_url('admin/actividad') ?>">
                            <i class="fas fa-history"></i>
                            <span>Registro de Actividad</span>
                        </a>
                    </li>
                </ul>

                <!-- Sidebar Footer -->
                <div class="sidebar-footer">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-circle text-success me-2"></i>
                        <small class="text-muted">Sistema funcionando</small>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Breadcrumb -->
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="<?= base_url('admin') ?>">
                            <i class="fas fa-home"></i>
                            Dashboard
                        </a>
                    </li>
                    <?= $this->renderSection('breadcrumb') ?>
                </ol>
            </nav>

            <!-- Alerts -->
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('warning')): ?>
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?= session()->getFlashdata('warning') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Page Content -->
            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>

    <!-- Custom Admin JS -->
    <script src="<?= base_url('assets/js/admin.js') ?>"></script>

    <!-- Additional Scripts -->
    <?= $this->renderSection('scripts') ?>

    <!-- Toast Container -->
    <div class="toast-container position-fixed top-0 end-0 p-3">
        <!-- Toasts will be inserted here by JavaScript -->
    </div>
</body>
</html>

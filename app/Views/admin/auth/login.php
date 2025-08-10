<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?></title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom Admin CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/admin.css') ?>">
</head>
<body class="admin-login-body">
    <div class="login-container">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-5 col-md-7">
                    <div class="login-card">
                        <div class="card shadow-lg border-0">
                            <div class="card-body p-5">
                                <!-- Logo -->
                                <div class="text-center mb-4">
                                    <img src="<?= base_url('images/logo-base.png') ?>" alt="Elige Donde" height="60">
                                    <h3 class="mt-3 text-primary">Panel Administrativo</h3>
                                    <p class="text-muted">Ingresa tus credenciales para acceder</p>
                                </div>

                                <!-- Alerts -->
                                <?php if (session()->getFlashdata('error')): ?>
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-circle me-2"></i>
                                        <?= session()->getFlashdata('error') ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>

                                <?php if (session()->getFlashdata('success')): ?>
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        <i class="fas fa-check-circle me-2"></i>
                                        <?= session()->getFlashdata('success') ?>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                <?php endif; ?>

                                <!-- Login Form -->
                                <form id="loginForm" action="<?= base_url('admin/auth/process-login') ?>" method="POST">
                                    <?= csrf_field() ?>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-2"></i>Email
                                        </label>
                                        <input type="email"
                                               class="form-control form-control-lg <?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                                               id="email"
                                               name="email"
                                               value="<?= old('email') ?>"
                                               placeholder="tu@email.com"
                                               required
                                               autofocus>
                                        <?php if (isset($errors['email'])): ?>
                                            <div class="invalid-feedback">
                                                <?= $errors['email'] ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">
                                            <i class="fas fa-lock me-2"></i>Contraseña
                                        </label>
                                        <div class="input-group">
                                            <input type="password"
                                                   class="form-control form-control-lg <?= isset($errors['password']) ? 'is-invalid' : '' ?>"
                                                   id="password"
                                                   name="password"
                                                   placeholder="••••••••"
                                                   required>
                                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <?php if (isset($errors['password'])): ?>
                                                <div class="invalid-feedback">
                                                    <?= $errors['password'] ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label class="form-check-label" for="remember">
                                            Recordarme
                                        </label>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary btn-lg" id="loginBtn">
                                            <i class="fas fa-sign-in-alt me-2"></i>
                                            <span class="btn-text">Iniciar Sesión</span>
                                            <span class="btn-loading d-none">
                                                <i class="fas fa-spinner fa-spin me-2"></i>
                                                Iniciando...
                                            </span>
                                        </button>
                                    </div>
                                </form>

                                <!-- Links -->
                                <div class="text-center mt-4">
                                    <a href="<?= base_url('admin/auth/forgot-password') ?>" class="text-decoration-none">
                                        <i class="fas fa-key me-1"></i>
                                        ¿Olvidaste tu contraseña?
                                    </a>
                                </div>

                                <!-- Demo Credentials -->
                                <div class="demo-credentials mt-4 p-3 bg-light rounded">
                                    <h6 class="text-muted mb-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Credenciales de demostración:
                                    </h6>
                                    <p class="mb-1"><strong>Email:</strong> admin@remax-peru.com</p>
                                    <p class="mb-0"><strong>Contraseña:</strong> password</p>
                                </div>
                            </div>
                        </div>

                        <!-- Footer -->
                        <div class="text-center mt-4">
                            <p class="text-muted">
                                <a href="<?= base_url() ?>" class="text-decoration-none">
                                    <i class="fas fa-arrow-left me-2"></i>
                                    Volver al sitio web
                                </a>
                            </p>
                            <p class="text-muted small">
                                © <?= date('Y') ?> Elige Donde. Todos los derechos reservados.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Custom Admin JS -->
    <script src="<?= base_url('assets/js/admin.js') ?>"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            document.getElementById('togglePassword').addEventListener('click', function() {
                const password = document.getElementById('password');
                const icon = this.querySelector('i');

                if (password.type === 'password') {
                    password.type = 'text';
                    icon.classList.remove('fa-eye');
                    icon.classList.add('fa-eye-slash');
                } else {
                    password.type = 'password';
                    icon.classList.remove('fa-eye-slash');
                    icon.classList.add('fa-eye');
                }
            });

            // Form submission with loading state
            document.getElementById('loginForm').addEventListener('submit', function() {
                const btn = document.getElementById('loginBtn');
                const btnText = btn.querySelector('.btn-text');
                const btnLoading = btn.querySelector('.btn-loading');

                btn.disabled = true;
                btnText.classList.add('d-none');
                btnLoading.classList.remove('d-none');
            });

            // Auto-hide alerts after 5 seconds
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    if (alert.classList.contains('show')) {
                        alert.classList.remove('show');
                        setTimeout(function() {
                            alert.remove();
                        }, 150);
                    }
                });
            }, 5000);
        });
    </script>
</body>
</html>

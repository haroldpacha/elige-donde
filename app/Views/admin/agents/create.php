<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item">
    <a href="<?= base_url('admin/agentes') ?>">Agentes</a>
</li>
<li class="breadcrumb-item active">Crear Nuevo</li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
.photo-upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    background-color: #f8f9fa;
    cursor: pointer;
    transition: all 0.3s ease;
    min-height: 200px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
}

.photo-upload-area:hover {
    border-color: #007bff;
    background-color: #e3f2fd;
}

.photo-upload-area.dragover {
    border-color: #28a745;
    background-color: #d4edda;
}

.photo-preview {
    max-width: 200px;
    max-height: 200px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-top: 1rem;
}

.remove-photo {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    border: none;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    font-size: 14px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
}

.preview-container {
    position: relative;
    display: inline-block;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Crear Nuevo Agente</h1>
        <p class="text-muted">Completa la información del nuevo agente inmobiliario</p>
    </div>
    <div>
        <a href="<?= base_url('admin/agentes') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Volver al Listado
        </a>
    </div>
</div>

<!-- Agent Form -->
<form id="agentForm" action="<?= base_url('admin/agentes/store') ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user me-2"></i>
                        Información Personal
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="first_name" class="form-label">Nombre *</label>
                            <input type="text" class="form-control" id="first_name" name="first_name"
                                   value="<?= old('first_name') ?>" required>
                            <?php if (isset($errors['first_name'])): ?>
                                <div class="invalid-feedback d-block"><?= $errors['first_name'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Apellido *</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                   value="<?= old('last_name') ?>" required>
                            <?php if (isset($errors['last_name'])): ?>
                                <div class="invalid-feedback d-block"><?= $errors['last_name'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email"
                               value="<?= old('email') ?>" required>
                        <?php if (isset($errors['email'])): ?>
                            <div class="invalid-feedback d-block"><?= $errors['email'] ?></div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Contact Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-phone me-2"></i>
                        Información de Contacto
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Teléfono Fijo</label>
                            <input type="tel" class="form-control" id="phone" name="phone"
                                   value="<?= old('phone') ?>" placeholder="01-2345678">
                            <?php if (isset($errors['phone'])): ?>
                                <div class="invalid-feedback d-block"><?= $errors['phone'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cell_phone" class="form-label">Celular</label>
                            <input type="tel" class="form-control" id="cell_phone" name="cell_phone"
                                   value="<?= old('cell_phone') ?>" placeholder="987654321">
                            <?php if (isset($errors['cell_phone'])): ?>
                                <div class="invalid-feedback d-block"><?= $errors['cell_phone'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Professional Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-briefcase me-2"></i>
                        Información Profesional
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="company" class="form-label">Empresa</label>
                            <input type="text" class="form-control" id="company" name="company"
                                   value="<?= old('company') ?: 'RE/MAX CENTRAL REALTY' ?>"
                                   placeholder="RE/MAX CENTRAL REALTY">
                            <?php if (isset($errors['company'])): ?>
                                <div class="invalid-feedback d-block"><?= $errors['company'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="license_number" class="form-label">Número de Licencia</label>
                            <input type="text" class="form-control" id="license_number" name="license_number"
                                   value="<?= old('license_number') ?>" placeholder="LIC-2024-001">
                            <?php if (isset($errors['license_number'])): ?>
                                <div class="invalid-feedback d-block"><?= $errors['license_number'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Información:</strong> El número de licencia es opcional pero recomendado para agentes certificados.
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Photo Upload -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-camera me-2"></i>
                        Foto del Agente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="photo-upload-area" id="photoUploadArea">
                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                        <h6>Arrastra una foto aquí o haz clic para seleccionar</h6>
                        <p class="text-muted small mb-0">Máximo 2MB. Formatos: JPG, PNG, WEBP</p>
                        <input type="file" id="photoInput" name="photo" accept="image/*" class="d-none">
                    </div>

                    <div id="photoPreviewContainer" class="mt-3"></div>

                    <?php if (isset($errors['photo'])): ?>
                        <div class="invalid-feedback d-block mt-2"><?= $errors['photo'] ?></div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Agent Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-toggle-on me-2"></i>
                        Estado del Agente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">
                            <i class="fas fa-user-check text-success me-2"></i>
                            Agente Activo
                        </label>
                        <div class="form-text">Los agentes activos pueden ser asignados a propiedades y aparecen en el sitio web</div>
                    </div>
                </div>
            </div>

            <!-- Guidelines -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lightbulb me-2"></i>
                        Consejos
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Usa una foto profesional de alta calidad
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            Incluye al menos un número de contacto
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-check text-success me-2"></i>
                            El email será único en el sistema
                        </li>
                        <li class="mb-0">
                            <i class="fas fa-check text-success me-2"></i>
                            La información será visible públicamente
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                            <i class="fas fa-save me-2"></i>
                            <span class="btn-text">Crear Agente</span>
                            <span class="btn-loading d-none">
                                <i class="fas fa-spinner fa-spin me-2"></i>
                                Guardando...
                            </span>
                        </button>

                        <a href="<?= base_url('admin/agentes') ?>" class="btn btn-outline-danger">
                            <i class="fas fa-times me-2"></i>
                            Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Photo upload functionality
    const photoUploadArea = document.getElementById('photoUploadArea');
    const photoInput = document.getElementById('photoInput');
    const photoPreviewContainer = document.getElementById('photoPreviewContainer');

    photoUploadArea.addEventListener('click', () => photoInput.click());

    photoUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    photoUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });

    photoUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            photoInput.files = files;
            handlePhotoUpload(files[0]);
        }
    });

    photoInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            handlePhotoUpload(this.files[0]);
        }
    });

    function handlePhotoUpload(file) {
        // Validate file
        const maxSize = 2 * 1024 * 1024; // 2MB
        const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];

        if (file.size > maxSize) {
            alert('El archivo es demasiado grande. Máximo 2MB.');
            photoInput.value = '';
            return;
        }

        if (!allowedTypes.includes(file.type)) {
            alert('Formato no válido. Solo JPG, PNG, WEBP.');
            photoInput.value = '';
            return;
        }

        // Create preview
        const reader = new FileReader();
        reader.onload = function(e) {
            photoPreviewContainer.innerHTML = `
                <div class="preview-container">
                    <img src="${e.target.result}" alt="Preview" class="photo-preview">
                    <button type="button" class="remove-photo" onclick="removePhoto()">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;

            // Hide upload area
            photoUploadArea.style.display = 'none';
        };
        reader.readAsDataURL(file);
    }

    // Form submission
    document.getElementById('agentForm').addEventListener('submit', function(e) {
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');

        submitBtn.disabled = true;
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
    });

    // Phone number formatting
    const phoneInputs = document.querySelectorAll('input[type="tel"]');
    phoneInputs.forEach(input => {
        input.addEventListener('input', function() {
            // Remove non-numeric characters
            this.value = this.value.replace(/[^0-9-]/g, '');
        });
    });

    // Email validation
    const emailInput = document.getElementById('email');
    emailInput.addEventListener('blur', function() {
        const email = this.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (email && !emailRegex.test(email)) {
            this.classList.add('is-invalid');
        } else {
            this.classList.remove('is-invalid');
        }
    });

    // Auto-generate company if empty
    const companyInput = document.getElementById('company');
    companyInput.addEventListener('focus', function() {
        if (!this.value) {
            this.value = 'RE/MAX CENTRAL REALTY';
        }
    });
});

// Global function to remove photo
function removePhoto() {
    document.getElementById('photoInput').value = '';
    document.getElementById('photoPreviewContainer').innerHTML = '';
    document.getElementById('photoUploadArea').style.display = 'flex';
}

// Form validation
(function() {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');

    Array.prototype.slice.call(forms).forEach(function(form) {
        form.addEventListener('submit', function(event) {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>
<?= $this->endSection() ?>

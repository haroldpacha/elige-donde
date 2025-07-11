<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item">
    <a href="<?= base_url('admin/agentes') ?>">Agentes</a>
</li>
<li class="breadcrumb-item active">Editar: <?= esc($agent['first_name'] . ' ' . $agent['last_name']) ?></li>
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

.current-photo {
    max-width: 200px;
    max-height: 200px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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

.stat-card {
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Editar Agente</h1>
        <p class="text-muted"><?= esc($agent['first_name'] . ' ' . $agent['last_name']) ?> - <?= esc($agent['email']) ?></p>
    </div>
    <div>
        <a href="<?= base_url('asesor/' . $agent['id']) ?>" target="_blank" class="btn btn-outline-info me-2">
            <i class="fas fa-external-link-alt me-2"></i>
            Ver en Sitio Web
        </a>
        <a href="<?= base_url('admin/agentes') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Volver al Listado
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Propiedades</h5>
                        <h3 class="mb-0"><?= $agent['stats']['total_properties'] ?></h3>
                    </div>
                    <i class="fas fa-home fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Principal</h5>
                        <h3 class="mb-0"><?= $agent['stats']['primary_properties'] ?></h3>
                    </div>
                    <i class="fas fa-star fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">En Venta</h5>
                        <h3 class="mb-0"><?= $agent['stats']['sale_properties'] ?></h3>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">En Alquiler</h5>
                        <h3 class="mb-0"><?= $agent['stats']['rent_properties'] ?></h3>
                    </div>
                    <i class="fas fa-key fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Agent Form -->
<form id="agentForm" action="<?= base_url('admin/agentes/update/' . $agent['id']) ?>" method="POST" enctype="multipart/form-data">
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
                                   value="<?= esc($agent['first_name']) ?>" required>
                            <?php if (isset($errors['first_name'])): ?>
                                <div class="invalid-feedback d-block"><?= $errors['first_name'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="last_name" class="form-label">Apellido *</label>
                            <input type="text" class="form-control" id="last_name" name="last_name"
                                   value="<?= esc($agent['last_name']) ?>" required>
                            <?php if (isset($errors['last_name'])): ?>
                                <div class="invalid-feedback d-block"><?= $errors['last_name'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email *</label>
                        <input type="email" class="form-control" id="email" name="email"
                               value="<?= esc($agent['email']) ?>" required>
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
                                   value="<?= esc($agent['phone']) ?>" placeholder="01-2345678">
                            <?php if (isset($errors['phone'])): ?>
                                <div class="invalid-feedback d-block"><?= $errors['phone'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="cell_phone" class="form-label">Celular</label>
                            <input type="tel" class="form-control" id="cell_phone" name="cell_phone"
                                   value="<?= esc($agent['cell_phone']) ?>" placeholder="987654321">
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
                                   value="<?= esc($agent['company']) ?>"
                                   placeholder="RE/MAX CENTRAL REALTY">
                            <?php if (isset($errors['company'])): ?>
                                <div class="invalid-feedback d-block"><?= $errors['company'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="license_number" class="form-label">Número de Licencia</label>
                            <input type="text" class="form-control" id="license_number" name="license_number"
                                   value="<?= esc($agent['license_number']) ?>" placeholder="LIC-2024-001">
                            <?php if (isset($errors['license_number'])): ?>
                                <div class="invalid-feedback d-block"><?= $errors['license_number'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Properties -->
            <?php if (!empty($agent['recent_properties'])): ?>
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-home me-2"></i>
                            Propiedades Recientes
                        </h5>
                        <span class="badge bg-primary"><?= count($agent['recent_properties']) ?></span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Propiedad</th>
                                        <th>Tipo</th>
                                        <th>Precio</th>
                                        <th>Rol</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($agent['recent_properties'], 0, 5) as $property): ?>
                                        <tr>
                                            <td>
                                                <div>
                                                    <strong><?= esc($property['title']) ?></strong>
                                                    <br><small class="text-muted">ID: <?= $property['property_code'] ?></small>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-info"><?= esc($property['property_type_name']) ?></span>
                                            </td>
                                            <td>
                                                <?php if ($property['price_pen']): ?>
                                                    S/. <?= number_format($property['price_pen'], 2) ?>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?= $property['is_primary'] ? 'success' : 'secondary' ?>">
                                                    <?= ucfirst($property['role']) ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('admin/propiedades/edit/' . $property['property_id']) ?>"
                                                   class="btn btn-sm btn-outline-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Recent Inquiries -->
            <?php if (!empty($agent['recent_inquiries'])): ?>
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-envelope me-2"></i>
                            Consultas Recientes
                        </h5>
                        <span class="badge bg-warning"><?= count($agent['recent_inquiries']) ?></span>
                    </div>
                    <div class="card-body p-0">
                        <div class="list-group list-group-flush">
                            <?php foreach (array_slice($agent['recent_inquiries'], 0, 5) as $inquiry): ?>
                                <div class="list-group-item">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="mb-1"><?= esc($inquiry['name']) ?></h6>
                                            <p class="mb-1 text-muted small"><?= esc($inquiry['property_title']) ?></p>
                                            <small class="text-muted"><?= date('d/m/Y H:i', strtotime($inquiry['created_at'])) ?></small>
                                        </div>
                                        <span class="badge bg-<?= $inquiry['status'] === 'new' ? 'warning' : ($inquiry['status'] === 'contacted' ? 'info' : 'success') ?>">
                                            <?= ucfirst($inquiry['status']) ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Current Photo -->
            <?php if ($agent['photo']): ?>
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-image me-2"></i>
                            Foto Actual
                        </h5>
                        <button type="button" class="btn btn-sm btn-outline-danger delete-current-photo"
                                data-agent-id="<?= $agent['id'] ?>">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                    <div class="card-body text-center">
                        <img src="<?= base_url('assets/images/agents/' . $agent['photo']) ?>"
                             alt="<?= esc($agent['first_name']) ?>"
                             class="current-photo">
                    </div>
                </div>
            <?php endif; ?>

            <!-- Photo Upload -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-camera me-2"></i>
                        <?= $agent['photo'] ? 'Cambiar Foto' : 'Subir Foto' ?>
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
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                               <?= $agent['is_active'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_active">
                            <i class="fas fa-user-check text-success me-2"></i>
                            Agente Activo
                        </label>
                        <div class="form-text">Los agentes activos pueden ser asignados a propiedades y aparecen en el sitio web</div>
                    </div>
                </div>
            </div>

            <!-- Agent Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info me-2"></i>
                        Información del Agente
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>ID:</strong> <?= $agent['id'] ?>
                    </div>
                    <div class="mb-2">
                        <strong>Creado:</strong> <?= date('d/m/Y H:i', strtotime($agent['created_at'])) ?>
                    </div>
                    <div class="mb-2">
                        <strong>Actualizado:</strong> <?= date('d/m/Y H:i', strtotime($agent['updated_at'])) ?>
                    </div>
                    <div class="mb-2">
                        <strong>Estado:</strong>
                        <span class="badge bg-<?= $agent['is_active'] ? 'success' : 'secondary' ?>">
                            <?= $agent['is_active'] ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                            <i class="fas fa-save me-2"></i>
                            <span class="btn-text">Actualizar Agente</span>
                            <span class="btn-loading d-none">
                                <i class="fas fa-spinner fa-spin me-2"></i>
                                Actualizando...
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
            showToast('error', 'El archivo es demasiado grande. Máximo 2MB.');
            photoInput.value = '';
            return;
        }

        if (!allowedTypes.includes(file.type)) {
            showToast('error', 'Formato no válido. Solo JPG, PNG, WEBP.');
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

    // Delete current photo
    document.querySelector('.delete-current-photo')?.addEventListener('click', function() {
        const agentId = this.dataset.agentId;

        if (confirm('¿Estás seguro de que quieres eliminar la foto actual?')) {
            fetch(`/admin/agentes/delete-photo/${agentId}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    this.closest('.card').remove();
                    showToast('success', data.message);
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(error => {
                showToast('error', 'Error de conexión');
            });
        }
    });

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
});

// Global function to remove photo
function removePhoto() {
    document.getElementById('photoInput').value = '';
    document.getElementById('photoPreviewContainer').innerHTML = '';
    document.getElementById('photoUploadArea').style.display = 'flex';
}

function showToast(type, message) {
    // Create toast element
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0`;
    toast.setAttribute('role', 'alert');
    toast.setAttribute('aria-live', 'assertive');
    toast.setAttribute('aria-atomic', 'true');

    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">
                <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} me-2"></i>
                ${message}
            </div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;

    // Add to container
    document.querySelector('.toast-container').appendChild(toast);

    // Show toast
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();

    // Remove after hide
    toast.addEventListener('hidden.bs.toast', function() {
        this.remove();
    });
}
</script>
<?= $this->endSection() ?>

<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item">
    <a href="<?= base_url('admin/propiedades') ?>">Propiedades</a>
</li>
<li class="breadcrumb-item active">Crear Nueva</li>
<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<!-- Leaflet CSS for Map -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Image Upload CSS -->
<style>
.image-upload-area {
    border: 2px dashed #dee2e6;
    border-radius: 8px;
    padding: 2rem;
    text-align: center;
    background-color: #f8f9fa;
    cursor: pointer;
    transition: all 0.3s ease;
}

.image-upload-area:hover {
    border-color: #007bff;
    background-color: #e3f2fd;
}

.image-upload-area.dragover {
    border-color: #28a745;
    background-color: #d4edda;
}

.image-preview {
    display: inline-block;
    position: relative;
    margin: 0.5rem;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.image-preview img {
    width: 150px;
    height: 100px;
    object-fit: cover;
}

.image-preview .remove-image {
    position: absolute;
    top: 5px;
    right: 5px;
    background: rgba(220, 53, 69, 0.9);
    color: white;
    border: none;
    border-radius: 50%;
    width: 25px;
    height: 25px;
    font-size: 12px;
    cursor: pointer;
}

.map-container {
    height: 400px;
    border-radius: 8px;
    overflow: hidden;
    border: 1px solid #dee2e6;
}

.agent-selection {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
    background-color: #f8f9fa;
}

.agent-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    margin-bottom: 0.5rem;
    background-color: white;
}

.agent-item.selected {
    border-color: #007bff;
    background-color: #e3f2fd;
}

.feature-group {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    padding: 1rem;
    margin-bottom: 1rem;
}

.feature-item {
    display: flex;
    align-items-center;
    margin-bottom: 0.5rem;
}

.feature-item label {
    margin-left: 0.5rem;
    margin-bottom: 0;
    flex-grow: 1;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Crear Nueva Propiedad</h1>
        <p class="text-muted">Completa la información de la nueva propiedad</p>
    </div>
    <div>
        <a href="<?= base_url('admin/propiedades') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Volver al Listado
        </a>
    </div>
</div>

<!-- Property Form -->
<form id="propertyForm" action="<?= base_url('admin/propiedades/store') ?>" method="POST" enctype="multipart/form-data">
    <?= csrf_field() ?>

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Información Básica
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="title" class="form-label">Título de la Propiedad *</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="property_type_id" class="form-label">Tipo de Propiedad *</label>
                            <select class="form-select" id="property_type_id" name="property_type_id" required>
                                <option value="">Seleccionar tipo</option>
                                <?php foreach ($property_types as $type): ?>
                                    <option value="<?= $type['id'] ?>"><?= esc($type['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" name="description" rows="4"
                                  placeholder="Describe la propiedad, sus características y ventajas..."></textarea>
                    </div>
                </div>
            </div>

            <!-- Transaction & Pricing -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-dollar-sign me-2"></i>
                        Transacción y Precios
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="transaction_type" class="form-label">Tipo de Transacción *</label>
                            <select class="form-select" id="transaction_type" name="transaction_type" required>
                                <option value="">Seleccionar</option>
                                <option value="venta">Venta</option>
                                <option value="alquiler">Alquiler</option>
                                <option value="anticresis">Anticresis</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="price_pen" class="form-label">Precio en Soles (S/.)</label>
                            <input type="number" class="form-control" id="price_pen" name="price_pen" step="0.01" min="0">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="price_usd" class="form-label">Precio en Dólares (USD)</label>
                            <input type="number" class="form-control" id="price_usd" name="price_usd" step="0.01" min="0">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Location & Map -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        Ubicación y Mapa
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="location_id" class="form-label">Ubicación *</label>
                            <select class="form-select" id="location_id" name="location_id" required>
                                <option value="">Seleccionar ubicación</option>
                                <?php foreach ($locations as $location): ?>
                                    <option value="<?= $location['id'] ?>">
                                        <?= esc($location['name']) ?>, <?= esc($location['district']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Dirección Específica</label>
                            <input type="text" class="form-control" id="address" name="address"
                                   placeholder="Ej: Av. Principal 123, Piso 2">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">Latitud</label>
                            <input type="number" class="form-control" id="latitude" name="latitude" step="any" readonly>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">Longitud</label>
                            <input type="number" class="form-control" id="longitude" name="longitude" step="any" readonly>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Seleccionar Ubicación en el Mapa</label>
                        <p class="text-muted small">Haz clic en el mapa para establecer la ubicación exacta de la propiedad</p>
                        <div id="propertyMap" class="map-container"></div>
                    </div>
                </div>
            </div>

            <!-- Property Details -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-home me-2"></i>
                        Detalles de la Propiedad
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="land_area" class="form-label">Área de Terreno (m²)</label>
                            <input type="number" class="form-control" id="land_area" name="land_area" step="0.01" min="0">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="built_area" class="form-label">Área Construida (m²)</label>
                            <input type="number" class="form-control" id="built_area" name="built_area" step="0.01" min="0">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="bedrooms" class="form-label">Habitaciones</label>
                            <input type="number" class="form-control" id="bedrooms" name="bedrooms" min="0" max="20">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="bathrooms" class="form-label">Baños</label>
                            <input type="number" class="form-control" id="bathrooms" name="bathrooms" min="0" max="20">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="half_bathrooms" class="form-label">Medios Baños</label>
                            <input type="number" class="form-control" id="half_bathrooms" name="half_bathrooms" min="0" max="10">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="parking_spaces" class="form-label">Estacionamientos</label>
                            <input type="number" class="form-control" id="parking_spaces" name="parking_spaces" min="0" max="20">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="floors" class="form-label">Pisos</label>
                            <input type="number" class="form-control" id="floors" name="floors" min="1" max="50">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="age_years" class="form-label">Antigüedad (años)</label>
                            <input type="number" class="form-control" id="age_years" name="age_years" min="0" max="100">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Features -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-list me-2"></i>
                        Características
                    </h5>
                </div>
                <div class="card-body">
                    <?php
                    $featuresByCategory = [];
                    foreach ($features as $feature) {
                        $category = $feature['category'] ?: 'otros';
                        $featuresByCategory[$category][] = $feature;
                    }
                    ?>

                    <?php foreach ($featuresByCategory as $category => $categoryFeatures): ?>
                        <div class="feature-group">
                            <h6 class="text-capitalize mb-3"><?= esc($category) ?></h6>
                            <div class="row">
                                <?php foreach ($categoryFeatures as $feature): ?>
                                    <div class="col-md-6">
                                        <div class="feature-item">
                                            <input type="checkbox" class="form-check-input"
                                                   id="feature_<?= $feature['id'] ?>"
                                                   name="features[<?= $feature['id'] ?>]"
                                                   value="Sí">
                                            <label for="feature_<?= $feature['id'] ?>" class="form-check-label">
                                                <?php if ($feature['icon']): ?>
                                                    <i class="fas fa-<?= $feature['icon'] ?> me-2"></i>
                                                <?php endif; ?>
                                                <?= esc($feature['name']) ?>
                                            </label>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Images Upload -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-images me-2"></i>
                        Imágenes de la Propiedad
                    </h5>
                </div>
                <div class="card-body">
                    <div class="image-upload-area" id="imageUploadArea">
                        <i class="fas fa-cloud-upload-alt fa-3x text-muted mb-3"></i>
                        <h5>Arrastra las imágenes aquí o haz clic para seleccionar</h5>
                        <p class="text-muted">Máximo 20 imágenes, 5MB cada una. Formatos: JPG, PNG, WEBP</p>
                        <input type="file" id="imageInput" name="images[]" multiple accept="image/*" class="d-none">
                    </div>

                    <div id="imagePreviewContainer" class="mt-3"></div>
                </div>
            </div>

            <!-- PDF Document -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-file-pdf me-2"></i>
                        Documento PDF
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="pdf_document" class="form-label">Subir Documento PDF</label>
                        <input type="file" class="form-control" id="pdf_document" name="pdf_document" accept=".pdf">
                        <div class="form-text">Máximo 10MB. Solo archivos PDF.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Agent Assignment -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>
                        Asignar Agentes *
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Selecciona uno o más agentes responsables de esta propiedad</p>

                    <div id="agentSelection">
                        <?php foreach ($agents as $agent): ?>
                            <div class="agent-item" data-agent-id="<?= $agent['id'] ?>">
                                <input type="checkbox" class="form-check-input me-3"
                                       id="agent_<?= $agent['id'] ?>"
                                       name="agents[<?= $agent['id'] ?>][agent_id]"
                                       value="<?= $agent['id'] ?>">

                                <div class="flex-grow-1">
                                    <div class="d-flex align-items-center">
                                        <?php if ($agent['photo']): ?>
                                            <img src="<?= base_url('assets/images/agents/' . $agent['photo']) ?>"
                                                 alt="<?= esc($agent['first_name']) ?>"
                                                 class="rounded-circle me-2" width="40" height="40">
                                        <?php else: ?>
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-2"
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        <?php endif; ?>

                                        <div>
                                            <h6 class="mb-0"><?= esc($agent['first_name'] . ' ' . $agent['last_name']) ?></h6>
                                            <small class="text-muted"><?= esc($agent['email']) ?></small>
                                        </div>
                                    </div>

                                    <!-- Agent role and commission (shown when selected) -->
                                    <div class="agent-details mt-2 d-none">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label small">Rol</label>
                                                <select class="form-select form-select-sm"
                                                        name="agents[<?= $agent['id'] ?>][role]">
                                                    <option value="principal">Principal</option>
                                                    <option value="co-agente">Co-agente</option>
                                                    <option value="colaborador">Colaborador</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small">Comisión (%)</label>
                                                <input type="number" class="form-control form-control-sm"
                                                       name="agents[<?= $agent['id'] ?>][commission]"
                                                       min="0" max="100" step="0.01" placeholder="0.00">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-3">
                        <small class="text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            El primer agente seleccionado será el agente principal
                        </small>
                    </div>
                </div>
            </div>

            <!-- Property Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-cog me-2"></i>
                        Estado de la Propiedad
                    </h5>
                </div>
                <div class="card-body">
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" id="featured" name="featured" value="1">
                        <label class="form-check-label" for="featured">
                            <i class="fas fa-star text-warning me-2"></i>
                            Propiedad Destacada
                        </label>
                        <div class="form-text">Las propiedades destacadas aparecen primero en los listados</div>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1" checked>
                        <label class="form-check-label" for="is_active">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Propiedad Activa
                        </label>
                        <div class="form-text">Las propiedades activas son visibles en el sitio web</div>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                            <i class="fas fa-save me-2"></i>
                            <span class="btn-text">Crear Propiedad</span>
                            <span class="btn-loading d-none">
                                <i class="fas fa-spinner fa-spin me-2"></i>
                                Guardando...
                            </span>
                        </button>

                        <button type="button" class="btn btn-outline-secondary" id="saveDraftBtn">
                            <i class="fas fa-file-alt me-2"></i>
                            Guardar Borrador
                        </button>

                        <a href="<?= base_url('admin/propiedades') ?>" class="btn btn-outline-danger">
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
<!-- Leaflet JS for Map -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize map
    const map = L.map('propertyMap').setView([-12.0464, -77.0428], 11); // Lima center

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    let marker = null;

    // Map click handler
    map.on('click', function(e) {
        const lat = e.latlng.lat;
        const lng = e.latlng.lng;

        // Update coordinates
        document.getElementById('latitude').value = lat.toFixed(6);
        document.getElementById('longitude').value = lng.toFixed(6);

        // Add/update marker
        if (marker) {
            marker.setLatLng(e.latlng);
        } else {
            marker = L.marker(e.latlng).addTo(map);
        }
    });

    // Image upload functionality
    const imageUploadArea = document.getElementById('imageUploadArea');
    const imageInput = document.getElementById('imageInput');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');

    imageUploadArea.addEventListener('click', () => imageInput.click());

    imageUploadArea.addEventListener('dragover', function(e) {
        e.preventDefault();
        this.classList.add('dragover');
    });

    imageUploadArea.addEventListener('dragleave', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');
    });

    imageUploadArea.addEventListener('drop', function(e) {
        e.preventDefault();
        this.classList.remove('dragover');

        const files = e.dataTransfer.files;
        handleImageFiles(files);
    });

    imageInput.addEventListener('change', function() {
        handleImageFiles(this.files);
    });

    function handleImageFiles(files) {
        Array.from(files).forEach((file, index) => {
            if (file.type.startsWith('image/')) {
                createImagePreview(file, index);
            }
        });
    }

    function createImagePreview(file, index) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.createElement('div');
            preview.className = 'image-preview';
            preview.innerHTML = `
                <img src="${e.target.result}" alt="Preview">
                <button type="button" class="remove-image" onclick="removeImagePreview(this)">
                    <i class="fas fa-times"></i>
                </button>
            `;
            imagePreviewContainer.appendChild(preview);
        };
        reader.readAsDataURL(file);
    }

    // Agent selection
    document.querySelectorAll('#agentSelection .agent-item').forEach(item => {
        const checkbox = item.querySelector('input[type="checkbox"]');
        const details = item.querySelector('.agent-details');

        checkbox.addEventListener('change', function() {
            if (this.checked) {
                item.classList.add('selected');
                details.classList.remove('d-none');

                // If this is the first selected agent, set as principal
                const selectedAgents = document.querySelectorAll('#agentSelection input[type="checkbox"]:checked');
                if (selectedAgents.length === 1) {
                    const roleSelect = details.querySelector('select[name*="[role]"]');
                    roleSelect.value = 'principal';
                    roleSelect.disabled = true;
                }
            } else {
                item.classList.remove('selected');
                details.classList.add('d-none');

                // Re-enable role selection for remaining agents
                document.querySelectorAll('#agentSelection select[name*="[role]"]').forEach(select => {
                    select.disabled = false;
                });
            }
        });
    });

    // Form submission
    document.getElementById('propertyForm').addEventListener('submit', function(e) {
        // Validate agent selection
        const selectedAgents = document.querySelectorAll('#agentSelection input[type="checkbox"]:checked');
        if (selectedAgents.length === 0) {
            e.preventDefault();
            alert('Debes seleccionar al menos un agente responsable de la propiedad');
            return false;
        }

        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        const btnText = submitBtn.querySelector('.btn-text');
        const btnLoading = submitBtn.querySelector('.btn-loading');

        submitBtn.disabled = true;
        btnText.classList.add('d-none');
        btnLoading.classList.remove('d-none');
    });

    // Save draft functionality
    document.getElementById('saveDraftBtn').addEventListener('click', function() {
        // Set form to draft mode and submit
        const draftInput = document.createElement('input');
        draftInput.type = 'hidden';
        draftInput.name = 'save_draft';
        draftInput.value = '1';
        document.getElementById('propertyForm').appendChild(draftInput);

        document.getElementById('propertyForm').submit();
    });
});

// Global function to remove image preview
function removeImagePreview(button) {
    button.closest('.image-preview').remove();
}

// Auto-resize textarea
document.getElementById('description').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
});

// Price validation
document.getElementById('price_pen').addEventListener('input', function() {
    if (this.value) {
        const usdInput = document.getElementById('price_usd');
        if (!usdInput.value) {
            // Auto-calculate USD price (rough conversion)
            const penPrice = parseFloat(this.value);
            const usdPrice = (penPrice / 3.7).toFixed(2); // Approximate exchange rate
            usdInput.value = usdPrice;
        }
    }
});

document.getElementById('price_usd').addEventListener('input', function() {
    if (this.value) {
        const penInput = document.getElementById('price_pen');
        if (!penInput.value) {
            // Auto-calculate PEN price
            const usdPrice = parseFloat(this.value);
            const penPrice = (usdPrice * 3.7).toFixed(2); // Approximate exchange rate
            penInput.value = penPrice;
        }
    }
});
</script>
<?= $this->endSection() ?>

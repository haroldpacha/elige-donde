<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item">
    <a href="<?= base_url('admin/propiedades') ?>">Propiedades</a>
</li>
<li class="breadcrumb-item active">Editar: <?= esc($property['property_code']) ?></li>
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

.existing-image, .image-preview {
    display: inline-block;
    position: relative;
    margin: 0.5rem;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.existing-image img, .image-preview img {
    width: 150px;
    height: 100px;
    object-fit: cover;
}

.existing-image .image-actions {
    position: absolute;
    top: 5px;
    right: 5px;
    display: flex;
    gap: 2px;
}

.existing-image .btn-sm {
    width: 25px;
    height: 25px;
    padding: 0;
    font-size: 10px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.existing-image.main-image {
    border: 3px solid #28a745;
}

.existing-image.main-image::after {
    content: "Principal";
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background: rgba(40, 167, 69, 0.9);
    color: white;
    text-align: center;
    font-size: 10px;
    padding: 2px;
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
    align-items: center;
    margin-bottom: 0.5rem;
}

.feature-item label {
    margin-left: 0.5rem;
    margin-bottom: 0;
    flex-grow: 1;
}

.document-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem;
    border: 1px solid #dee2e6;
    border-radius: 6px;
    margin-bottom: 0.5rem;
    background-color: white;
}

.document-info {
    display: flex;
    align-items: center;
}

.document-info i {
    font-size: 1.5rem;
    color: #dc3545;
    margin-right: 0.75rem;
}
</style>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Editar Propiedad</h1>
        <p class="text-muted">Código: <?= esc($property['property_code']) ?></p>
    </div>
    <div>
        <a href="<?= base_url('propiedad/' . $property['property_code']) ?>" target="_blank" class="btn btn-outline-info me-2">
            <i class="fas fa-external-link-alt me-2"></i>
            Ver en Sitio Web
        </a>
        <a href="<?= base_url('admin/propiedades') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>
            Volver al Listado
        </a>
    </div>
</div>

<!-- Property Form -->
<form id="propertyForm" action="<?= base_url('admin/propiedades/update/' . $property['id']) ?>" method="POST" enctype="multipart/form-data">
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
                            <input type="text" class="form-control" id="title" name="title" value="<?= esc($property['title']) ?>" required>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="property_type_id" class="form-label">Tipo de Propiedad *</label>
                            <select class="form-select" id="property_type_id" name="property_type_id" required>
                                <option value="">Seleccionar tipo</option>
                                <?php foreach ($property_types as $type): ?>
                                    <option value="<?= $type['id'] ?>" <?= $property['property_type_id'] == $type['id'] ? 'selected' : '' ?>>
                                        <?= esc($type['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Descripción</label>
                        <textarea class="form-control" id="description" name="description" rows="4"
                                  placeholder="Describe la propiedad, sus características y ventajas..."><?= esc($property['description']) ?></textarea>
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
                                <option value="venta" <?= $property['transaction_type'] === 'venta' ? 'selected' : '' ?>>Venta</option>
                                <option value="alquiler" <?= $property['transaction_type'] === 'alquiler' ? 'selected' : '' ?>>Alquiler</option>
                                <option value="anticresis" <?= $property['transaction_type'] === 'anticresis' ? 'selected' : '' ?>>Anticresis</option>
                            </select>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="price_pen" class="form-label">Precio en Soles (S/.)</label>
                            <input type="number" class="form-control" id="price_pen" name="price_pen" step="0.01" min="0" value="<?= $property['price_pen'] ?>">
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="price_usd" class="form-label">Precio en Dólares (USD)</label>
                            <input type="number" class="form-control" id="price_usd" name="price_usd" step="0.01" min="0" value="<?= $property['price_usd'] ?>">
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
                                    <option value="<?= $location['id'] ?>" <?= $property['location_id'] == $location['id'] ? 'selected' : '' ?>>
                                        <?= esc($location['name']) ?>, <?= esc($location['district']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Dirección Específica</label>
                            <input type="text" class="form-control" id="address" name="address"
                                   placeholder="Ej: Av. Principal 123, Piso 2" value="<?= esc($property['address']) ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="latitude" class="form-label">Latitud</label>
                            <input type="number" class="form-control" id="latitude" name="latitude" step="any" readonly value="<?= $property['latitude'] ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="longitude" class="form-label">Longitud</label>
                            <input type="number" class="form-control" id="longitude" name="longitude" step="any" readonly value="<?= $property['longitude'] ?>">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Seleccionar Ubicación en el Mapa</label>
                        <p class="text-muted small">Haz clic en el mapa para actualizar la ubicación exacta de la propiedad</p>
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
                            <input type="number" class="form-control" id="land_area" name="land_area" step="0.01" min="0" value="<?= $property['land_area'] ?>">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="built_area" class="form-label">Área Construida (m²)</label>
                            <input type="number" class="form-control" id="built_area" name="built_area" step="0.01" min="0" value="<?= $property['built_area'] ?>">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="bedrooms" class="form-label">Habitaciones</label>
                            <input type="number" class="form-control" id="bedrooms" name="bedrooms" min="0" max="20" value="<?= $property['bedrooms'] ?>">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="bathrooms" class="form-label">Baños</label>
                            <input type="number" class="form-control" id="bathrooms" name="bathrooms" min="0" max="20" value="<?= $property['bathrooms'] ?>">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="half_bathrooms" class="form-label">Medios Baños</label>
                            <input type="number" class="form-control" id="half_bathrooms" name="half_bathrooms" min="0" max="10" value="<?= $property['half_bathrooms'] ?>">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="parking_spaces" class="form-label">Estacionamientos</label>
                            <input type="number" class="form-control" id="parking_spaces" name="parking_spaces" min="0" max="20" value="<?= $property['parking_spaces'] ?>">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="floors" class="form-label">Pisos</label>
                            <input type="number" class="form-control" id="floors" name="floors" min="1" max="50" value="<?= $property['floors'] ?>">
                        </div>

                        <div class="col-md-3 mb-3">
                            <label for="age_years" class="form-label">Antigüedad (años)</label>
                            <input type="number" class="form-control" id="age_years" name="age_years" min="0" max="100" value="<?= $property['age_years'] ?>">
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
                    foreach ($all_features as $feature) {
                        $category = $feature['category'] ?: 'otros';
                        $featuresByCategory[$category][] = $feature;
                    }

                    // Create array of selected features for easy checking
                    $selectedFeatures = [];
                    foreach ($property['features'] as $feature) {
                        $selectedFeatures[$feature['id']] = $feature['value'];
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
                                                   value="<?= esc($selectedFeatures[$feature['id']] ?? 'Sí') ?>"
                                                   <?= isset($selectedFeatures[$feature['id']]) ? 'checked' : '' ?>>
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

            <!-- Existing Images -->
            <?php if (!empty($property['images'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-images me-2"></i>
                            Imágenes Existentes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="existingImagesContainer">
                            <?php foreach ($property['images'] as $image): ?>
                                <div class="existing-image <?= $image['is_main'] ? 'main-image' : '' ?>" data-image-id="<?= $image['id'] ?>">
                                    <img src="<?= base_url('uploads/properties/images/' . $image['image_url']) ?>"
                                         alt="<?= esc($image['alt_text']) ?>">
                                    <div class="image-actions">
                                        <?php if (!$image['is_main']): ?>
                                            <button type="button" class="btn btn-success btn-sm set-main-image"
                                                    data-image-id="<?= $image['id'] ?>"
                                                    title="Establecer como principal">
                                                <i class="fas fa-star"></i>
                                            </button>
                                        <?php endif; ?>
                                        <button type="button" class="btn btn-danger btn-sm delete-existing-image"
                                                data-image-id="<?= $image['id'] ?>"
                                                title="Eliminar imagen">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- New Images Upload -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>
                        Agregar Nuevas Imágenes
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

            <!-- Existing Documents -->
            <?php if (!empty($property['documents'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-file-pdf me-2"></i>
                            Documentos Existentes
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="existingDocumentsContainer">
                            <?php foreach ($property['documents'] as $document): ?>
                                <div class="document-item" data-document-id="<?= $document['id'] ?>">
                                    <div class="document-info">
                                        <i class="fas fa-file-pdf"></i>
                                        <div>
                                            <h6 class="mb-1"><?= esc($document['file_name']) ?></h6>
                                            <small class="text-muted">
                                                Subido: <?= date('d/m/Y H:i', strtotime($document['created_at'])) ?>
                                                <?php if ($document['uploader_first_name']): ?>
                                                    por <?= esc($document['uploader_first_name'] . ' ' . $document['uploader_last_name']) ?>
                                                <?php endif; ?>
                                            </small>
                                        </div>
                                    </div>
                                    <div class="document-actions">
                                        <a href="<?= base_url($document['file_path']) ?>" target="_blank"
                                           class="btn btn-sm btn-outline-primary me-2" title="Ver documento">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger delete-existing-document"
                                                data-document-id="<?= $document['id'] ?>"
                                                title="Eliminar documento">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- New PDF Document -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-plus me-2"></i>
                        Agregar Nuevo Documento PDF
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
            <!-- Current Agents -->
            <?php if (!empty($property['agents'])): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-users me-2"></i>
                            Agentes Actuales
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($property['agents'] as $agent): ?>
                            <div class="agent-item selected mb-2">
                                <div class="d-flex align-items-center w-100">
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

                                    <div class="flex-grow-1">
                                        <h6 class="mb-0"><?= esc($agent['first_name'] . ' ' . $agent['last_name']) ?></h6>
                                        <small class="text-muted">
                                            <?= ucfirst(esc($agent['role'])) ?>
                                            <?php if ($agent['is_primary']): ?>
                                                <span class="badge bg-primary ms-1">Principal</span>
                                            <?php endif; ?>
                                            <?php if ($agent['commission_percentage'] > 0): ?>
                                                - <?= $agent['commission_percentage'] ?>%
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Agent Assignment -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-plus me-2"></i>
                        Reasignar Agentes *
                    </h5>
                </div>
                <div class="card-body">
                    <p class="text-muted small">Selecciona los agentes responsables de esta propiedad</p>

                    <div id="agentSelection">
                        <?php
                        $currentAgentIds = array_column($property['agents'], 'agent_id');
                        ?>
                        <?php foreach ($agents as $agent): ?>
                            <?php
                            $isSelected = in_array($agent['id'], $currentAgentIds);
                            $currentAgent = null;
                            if ($isSelected) {
                                foreach ($property['agents'] as $propAgent) {
                                    if ($propAgent['agent_id'] == $agent['id']) {
                                        $currentAgent = $propAgent;
                                        break;
                                    }
                                }
                            }
                            ?>
                            <div class="agent-item <?= $isSelected ? 'selected' : '' ?>" data-agent-id="<?= $agent['id'] ?>">
                                <input type="checkbox" class="form-check-input me-3"
                                       id="agent_<?= $agent['id'] ?>"
                                       name="agents[<?= $agent['id'] ?>][agent_id]"
                                       value="<?= $agent['id'] ?>"
                                       <?= $isSelected ? 'checked' : '' ?>>

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

                                    <!-- Agent role and commission -->
                                    <div class="agent-details mt-2 <?= $isSelected ? '' : 'd-none' ?>">
                                        <div class="row">
                                            <div class="col-6">
                                                <label class="form-label small">Rol</label>
                                                <select class="form-select form-select-sm"
                                                        name="agents[<?= $agent['id'] ?>][role]">
                                                    <option value="principal" <?= ($currentAgent && $currentAgent['role'] === 'principal') ? 'selected' : '' ?>>Principal</option>
                                                    <option value="co-agente" <?= ($currentAgent && $currentAgent['role'] === 'co-agente') ? 'selected' : '' ?>>Co-agente</option>
                                                    <option value="colaborador" <?= ($currentAgent && $currentAgent['role'] === 'colaborador') ? 'selected' : '' ?>>Colaborador</option>
                                                </select>
                                            </div>
                                            <div class="col-6">
                                                <label class="form-label small">Comisión (%)</label>
                                                <input type="number" class="form-control form-control-sm"
                                                       name="agents[<?= $agent['id'] ?>][commission]"
                                                       min="0" max="100" step="0.01"
                                                       value="<?= $currentAgent ? $currentAgent['commission_percentage'] : '' ?>"
                                                       placeholder="0.00">
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
                        <input type="checkbox" class="form-check-input" id="featured" name="featured" value="1"
                               <?= $property['featured'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="featured">
                            <i class="fas fa-star text-warning me-2"></i>
                            Propiedad Destacada
                        </label>
                        <div class="form-text">Las propiedades destacadas aparecen primero en los listados</div>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="is_active" name="is_active" value="1"
                               <?= $property['is_active'] ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_active">
                            <i class="fas fa-check-circle text-success me-2"></i>
                            Propiedad Activa
                        </label>
                        <div class="form-text">Las propiedades activas son visibles en el sitio web</div>
                    </div>
                </div>
            </div>

            <!-- Property Info -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-info me-2"></i>
                        Información de la Propiedad
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-2">
                        <strong>Código:</strong> <?= esc($property['property_code']) ?>
                    </div>
                    <div class="mb-2">
                        <strong>Creada:</strong> <?= date('d/m/Y H:i', strtotime($property['created_at'])) ?>
                    </div>
                    <div class="mb-2">
                        <strong>Actualizada:</strong> <?= date('d/m/Y H:i', strtotime($property['updated_at'])) ?>
                    </div>
                    <?php if ($property['published_at']): ?>
                        <div class="mb-2">
                            <strong>Publicada:</strong> <?= date('d/m/Y H:i', strtotime($property['published_at'])) ?>
                        </div>
                    <?php endif; ?>
                    <div class="mb-2">
                        <strong>Imágenes:</strong> <?= count($property['images']) ?>
                    </div>
                    <div class="mb-2">
                        <strong>Documentos:</strong> <?= count($property['documents']) ?>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                            <i class="fas fa-save me-2"></i>
                            <span class="btn-text">Actualizar Propiedad</span>
                            <span class="btn-loading d-none">
                                <i class="fas fa-spinner fa-spin me-2"></i>
                                Actualizando...
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
    const initialLat = <?= $property['latitude'] ?: -12.0464 ?>;
    const initialLng = <?= $property['longitude'] ?: -77.0428 ?>;
    const map = L.map('propertyMap').setView([initialLat, initialLng], <?= $property['latitude'] ? 15 : 11 ?>);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add existing marker if coordinates exist
    let marker = null;
    <?php if ($property['latitude'] && $property['longitude']): ?>
    marker = L.marker([<?= $property['latitude'] ?>, <?= $property['longitude'] ?>]).addTo(map);
    <?php endif; ?>

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

    // Delete existing image
    document.querySelectorAll('.delete-existing-image').forEach(btn => {
        btn.addEventListener('click', function() {
            const imageId = this.dataset.imageId;
            const imageContainer = this.closest('.existing-image');

            if (confirm('¿Estás seguro de que quieres eliminar esta imagen?')) {
                fetch(`/admin/propiedades/delete-image/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        imageContainer.style.transition = 'opacity 0.3s ease';
                        imageContainer.style.opacity = '0';
                        setTimeout(() => imageContainer.remove(), 300);
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
    });

    // Set main image
    document.querySelectorAll('.set-main-image').forEach(btn => {
        btn.addEventListener('click', function() {
            const imageId = this.dataset.imageId;
            const propertyId = <?= $property['id'] ?>;

            fetch(`/admin/propiedades/set-main-image/${propertyId}/${imageId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove main-image class from all images
                    document.querySelectorAll('.existing-image').forEach(img => {
                        img.classList.remove('main-image');
                        const setMainBtn = img.querySelector('.set-main-image');
                        if (setMainBtn) {
                            setMainBtn.style.display = 'flex';
                        }
                    });

                    // Add main-image class to selected image
                    const imageContainer = this.closest('.existing-image');
                    imageContainer.classList.add('main-image');
                    this.style.display = 'none';

                    showToast('success', data.message);
                } else {
                    showToast('error', data.message);
                }
            })
            .catch(error => {
                showToast('error', 'Error de conexión');
            });
        });
    });

    // Delete existing document
    document.querySelectorAll('.delete-existing-document').forEach(btn => {
        btn.addEventListener('click', function() {
            const documentId = this.dataset.documentId;
            const documentContainer = this.closest('.document-item');

            if (confirm('¿Estás seguro de que quieres eliminar este documento?')) {
                fetch(`/admin/propiedades/delete-document/${documentId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        documentContainer.style.transition = 'opacity 0.3s ease';
                        documentContainer.style.opacity = '0';
                        setTimeout(() => documentContainer.remove(), 300);
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
    });

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
            showToast('error', 'Debes seleccionar al menos un agente responsable de la propiedad');
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

// Toast function for feedback
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
    const toastContainer = document.querySelector('.toast-container');
    if (toastContainer) {
        toastContainer.appendChild(toast);

        // Show toast
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();

        // Remove after hide
        toast.addEventListener('hidden.bs.toast', function() {
            this.remove();
        });
    }
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

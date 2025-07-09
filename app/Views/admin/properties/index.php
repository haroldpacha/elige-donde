<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item active">Propiedades</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Gestión de Propiedades</h1>
        <p class="text-muted">Administra todas las propiedades del sistema</p>
    </div>
    <div>
        <a href="<?= base_url('admin/propiedades/crear') ?>" class="btn btn-primary">
            <i class="fas fa-plus me-2"></i>
            Nueva Propiedad
        </a>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Total</h5>
                        <h3><?= number_format($pagination['total_results']) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-home fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Activas</h5>
                        <h3><?= number_format(count(array_filter($properties, function($p) { return $p['is_active']; }))) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Destacadas</h5>
                        <h3><?= number_format(count(array_filter($properties, function($p) { return $p['featured']; }))) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title">Esta Página</h5>
                        <h3><?= count($properties) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i>
            Filtros de Búsqueda
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="<?= base_url('admin/propiedades') ?>">
            <div class="row g-3">
                <div class="col-md-3">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="Título, código o descripción..."
                           value="<?= esc($filters['search']) ?>">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Tipo de Transacción</label>
                    <select name="transaction_type" class="form-select">
                        <option value="">Todos</option>
                        <option value="venta" <?= $filters['transaction_type'] === 'venta' ? 'selected' : '' ?>>Venta</option>
                        <option value="alquiler" <?= $filters['transaction_type'] === 'alquiler' ? 'selected' : '' ?>>Alquiler</option>
                        <option value="anticresis" <?= $filters['transaction_type'] === 'anticresis' ? 'selected' : '' ?>>Anticresis</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Tipo de Propiedad</label>
                    <select name="property_type_id" class="form-select">
                        <option value="">Todos</option>
                        <?php foreach ($property_types as $type): ?>
                            <option value="<?= $type['id'] ?>" <?= $filters['property_type_id'] == $type['id'] ? 'selected' : '' ?>>
                                <?= esc($type['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Ubicación</label>
                    <select name="location_id" class="form-select">
                        <option value="">Todas</option>
                        <?php foreach ($locations as $location): ?>
                            <option value="<?= $location['id'] ?>" <?= $filters['location_id'] == $location['id'] ? 'selected' : '' ?>>
                                <?= esc($location['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Estado</label>
                    <select name="is_active" class="form-select">
                        <option value="">Todos</option>
                        <option value="true" <?= $filters['is_active'] === 'true' ? 'selected' : '' ?>>Activas</option>
                        <option value="false" <?= $filters['is_active'] === 'false' ? 'selected' : '' ?>>Inactivas</option>
                    </select>
                </div>

                <div class="col-md-1">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-12">
                    <a href="<?= base_url('admin/propiedades') ?>" class="btn btn-outline-secondary btn-sm">
                        <i class="fas fa-times me-1"></i>
                        Limpiar Filtros
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Properties Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            Propiedades
            <span class="badge bg-secondary"><?= number_format($pagination['total_results']) ?></span>
        </h5>

        <div class="btn-group" role="group">
            <button type="button" class="btn btn-outline-secondary btn-sm" id="viewGrid">
                <i class="fas fa-th-large"></i>
            </button>
            <button type="button" class="btn btn-outline-secondary btn-sm active" id="viewTable">
                <i class="fas fa-list"></i>
            </button>
        </div>
    </div>

    <div class="card-body p-0">
        <!-- Table View -->
        <div id="tableView" class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th width="60">Imagen</th>
                        <th>Propiedad</th>
                        <th>Tipo</th>
                        <th>Transacción</th>
                        <th>Precio</th>
                        <th>Ubicación</th>
                        <th width="80">Estado</th>
                        <th width="120">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($properties)): ?>
                        <?php foreach ($properties as $property): ?>
                            <tr>
                                <td>
                                    <?php if ($property['main_image']): ?>
                                        <img src="<?= base_url('uploads/properties/images/' . $property['main_image']['image_url']) ?>"
                                             alt="<?= esc($property['title']) ?>"
                                             class="img-thumbnail" width="50" height="50" style="object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-light d-flex align-items-center justify-content-center"
                                             style="width: 50px; height: 50px; border-radius: 4px;">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div>
                                        <h6 class="mb-1"><?= esc($property['title']) ?></h6>
                                        <small class="text-muted">ID: <?= $property['property_code'] ?></small>
                                        <?php if ($property['featured']): ?>
                                            <span class="badge bg-warning text-dark ms-1">Destacada</span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-info"><?= esc($property['property_type_name']) ?></span>
                                </td>

                                <td>
                                    <span class="badge bg-<?= $property['transaction_type'] === 'venta' ? 'success' : ($property['transaction_type'] === 'alquiler' ? 'primary' : 'secondary') ?>">
                                        <?= ucfirst($property['transaction_type']) ?>
                                    </span>
                                </td>

                                <td>
                                    <div>
                                        <?php if ($property['price_pen']): ?>
                                            <div>S/. <?= number_format($property['price_pen'], 2) ?></div>
                                        <?php endif; ?>
                                        <?php if ($property['price_usd']): ?>
                                            <small class="text-muted">USD <?= number_format($property['price_usd'], 2) ?></small>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td>
                                    <div>
                                        <?= esc($property['location_name']) ?>
                                        <br><small class="text-muted"><?= esc($property['district']) ?></small>
                                    </div>
                                </td>

                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle"
                                               type="checkbox"
                                               data-property-id="<?= $property['id'] ?>"
                                               data-type="active"
                                               <?= $property['is_active'] ? 'checked' : '' ?>>
                                    </div>
                                </td>

                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('admin/propiedades/edit/' . $property['id']) ?>"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <button type="button"
                                                class="btn btn-sm btn-outline-warning toggle-featured"
                                                data-property-id="<?= $property['id'] ?>"
                                                title="<?= $property['featured'] ? 'Quitar destacada' : 'Marcar destacada' ?>">
                                            <i class="fas fa-star <?= $property['featured'] ? 'text-warning' : '' ?>"></i>
                                        </button>

                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger delete-property"
                                                data-property-id="<?= $property['id'] ?>"
                                                data-property-title="<?= esc($property['title']) ?>"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="text-center py-4">
                                <i class="fas fa-home fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No se encontraron propiedades</h5>
                                <p class="text-muted">Intenta ajustar los filtros de búsqueda o
                                   <a href="<?= base_url('admin/propiedades/crear') ?>">crear una nueva propiedad</a>
                                </p>
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Grid View (Hidden by default) -->
        <div id="gridView" class="d-none p-4">
            <div class="row">
                <?php if (!empty($properties)): ?>
                    <?php foreach ($properties as $property): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card property-grid-card">
                                <div class="position-relative">
                                    <?php if ($property['main_image']): ?>
                                        <img src="<?= base_url('uploads/properties/images/' . $property['main_image']['image_url']) ?>"
                                             class="card-img-top" alt="<?= esc($property['title']) ?>" style="height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                            <i class="fas fa-image fa-3x text-muted"></i>
                                        </div>
                                    <?php endif; ?>

                                    <div class="position-absolute top-0 end-0 p-2">
                                        <?php if ($property['featured']): ?>
                                            <span class="badge bg-warning text-dark">Destacada</span>
                                        <?php endif; ?>
                                        <span class="badge bg-<?= $property['is_active'] ? 'success' : 'secondary' ?>">
                                            <?= $property['is_active'] ? 'Activa' : 'Inactiva' ?>
                                        </span>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <h6 class="card-title"><?= esc($property['title']) ?></h6>
                                    <p class="text-muted small mb-2">ID: <?= $property['property_code'] ?></p>

                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-info"><?= esc($property['property_type_name']) ?></span>
                                        <span class="badge bg-primary"><?= ucfirst($property['transaction_type']) ?></span>
                                    </div>

                                    <div class="price mb-2">
                                        <?php if ($property['price_pen']): ?>
                                            <strong>S/. <?= number_format($property['price_pen'], 2) ?></strong>
                                        <?php endif; ?>
                                        <?php if ($property['price_usd']): ?>
                                            <br><small class="text-muted">USD <?= number_format($property['price_usd'], 2) ?></small>
                                        <?php endif; ?>
                                    </div>

                                    <p class="text-muted small">
                                        <i class="fas fa-map-marker-alt me-1"></i>
                                        <?= esc($property['location_name']) ?>, <?= esc($property['district']) ?>
                                    </p>
                                </div>

                                <div class="card-footer bg-transparent">
                                    <div class="d-flex gap-2">
                                        <a href="<?= base_url('admin/propiedades/edit/' . $property['id']) ?>"
                                           class="btn btn-sm btn-primary flex-fill">
                                            <i class="fas fa-edit me-1"></i>Editar
                                        </a>
                                        <button class="btn btn-sm btn-outline-warning toggle-featured"
                                                data-property-id="<?= $property['id'] ?>">
                                            <i class="fas fa-star"></i>
                                        </button>
                                        <button class="btn btn-sm btn-outline-danger delete-property"
                                                data-property-id="<?= $property['id'] ?>"
                                                data-property-title="<?= esc($property['title']) ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-home fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No se encontraron propiedades</h5>
                        <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                        <a href="<?= base_url('admin/propiedades/crear') ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Crear Nueva Propiedad
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($pagination['total_pages'] > 1): ?>
        <div class="card-footer">
            <nav aria-label="Paginación de propiedades">
                <ul class="pagination justify-content-center mb-0">
                    <!-- Previous -->
                    <li class="page-item <?= $pagination['current_page'] <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $pagination['current_page'] - 1 ?>&<?= http_build_query($filters) ?>">
                            <i class="fas fa-chevron-left"></i>
                        </a>
                    </li>

                    <!-- Pages -->
                    <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
                        <li class="page-item <?= $i === $pagination['current_page'] ? 'active' : '' ?>">
                            <a class="page-link" href="?page=<?= $i ?>&<?= http_build_query($filters) ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>

                    <!-- Next -->
                    <li class="page-item <?= $pagination['current_page'] >= $pagination['total_pages'] ? 'disabled' : '' ?>">
                        <a class="page-link" href="?page=<?= $pagination['current_page'] + 1 ?>&<?= http_build_query($filters) ?>">
                            <i class="fas fa-chevron-right"></i>
                        </a>
                    </li>
                </ul>
            </nav>

            <div class="text-center mt-2">
                <small class="text-muted">
                    Mostrando <?= (($pagination['current_page'] - 1) * $pagination['per_page']) + 1 ?> -
                    <?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total_results']) ?>
                    de <?= number_format($pagination['total_results']) ?> propiedades
                </small>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // View toggle
    document.getElementById('viewGrid').addEventListener('click', function() {
        document.getElementById('tableView').classList.add('d-none');
        document.getElementById('gridView').classList.remove('d-none');
        this.classList.add('active');
        document.getElementById('viewTable').classList.remove('active');
    });

    document.getElementById('viewTable').addEventListener('click', function() {
        document.getElementById('gridView').classList.add('d-none');
        document.getElementById('tableView').classList.remove('d-none');
        this.classList.add('active');
        document.getElementById('viewGrid').classList.remove('active');
    });

    // Status toggle
    document.querySelectorAll('.status-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const propertyId = this.dataset.propertyId;
            const isActive = this.checked;

            fetch(`/admin/propiedades/toggle-active/${propertyId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showToast('success', data.message);
                } else {
                    this.checked = !isActive; // Revert toggle
                    showToast('error', data.message);
                }
            })
            .catch(error => {
                this.checked = !isActive; // Revert toggle
                showToast('error', 'Error de conexión');
            });
        });
    });

    // Featured toggle
    document.querySelectorAll('.toggle-featured').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const propertyId = this.dataset.propertyId;

            fetch(`/admin/propiedades/toggle-featured/${propertyId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const icon = this.querySelector('i');
                    if (data.featured) {
                        icon.classList.add('text-warning');
                    } else {
                        icon.classList.remove('text-warning');
                    }
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

    // Delete property
    document.querySelectorAll('.delete-property').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const propertyId = this.dataset.propertyId;
            const propertyTitle = this.dataset.propertyTitle;

            if (confirm(`¿Estás seguro de que quieres eliminar la propiedad "${propertyTitle}"?\n\nEsta acción no se puede deshacer.`)) {
                fetch(`/admin/propiedades/delete/${propertyId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove row from table
                        this.closest('tr, .col-lg-4').remove();
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
});

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

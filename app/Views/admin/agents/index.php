<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item active">Agentes</li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Gestión de Agentes</h1>
        <p class="text-muted">Administra todos los agentes inmobiliarios del sistema</p>
    </div>
    <div>
        <div class="btn-group" role="group">
            <a href="<?= base_url('admin/agentes/export') ?>" class="btn btn-outline-success">
                <i class="fas fa-download me-2"></i>
                Exportar CSV
            </a>
            <a href="<?= base_url('admin/agentes/crear') ?>" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>
                Nuevo Agente
            </a>
        </div>
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
                        <i class="fas fa-users fa-2x"></i>
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
                        <h5 class="card-title">Activos</h5>
                        <h3><?= number_format(count(array_filter($agents, function($a) { return $a['is_active']; }))) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-check fa-2x"></i>
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
                        <h5 class="card-title">Con Licencia</h5>
                        <h3><?= number_format(count(array_filter($agents, function($a) { return !empty($a['license_number']); }))) ?></h3>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-certificate fa-2x"></i>
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
                        <h5 class="card-title">Esta Página</h5>
                        <h3><?= count($agents) ?></h3>
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
        <form method="GET" action="<?= base_url('admin/agentes') ?>">
            <div class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">Buscar</label>
                    <input type="text" name="search" class="form-control"
                           placeholder="Nombre, email, teléfono..."
                           value="<?= esc($filters['search']) ?>">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Estado</label>
                    <select name="is_active" class="form-select">
                        <option value="">Todos</option>
                        <option value="true" <?= $filters['is_active'] === 'true' ? 'selected' : '' ?>>Activos</option>
                        <option value="false" <?= $filters['is_active'] === 'false' ? 'selected' : '' ?>>Inactivos</option>
                    </select>
                </div>

                <div class="col-md-3">
                    <label class="form-label">Licencia</label>
                    <select name="license_number" class="form-select">
                        <option value="">Todos</option>
                        <option value="yes" <?= $filters['license_number'] === 'yes' ? 'selected' : '' ?>>Con Licencia</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">&nbsp;</label>
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search"></i>
                        </button>
                        <a href="<?= base_url('admin/agentes') ?>" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-times"></i>
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Agents Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="card-title mb-0">
            Agentes
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
                        <th width="60">Foto</th>
                        <th>Agente</th>
                        <th>Contacto</th>
                        <th>Empresa</th>
                        <th>Propiedades</th>
                        <th>Consultas</th>
                        <th width="80">Estado</th>
                        <th width="150">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($agents)): ?>
                        <?php foreach ($agents as $agent): ?>
                            <tr>
                                <td>
                                    <?php if ($agent['photo']): ?>
                                        <img src="<?= base_url('assets/images/agents/' . $agent['photo']) ?>"
                                             alt="<?= esc($agent['first_name']) ?>"
                                             class="rounded-circle" width="50" height="50" style="object-fit: cover;">
                                    <?php else: ?>
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                             style="width: 50px; height: 50px;">
                                            <i class="fas fa-user text-white"></i>
                                        </div>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <div>
                                        <h6 class="mb-1"><?= esc($agent['first_name'] . ' ' . $agent['last_name']) ?></h6>
                                        <small class="text-muted"><?= esc($agent['email']) ?></small>
                                        <?php if ($agent['license_number']): ?>
                                            <br><span class="badge bg-success text-white">Licencia: <?= esc($agent['license_number']) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td>
                                    <div>
                                        <?php if ($agent['phone']): ?>
                                            <div><i class="fas fa-phone me-1"></i> <?= esc($agent['phone']) ?></div>
                                        <?php endif; ?>
                                        <?php if ($agent['cell_phone']): ?>
                                            <div><i class="fas fa-mobile-alt me-1"></i> <?= esc($agent['cell_phone']) ?></div>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-info"><?= esc($agent['company']) ?></span>
                                </td>

                                <td>
                                    <div class="text-center">
                                        <div class="fw-bold text-primary"><?= $agent['total_properties'] ?></div>
                                        <small class="text-muted">Total</small>
                                        <?php if ($agent['primary_properties'] > 0): ?>
                                            <br><small class="text-success"><?= $agent['primary_properties'] ?> Principal</small>
                                        <?php endif; ?>
                                    </div>
                                </td>

                                <td>
                                    <div class="text-center">
                                        <div class="fw-bold text-warning"><?= $agent['recent_inquiries'] ?></div>
                                        <small class="text-muted">Últimos 30 días</small>
                                    </div>
                                </td>

                                <td>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle"
                                               type="checkbox"
                                               data-agent-id="<?= $agent['id'] ?>"
                                               <?= $agent['is_active'] ? 'checked' : '' ?>>
                                    </div>
                                </td>

                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?= base_url('admin/agentes/show/' . $agent['id']) ?>"
                                           class="btn btn-sm btn-outline-info"
                                           title="Ver detalles">
                                            <i class="fas fa-eye"></i>
                                        </a>

                                        <a href="<?= base_url('admin/agentes/edit/' . $agent['id']) ?>"
                                           class="btn btn-sm btn-outline-primary"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>

                                        <a href="mailto:<?= $agent['email'] ?>"
                                           class="btn btn-sm btn-outline-success"
                                           title="Enviar email">
                                            <i class="fas fa-envelope"></i>
                                        </a>

                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger delete-agent"
                                                data-agent-id="<?= $agent['id'] ?>"
                                                data-agent-name="<?= esc($agent['first_name'] . ' ' . $agent['last_name']) ?>"
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
                                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No se encontraron agentes</h5>
                                <p class="text-muted">Intenta ajustar los filtros de búsqueda o
                                   <a href="<?= base_url('admin/agentes/crear') ?>">crear un nuevo agente</a>
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
                <?php if (!empty($agents)): ?>
                    <?php foreach ($agents as $agent): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card agent-grid-card">
                                <div class="card-body text-center">
                                    <div class="position-relative d-inline-block mb-3">
                                        <?php if ($agent['photo']): ?>
                                            <img src="<?= base_url('assets/images/agents/' . $agent['photo']) ?>"
                                                 alt="<?= esc($agent['first_name']) ?>"
                                                 class="rounded-circle" width="80" height="80" style="object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center mx-auto"
                                                 style="width: 80px; height: 80px;">
                                                <i class="fas fa-user fa-2x text-white"></i>
                                            </div>
                                        <?php endif; ?>

                                        <div class="position-absolute top-0 end-0">
                                            <span class="badge bg-<?= $agent['is_active'] ? 'success' : 'secondary' ?> rounded-pill">
                                                <?= $agent['is_active'] ? 'Activo' : 'Inactivo' ?>
                                            </span>
                                        </div>
                                    </div>

                                    <h6 class="card-title"><?= esc($agent['first_name'] . ' ' . $agent['last_name']) ?></h6>
                                    <p class="text-muted small mb-2"><?= esc($agent['email']) ?></p>

                                    <?php if ($agent['license_number']): ?>
                                        <span class="badge bg-success mb-2">Lic: <?= esc($agent['license_number']) ?></span>
                                    <?php endif; ?>

                                    <div class="row text-center mb-3">
                                        <div class="col-4">
                                            <div class="fw-bold text-primary"><?= $agent['total_properties'] ?></div>
                                            <small class="text-muted">Propiedades</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="fw-bold text-success"><?= $agent['primary_properties'] ?></div>
                                            <small class="text-muted">Principal</small>
                                        </div>
                                        <div class="col-4">
                                            <div class="fw-bold text-warning"><?= $agent['recent_inquiries'] ?></div>
                                            <small class="text-muted">Consultas</small>
                                        </div>
                                    </div>

                                    <div class="d-flex gap-1 justify-content-center">
                                        <a href="<?= base_url('admin/agentes/show/' . $agent['id']) ?>"
                                           class="btn btn-sm btn-outline-info" title="Ver">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?= base_url('admin/agentes/edit/' . $agent['id']) ?>"
                                           class="btn btn-sm btn-outline-primary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="mailto:<?= $agent['email'] ?>"
                                           class="btn btn-sm btn-outline-success" title="Email">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-danger delete-agent"
                                                data-agent-id="<?= $agent['id'] ?>"
                                                data-agent-name="<?= esc($agent['first_name'] . ' ' . $agent['last_name']) ?>"
                                                title="Eliminar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-users fa-4x text-muted mb-3"></i>
                        <h5 class="text-muted">No se encontraron agentes</h5>
                        <p class="text-muted">Intenta ajustar los filtros de búsqueda</p>
                        <a href="<?= base_url('admin/agentes/crear') ?>" class="btn btn-primary">
                            <i class="fas fa-plus me-2"></i>Crear Nuevo Agente
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    <?php if ($pagination['total_pages'] > 1): ?>
        <div class="card-footer">
            <nav aria-label="Paginación de agentes">
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
                    de <?= number_format($pagination['total_results']) ?> agentes
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
            const agentId = this.dataset.agentId;
            const isActive = this.checked;

            fetch(`/admin/agentes/toggle-active/${agentId}`, {
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

    // Delete agent
    document.querySelectorAll('.delete-agent').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const agentId = this.dataset.agentId;
            const agentName = this.dataset.agentName;

            if (confirm(`¿Estás seguro de que quieres eliminar al agente "${agentName}"?\n\nEsta acción no se puede deshacer.`)) {
                fetch(`/admin/agentes/delete/${agentId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Remove row from table or card from grid
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

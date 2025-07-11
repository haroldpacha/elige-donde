<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('breadcrumb') ?>
<li class="breadcrumb-item">
    <a href="<?= base_url('admin/agentes') ?>">Agentes</a>
</li>
<li class="breadcrumb-item active"><?= esc($agent['first_name'] . ' ' . $agent['last_name']) ?></li>
<?= $this->endSection() ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center">
        <div class="me-3">
            <?php if ($agent['photo']): ?>
                <img src="<?= base_url('assets/images/agents/' . $agent['photo']) ?>"
                     alt="<?= esc($agent['first_name']) ?>"
                     class="rounded-circle" width="80" height="80" style="object-fit: cover;">
            <?php else: ?>
                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                     style="width: 80px; height: 80px;">
                    <i class="fas fa-user fa-2x text-white"></i>
                </div>
            <?php endif; ?>
        </div>
        <div>
            <h1 class="h3 mb-0"><?= esc($agent['first_name'] . ' ' . $agent['last_name']) ?></h1>
            <p class="text-muted mb-1"><?= esc($agent['email']) ?></p>
            <div>
                <span class="badge bg-<?= $agent['is_active'] ? 'success' : 'secondary' ?> me-2">
                    <?= $agent['is_active'] ? 'Activo' : 'Inactivo' ?>
                </span>
                <?php if ($agent['license_number']): ?>
                    <span class="badge bg-info">Licencia: <?= esc($agent['license_number']) ?></span>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div>
        <div class="btn-group" role="group">
            <a href="<?= base_url('asesor/' . $agent['id']) ?>" target="_blank" class="btn btn-outline-info">
                <i class="fas fa-external-link-alt me-2"></i>
                Ver en Sitio Web
            </a>
            <a href="<?= base_url('admin/agentes/edit/' . $agent['id']) ?>" class="btn btn-primary">
                <i class="fas fa-edit me-2"></i>
                Editar Agente
            </a>
            <a href="<?= base_url('admin/agentes') ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>
                Volver al Listado
            </a>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Total Propiedades</h5>
                        <h3 class="mb-0"><?= $agent['total_properties'] ?></h3>
                    </div>
                    <i class="fas fa-home fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">Principal</h5>
                        <h3 class="mb-0"><?= $agent['primary_properties'] ?></h3>
                    </div>
                    <i class="fas fa-star fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">En Venta</h5>
                        <h3 class="mb-0"><?= $agent['sale_properties'] ?></h3>
                    </div>
                    <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card stat-card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title">En Alquiler</h5>
                        <h3 class="mb-0"><?= $agent['rent_properties'] ?></h3>
                    </div>
                    <i class="fas fa-key fa-2x opacity-75"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Left Column -->
    <div class="col-lg-8">
        <!-- Agent Properties -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-home me-2"></i>
                    Propiedades del Agente
                </h5>
                <span class="badge bg-primary"><?= count($agent['properties']) ?></span>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($agent['properties'])): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Propiedad</th>
                                    <th>Tipo</th>
                                    <th>Transacción</th>
                                    <th>Precio</th>
                                    <th>Rol</th>
                                    <th>Comisión</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($agent['properties'] as $property): ?>
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
                                            <span class="badge bg-<?= $property['transaction_type'] === 'venta' ? 'success' : 'primary' ?>">
                                                <?= ucfirst($property['transaction_type']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($property['price_pen']): ?>
                                                <div>S/. <?= number_format($property['price_pen'], 2) ?></div>
                                            <?php endif; ?>
                                            <?php if ($property['price_usd']): ?>
                                                <small class="text-muted">USD <?= number_format($property['price_usd'], 2) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?= $property['is_primary'] ? 'success' : 'secondary' ?>">
                                                <?= ucfirst($property['role']) ?>
                                                <?php if ($property['is_primary']): ?>
                                                    <i class="fas fa-star ms-1"></i>
                                                <?php endif; ?>
                                            </span>
                                        </td>
                                        <td>
                                            <?php if ($property['commission_percentage'] > 0): ?>
                                                <span class="text-success fw-bold"><?= $property['commission_percentage'] ?>%</span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="<?= base_url('propiedad/' . $property['property_code']) ?>"
                                                   target="_blank"
                                                   class="btn btn-sm btn-outline-info" title="Ver en sitio">
                                                    <i class="fas fa-external-link-alt"></i>
                                                </a>
                                                <a href="<?= base_url('admin/propiedades/edit/' . $property['property_id']) ?>"
                                                   class="btn btn-sm btn-outline-primary" title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-home fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No hay propiedades asignadas</h6>
                        <p class="text-muted">Este agente aún no tiene propiedades asignadas.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Agent Inquiries -->
        <div class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-envelope me-2"></i>
                    Consultas Recibidas
                </h5>
                <span class="badge bg-warning"><?= count($agent['inquiries']) ?></span>
            </div>
            <div class="card-body p-0">
                <?php if (!empty($agent['inquiries'])): ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($agent['inquiries'] as $inquiry): ?>
                            <div class="list-group-item">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <div class="d-flex justify-content-between align-items-center mb-1">
                                            <h6 class="mb-0"><?= esc($inquiry['name']) ?></h6>
                                            <span class="badge bg-<?= $inquiry['status'] === 'new' ? 'warning' : ($inquiry['status'] === 'contacted' ? 'info' : 'success') ?>">
                                                <?= ucfirst($inquiry['status']) ?>
                                            </span>
                                        </div>
                                        <p class="mb-1 text-muted small">
                                            <strong>Propiedad:</strong> <?= esc($inquiry['property_title']) ?>
                                        </p>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small class="text-muted">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    <?= esc($inquiry['email']) ?>
                                                </small>
                                            </div>
                                            <div class="col-md-6">
                                                <?php if ($inquiry['phone']): ?>
                                                    <small class="text-muted">
                                                        <i class="fas fa-phone me-1"></i>
                                                        <?= esc($inquiry['phone']) ?>
                                                    </small>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php if ($inquiry['message']): ?>
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <strong>Mensaje:</strong> <?= esc(substr($inquiry['message'], 0, 100)) ?><?= strlen($inquiry['message']) > 100 ? '...' : '' ?>
                                                </small>
                                            </div>
                                        <?php endif; ?>
                                        <small class="text-muted">
                                            <i class="fas fa-clock me-1"></i>
                                            <?= date('d/m/Y H:i', strtotime($inquiry['created_at'])) ?>
                                        </small>
                                    </div>
                                    <div class="ms-3">
                                        <div class="btn-group-vertical" role="group">
                                            <a href="mailto:<?= $inquiry['email'] ?>"
                                               class="btn btn-sm btn-outline-success" title="Responder email">
                                                <i class="fas fa-reply"></i>
                                            </a>
                                            <?php if ($inquiry['phone']): ?>
                                                <a href="tel:<?= $inquiry['phone'] ?>"
                                                   class="btn btn-sm btn-outline-primary" title="Llamar">
                                                    <i class="fas fa-phone"></i>
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-envelope fa-3x text-muted mb-3"></i>
                        <h6 class="text-muted">No hay consultas recibidas</h6>
                        <p class="text-muted">Este agente aún no ha recibido consultas.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Right Column -->
    <div class="col-lg-4">
        <!-- Agent Contact Info -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-address-card me-2"></i>
                    Información de Contacto
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">Email:</label>
                    <div>
                        <a href="mailto:<?= $agent['email'] ?>" class="text-decoration-none">
                            <?= esc($agent['email']) ?>
                        </a>
                    </div>
                </div>

                <?php if ($agent['phone']): ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Teléfono:</label>
                        <div>
                            <a href="tel:<?= $agent['phone'] ?>" class="text-decoration-none">
                                <?= esc($agent['phone']) ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if ($agent['cell_phone']): ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Celular:</label>
                        <div>
                            <a href="tel:<?= $agent['cell_phone'] ?>" class="text-decoration-none">
                                <?= esc($agent['cell_phone']) ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="mb-3">
                    <label class="form-label fw-bold">Empresa:</label>
                    <div><?= esc($agent['company']) ?></div>
                </div>

                <?php if ($agent['license_number']): ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Licencia:</label>
                        <div>
                            <span class="badge bg-success"><?= esc($agent['license_number']) ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="d-grid gap-2">
                    <a href="mailto:<?= $agent['email'] ?>" class="btn btn-success">
                        <i class="fas fa-envelope me-2"></i>
                        Enviar Email
                    </a>
                    <?php if ($agent['cell_phone']): ?>
                        <a href="https://wa.me/51<?= preg_replace('/[^0-9]/', '', $agent['cell_phone']) ?>"
                           target="_blank" class="btn btn-outline-success">
                            <i class="fab fa-whatsapp me-2"></i>
                            WhatsApp
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Performance Stats -->
        <?php if (isset($agent['role_stats'])): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Estadísticas de Rol
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="fw-bold text-success h4"><?= $agent['role_stats']['principal'] ?></div>
                            <small class="text-muted">Principal</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-info h4"><?= $agent['role_stats']['co-agente'] ?></div>
                            <small class="text-muted">Co-agente</small>
                        </div>
                        <div class="col-4">
                            <div class="fw-bold text-secondary h4"><?= $agent['role_stats']['colaborador'] ?></div>
                            <small class="text-muted">Colaborador</small>
                        </div>
                    </div>

                    <?php if (isset($agent['average_commission']) && $agent['average_commission'] > 0): ?>
                        <hr>
                        <div class="text-center">
                            <div class="fw-bold text-warning h4"><?= $agent['average_commission'] ?>%</div>
                            <small class="text-muted">Comisión Promedio</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Frequent Collaborators -->
        <?php if (isset($agent['frequent_collaborators']) && !empty($agent['frequent_collaborators'])): ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-users me-2"></i>
                        Colaboradores Frecuentes
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="list-group list-group-flush">
                        <?php foreach ($agent['frequent_collaborators'] as $collaborator): ?>
                            <div class="list-group-item d-flex align-items-center">
                                <?php if ($collaborator['photo']): ?>
                                    <img src="<?= base_url('assets/images/agents/' . $collaborator['photo']) ?>"
                                         alt="<?= esc($collaborator['first_name']) ?>"
                                         class="rounded-circle me-3" width="40" height="40" style="object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3"
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-white"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="flex-grow-1">
                                    <h6 class="mb-0"><?= esc($collaborator['first_name'] . ' ' . $collaborator['last_name']) ?></h6>
                                    <small class="text-muted"><?= $collaborator['collaboration_count'] ?> colaboraciones</small>
                                </div>

                                <a href="<?= base_url('admin/agentes/show/' . $collaborator['agent_id']) ?>"
                                   class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>

        <!-- Agent Timeline -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>
                    Información del Sistema
                </h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label fw-bold">ID del Agente:</label>
                    <div><code><?= $agent['id'] ?></code></div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Fecha de Registro:</label>
                    <div><?= date('d/m/Y H:i', strtotime($agent['created_at'])) ?></div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Última Actualización:</label>
                    <div><?= date('d/m/Y H:i', strtotime($agent['updated_at'])) ?></div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Estado:</label>
                    <div>
                        <span class="badge bg-<?= $agent['is_active'] ? 'success' : 'secondary' ?> fs-6">
                            <?= $agent['is_active'] ? 'Activo' : 'Inactivo' ?>
                        </span>
                    </div>
                </div>

                <div class="d-grid">
                    <a href="<?= base_url('admin/agentes/edit/' . $agent['id']) ?>" class="btn btn-primary">
                        <i class="fas fa-edit me-2"></i>
                        Editar Agente
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to stat cards
    const statCards = document.querySelectorAll('.stat-card');
    statCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
            this.style.transition = 'transform 0.3s ease';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });

    // Print functionality
    window.printAgentInfo = function() {
        window.print();
    };
});
</script>
<?= $this->endSection() ?>

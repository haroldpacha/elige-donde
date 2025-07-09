<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>

<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Dashboard</h1>
        <p class="text-muted">Bienvenido al panel administrativo de RE/MAX Perú</p>
    </div>
    <div>
        <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#quickStatsModal">
            <i class="fas fa-chart-line me-2"></i>
            Ver Estadísticas
        </button>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-left-primary">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Propiedades
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= number_format($stats['total_properties']) ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-home fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-left-success">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Propiedades Activas
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= number_format($stats['active_properties']) ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-left-info">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Agentes Activos
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= number_format($stats['total_agents']) ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-users fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-xl-3 col-md-6 mb-3">
        <div class="card stat-card border-left-warning">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                            Consultas Pendientes
                        </div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">
                            <?= number_format($stats['pending_inquiries']) ?>
                        </div>
                    </div>
                    <div class="col-auto">
                        <i class="fas fa-envelope fa-2x text-gray-300"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row mb-4">
    <!-- Properties by Type Chart -->
    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Propiedades por Tipo</h6>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="propertyTypeChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Monthly Growth Chart -->
    <div class="col-xl-6 col-lg-6">
        <div class="card">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Propiedades Agregadas (Últimos 6 Meses)</h6>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monthlyGrowthChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <!-- Recent Properties -->
    <div class="col-xl-4 col-lg-6 mb-4">
        <div class="card">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Propiedades Recientes</h6>
                <a href="<?= base_url('admin/propiedades') ?>" class="btn btn-sm btn-outline-primary">Ver Todas</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (!empty($recent_properties)): ?>
                        <?php foreach ($recent_properties as $property): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?= esc($property['title']) ?></h6>
                                    <p class="mb-1 text-muted small">ID: <?= $property['property_code'] ?></p>
                                    <small class="text-muted"><?= date('d/m/Y', strtotime($property['created_at'])) ?></small>
                                </div>
                                <a href="<?= base_url('admin/propiedades/edit/' . $property['id']) ?>" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item text-center text-muted">
                            No hay propiedades recientes
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Inquiries -->
    <div class="col-xl-4 col-lg-6 mb-4">
        <div class="card">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Consultas Recientes</h6>
                <a href="<?= base_url('admin/consultas') ?>" class="btn btn-sm btn-outline-primary">Ver Todas</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (!empty($recent_inquiries)): ?>
                        <?php foreach ($recent_inquiries as $inquiry): ?>
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
                    <?php else: ?>
                        <div class="list-group-item text-center text-muted">
                            No hay consultas recientes
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Documents -->
    <div class="col-xl-4 col-lg-6 mb-4">
        <div class="card">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Documentos Recientes</h6>
                <a href="<?= base_url('admin/documentos') ?>" class="btn btn-sm btn-outline-primary">Ver Todos</a>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php if (!empty($recent_documents)): ?>
                        <?php foreach ($recent_documents as $document): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?= esc($document['file_name']) ?></h6>
                                    <p class="mb-1 text-muted small"><?= esc($document['property_title']) ?></p>
                                    <small class="text-muted"><?= date('d/m/Y', strtotime($document['created_at'])) ?></small>
                                </div>
                                <a href="<?= base_url($document['file_path']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-download"></i>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="list-group-item text-center text-muted">
                            No hay documentos recientes
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h6 class="m-0 font-weight-bold text-primary">Acciones Rápidas</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('admin/propiedades/crear') ?>" class="btn btn-success w-100">
                            <i class="fas fa-plus-circle mb-2"></i><br>
                            Nueva Propiedad
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('admin/agentes/crear') ?>" class="btn btn-info w-100">
                            <i class="fas fa-user-plus mb-2"></i><br>
                            Nuevo Agente
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('admin/consultas') ?>" class="btn btn-warning w-100">
                            <i class="fas fa-envelope-open mb-2"></i><br>
                            Ver Consultas
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="<?= base_url('admin/reportes') ?>" class="btn btn-secondary w-100">
                            <i class="fas fa-chart-bar mb-2"></i><br>
                            Generar Reporte
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Stats Modal -->
<div class="modal fade" id="quickStatsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Estadísticas Rápidas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Propiedades</h6>
                        <ul class="list-unstyled">
                            <li>Total: <strong><?= number_format($stats['total_properties']) ?></strong></li>
                            <li>Activas: <strong><?= number_format($stats['active_properties']) ?></strong></li>
                            <li>Destacadas: <strong><?= number_format($stats['featured_properties']) ?></strong></li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h6>Sistema</h6>
                        <ul class="list-unstyled">
                            <li>Agentes: <strong><?= number_format($stats['total_agents']) ?></strong></li>
                            <li>Consultas: <strong><?= number_format($stats['pending_inquiries']) ?></strong></li>
                            <li>Documentos: <strong><?= number_format($stats['total_documents']) ?></strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Property Type Chart
    const propertyTypeData = <?= json_encode($property_stats) ?>;
    const propertyTypeLabels = propertyTypeData.map(item => item.type_name || 'Sin categoría');
    const propertyTypeCounts = propertyTypeData.map(item => item.count);

    const propertyTypeCtx = document.getElementById('propertyTypeChart').getContext('2d');
    new Chart(propertyTypeCtx, {
        type: 'doughnut',
        data: {
            labels: propertyTypeLabels,
            datasets: [{
                data: propertyTypeCounts,
                backgroundColor: [
                    '#FF6384',
                    '#36A2EB',
                    '#FFCE56',
                    '#4BC0C0',
                    '#9966FF',
                    '#FF9F40'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    // Monthly Growth Chart
    const monthlyData = <?= json_encode($monthly_stats) ?>;
    const monthlyLabels = monthlyData.map(item => item.month);
    const monthlyCounts = monthlyData.map(item => item.count);

    const monthlyGrowthCtx = document.getElementById('monthlyGrowthChart').getContext('2d');
    new Chart(monthlyGrowthCtx, {
        type: 'line',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Propiedades Agregadas',
                data: monthlyCounts,
                borderColor: '#36A2EB',
                backgroundColor: 'rgba(54, 162, 235, 0.1)',
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
});
</script>
<?= $this->endSection() ?>

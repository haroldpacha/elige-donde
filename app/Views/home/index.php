<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- Hero Section -->
<section class="hero-section position-relative">
    <div class="hero-background">
        <img src="<?= base_url('assets/images/hero-bg.jpg') ?>" alt="RE/MAX Perú" class="w-100 h-100 object-cover">
        <div class="hero-overlay"></div>
    </div>

    <div class="hero-content position-absolute top-50 start-50 translate-middle text-center text-white">
        <h1 class="hero-title display-4 fw-bold mb-4">
            Nadie en el mundo vende más bienes raíces que RE/MAX
        </h1>

        <!-- Transaction Type Buttons -->
        <div class="transaction-buttons mb-4">
            <button class="btn btn-outline-light me-2 active" data-type="venta">Venta</button>
            <button class="btn btn-outline-light me-2" data-type="alquiler">Alquiler</button>
            <button class="btn btn-outline-light" data-type="anticresis">Anticresis</button>
        </div>

        <!-- Search Form -->
        <div class="search-form-container">
            <form id="propertySearchForm" action="<?= base_url('buscar-propiedades') ?>" method="GET" class="bg-white p-4 rounded">
                <input type="hidden" id="transaction_type" name="transaction_type" value="venta">

                <div class="row g-3">
                    <!-- Property Type -->
                    <div class="col-md-3">
                        <select name="property_type_id" class="form-select">
                            <option value="">Todos los Inmuebles</option>
                            <?php foreach ($property_types as $type): ?>
                                <option value="<?= $type['id'] ?>"><?= esc($type['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Location -->
                    <div class="col-md-3">
                        <select name="location_id" class="form-select">
                            <option value="">¿ En Donde la Buscas ?</option>
                            <?php foreach ($locations as $location): ?>
                                <option value="<?= $location['id'] ?>">
                                    <?= esc($location['name']) ?>, <?= esc($location['district']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <!-- Price Range -->
                    <div class="col-md-2">
                        <input type="number" name="price_min" class="form-control" placeholder="Mínimo">
                    </div>
                    <div class="col-md-2">
                        <input type="number" name="price_max" class="form-control" placeholder="Máximo">
                    </div>

                    <!-- Currency -->
                    <div class="col-md-2">
                        <select name="currency" class="form-select">
                            <option value="pen">Soles</option>
                            <option value="usd">USD</option>
                        </select>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <button type="button" class="btn btn-danger w-100" id="searchNearMeBtn">
                            <i class="fas fa-map-marker-alt me-2"></i>
                            BUSCAR ALREDEDOR MÍO
                        </button>
                    </div>
                    <div class="col-md-6">
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="fas fa-search me-2"></i>
                            BUSCAR
                        </button>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12 text-center">
                        <a href="#" class="text-dark" data-bs-toggle="collapse" data-bs-target="#moreFilters">
                            MÁS FILTROS <i class="fas fa-chevron-down"></i>
                        </a>
                    </div>
                </div>

                <!-- More Filters (Collapsed) -->
                <div class="collapse mt-3" id="moreFilters">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <select name="bedrooms" class="form-select">
                                <option value="">Habitaciones</option>
                                <option value="1">1+</option>
                                <option value="2">2+</option>
                                <option value="3">3+</option>
                                <option value="4">4+</option>
                                <option value="5">5+</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="bathrooms" class="form-select">
                                <option value="">Baños</option>
                                <option value="1">1+</option>
                                <option value="2">2+</option>
                                <option value="3">3+</option>
                                <option value="4">4+</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="parking" class="form-select">
                                <option value="">Estacionamientos</option>
                                <option value="1">1+</option>
                                <option value="2">2+</option>
                                <option value="3">3+</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="search" class="form-control" placeholder="Palabra clave">
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Featured Properties Buttons -->
<section class="featured-buttons py-3 bg-light">
    <div class="container text-center">
        <button class="btn btn-danger me-3">PROPIEDADES RECIENTES</button>
        <button class="btn btn-outline-danger">BAJARON DE PRECIO</button>
    </div>
</section>

<!-- Latest Properties -->
<section class="latest-properties py-5">
    <div class="container">
        <h2 class="text-center mb-5 text-secondary">ÚLTIMAS PROPIEDADES</h2>

        <div class="row">
            <?php foreach ($featured_properties as $property): ?>
                <div class="col-lg-3 col-md-6 mb-4">
                    <div class="property-card h-100">
                        <div class="property-image position-relative">
                            <?php if ($property['main_image']): ?>
                                <img src="<?= base_url('assets/images/properties/' . $property['main_image']['image_url']) ?>"
                                     alt="<?= esc($property['title']) ?>" class="w-100 h-100 object-cover">
                            <?php else: ?>
                                <img src="<?= base_url('assets/images/property-placeholder.jpg') ?>"
                                     alt="<?= esc($property['title']) ?>" class="w-100 h-100 object-cover">
                            <?php endif; ?>

                            <!-- Property ID Badge -->
                            <div class="property-id">
                                ID: <?= $property['property_code'] ?>
                            </div>

                            <!-- Transaction Type Badge -->
                            <div class="transaction-type">
                                <?= strtoupper($property['transaction_type']) ?>
                            </div>
                        </div>

                        <div class="property-content p-3">
                            <!-- Price -->
                            <div class="property-price mb-2">
                                <span class="price-pen">S/. <?= number_format($property['price_pen'], 2) ?></span>
                                <?php if ($property['price_usd']): ?>
                                    <span class="price-usd">USD <?= number_format($property['price_usd'], 2) ?></span>
                                <?php endif; ?>
                            </div>

                            <!-- Location -->
                            <div class="property-location mb-2">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                <?= esc($property['location_name']) ?>, <?= esc($property['district']) ?>
                            </div>

                            <!-- Company -->
                            <div class="property-company text-muted small mb-3">
                                RUC: 20020240665<br>
                                Realty Group S.A.C.
                            </div>

                            <!-- Property Details -->
                            <div class="property-details d-flex justify-content-between mb-3">
                                <?php if ($property['land_area']): ?>
                                    <div class="detail-item">
                                        <i class="fas fa-expand-arrows-alt"></i>
                                        <span>Área Terreno: <?= number_format($property['land_area']) ?> m²</span>
                                    </div>
                                <?php endif; ?>

                                <?php if ($property['built_area']): ?>
                                    <div class="detail-item">
                                        <i class="fas fa-home"></i>
                                        <span>Área Construida: <?= number_format($property['built_area']) ?> m²</span>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <!-- Agent Info -->
                            <div class="agent-info d-flex align-items-center">
                                <div class="agent-photo me-2">
                                    <?php if ($property['agent_photo']): ?>
                                        <img src="<?= base_url('assets/images/agents/' . $property['agent_photo']) ?>"
                                             alt="<?= esc($property['agent_first_name']) ?>"
                                             class="rounded-circle" width="40" height="40">
                                    <?php else: ?>
                                        <img src="<?= base_url('assets/images/agent-placeholder.jpg') ?>"
                                             alt="Agente" class="rounded-circle" width="40" height="40">
                                    <?php endif; ?>
                                </div>
                                <div class="agent-details">
                                    <div class="agent-name"><?= esc($property['agent_first_name'] . ' ' . $property['agent_last_name']) ?></div>
                                </div>
                            </div>
                        </div>

                        <!-- Property Link -->
                        <a href="<?= base_url('propiedad/' . $property['property_code']) ?>" class="stretched-link"></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="text-center mt-4">
            <a href="<?= base_url('buscar-propiedades') ?>" class="btn btn-danger btn-lg">
                Ver Todas las Propiedades
            </a>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="stats-section py-5 bg-primary text-white">
    <div class="container">
        <div class="row text-center">
            <div class="col-md-3 mb-3">
                <h3 class="display-6 fw-bold"><?= number_format($stats['total_properties']) ?></h3>
                <p>Propiedades Disponibles</p>
            </div>
            <div class="col-md-3 mb-3">
                <h3 class="display-6 fw-bold"><?= number_format($stats['properties_for_sale']) ?></h3>
                <p>En Venta</p>
            </div>
            <div class="col-md-3 mb-3">
                <h3 class="display-6 fw-bold"><?= number_format($stats['properties_for_rent']) ?></h3>
                <p>En Alquiler</p>
            </div>
            <div class="col-md-3 mb-3">
                <h3 class="display-6 fw-bold"><?= number_format($stats['total_agents']) ?></h3>
                <p>Asesores Especialistas</p>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
// Transaction type buttons
document.querySelectorAll('.transaction-buttons button').forEach(button => {
    button.addEventListener('click', function() {
        // Remove active class from all buttons
        document.querySelectorAll('.transaction-buttons button').forEach(btn => btn.classList.remove('active'));

        // Add active class to clicked button
        this.classList.add('active');

        // Update hidden input
        document.getElementById('transaction_type').value = this.dataset.type;
    });
});

// Search near me functionality
document.getElementById('searchNearMeBtn').addEventListener('click', function() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            // Here you would implement location-based search
            alert('Funcionalidad de búsqueda por ubicación en desarrollo');
        });
    } else {
        alert('La geolocalización no está soportada en este navegador');
    }
});
</script>
<?= $this->endSection() ?>

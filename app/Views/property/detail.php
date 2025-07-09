<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid px-0">
    <!-- Property Image Gallery -->
    <div class="property-gallery position-relative">
        <?php if (!empty($images)): ?>
            <div id="propertyCarousel" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php foreach ($images as $index => $image): ?>
                        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                            <img src="<?= base_url('assets/images/properties/' . $image['image_url']) ?>"
                                 class="d-block w-100 property-hero-image"
                                 alt="<?= esc($image['alt_text'] ?: $property['title']) ?>">
                        </div>
                    <?php endforeach; ?>
                </div>

                <?php if (count($images) > 1): ?>
                    <button class="carousel-control-prev" type="button" data-bs-target="#propertyCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#propertyCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                    </button>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <img src="<?= base_url('assets/images/property-placeholder.jpg') ?>"
                 class="w-100 property-hero-image" alt="<?= esc($property['title']) ?>">
        <?php endif; ?>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <!-- Property Details Column -->
        <div class="col-lg-8">
            <!-- Property Header -->
            <div class="property-header mb-4">
                <div class="d-flex flex-wrap align-items-center mb-3">
                    <span class="badge bg-primary me-2 py-2 px-3">
                        <?= strtoupper(str_replace('_', ' ', $property['transaction_type'])) ?> EN <?= strtoupper($property['property_type_name']) ?>
                    </span>
                    <span class="badge bg-danger py-2 px-3">
                        ID: <?= $property['property_code'] ?>
                    </span>
                </div>

                <div class="location mb-2">
                    <i class="fas fa-map-marker-alt text-muted me-2"></i>
                    <?= esc($property['location_name']) ?>, <?= esc($property['district']) ?>, <?= esc($property['province']) ?>, <?= esc($property['department']) ?>
                </div>

                <h1 class="property-title h3 text-primary">
                    <?= esc($property['title']) ?>
                </h1>

                <div class="property-price h4 mb-3">
                    <span class="text-danger fw-bold">S/. <?= number_format($property['price_pen'], 2) ?></span>
                    <?php if ($property['price_usd']): ?>
                        <span class="text-muted"> - USD <?= number_format($property['price_usd'], 2) ?></span>
                    <?php endif; ?>
                </div>

                <div class="publication-date text-muted small">
                    Publicada el: <?= date('d \d\e F \d\e Y \a \l\a\s H:i', strtotime($property['published_at'] ?: $property['created_at'])) ?>
                </div>
            </div>

            <!-- Property Description -->
            <?php if ($property['description']): ?>
                <div class="property-section mb-4">
                    <h3 class="section-title" data-bs-toggle="collapse" data-bs-target="#description" aria-expanded="true">
                        <i class="fas fa-file-alt me-2"></i>
                        DESCRIPCIÓN
                        <i class="fas fa-chevron-down float-end"></i>
                    </h3>
                    <div class="collapse show" id="description">
                        <div class="section-content p-3 bg-light">
                            <?= nl2br(esc($property['description'])) ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Property Measurements -->
            <div class="property-section mb-4">
                <h3 class="section-title" data-bs-toggle="collapse" data-bs-target="#measurements" aria-expanded="false">
                    <i class="fas fa-ruler me-2"></i>
                    MEDIDAS
                    <i class="fas fa-chevron-down float-end"></i>
                </h3>
                <div class="collapse" id="measurements">
                    <div class="section-content p-3 bg-light">
                        <div class="row">
                            <?php if ($property['land_area']): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="measurement-item text-center">
                                        <div class="measurement-icon">
                                            <i class="fas fa-expand-arrows-alt fa-2x text-muted"></i>
                                        </div>
                                        <h5>Área Terreno</h5>
                                        <p class="mb-0"><?= number_format($property['land_area'], 2) ?> m²</p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($property['built_area']): ?>
                                <div class="col-md-4 mb-3">
                                    <div class="measurement-item text-center">
                                        <div class="measurement-icon">
                                            <i class="fas fa-home fa-2x text-muted"></i>
                                        </div>
                                        <h5>Área Construida</h5>
                                        <p class="mb-0"><?= number_format($property['built_area'], 2) ?> m²</p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="col-md-4 mb-3">
                                <div class="measurement-item text-center">
                                    <div class="measurement-icon">
                                        <i class="fas fa-calculator fa-2x text-muted"></i>
                                    </div>
                                    <h5>Medidas</h5>
                                    <p class="mb-0">0 x 0</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Property Characteristics -->
            <?php if (!empty($features)): ?>
                <div class="property-section mb-4">
                    <h3 class="section-title" data-bs-toggle="collapse" data-bs-target="#characteristics" aria-expanded="false">
                        <i class="fas fa-list me-2"></i>
                        CARACTERÍSTICAS
                        <i class="fas fa-chevron-down float-end"></i>
                    </h3>
                    <div class="collapse" id="characteristics">
                        <div class="section-content p-3 bg-light">
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <div class="characteristic-item text-center">
                                        <i class="fas fa-calendar-alt fa-2x text-muted"></i>
                                        <h6>Antigüedad</h6>
                                        <p><?= $property['age_years'] ?: 0 ?> Años</p>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="characteristic-item text-center">
                                        <i class="fas fa-building fa-2x text-muted"></i>
                                        <h6>Nº de Pisos</h6>
                                        <p><?= $property['floors'] ?: 1 ?></p>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="characteristic-item text-center">
                                        <i class="fas fa-bed fa-2x text-muted"></i>
                                        <h6>Habitaciones</h6>
                                        <p><?= $property['bedrooms'] ?: 0 ?></p>
                                    </div>
                                </div>

                                <div class="col-md-3 mb-3">
                                    <div class="characteristic-item text-center">
                                        <i class="fas fa-bath fa-2x text-muted"></i>
                                        <h6>Baños</h6>
                                        <p><?= $property['bathrooms'] ?: 0 ?></p>
                                    </div>
                                </div>
                            </div>

                            <!-- Additional Features -->
                            <?php foreach ($features as $category => $categoryFeatures): ?>
                                <h6 class="mt-4 mb-3 text-capitalize"><?= esc($category) ?></h6>
                                <div class="row">
                                    <?php foreach ($categoryFeatures as $feature): ?>
                                        <div class="col-md-6 mb-2">
                                            <div class="feature-item">
                                                <i class="fas fa-check-circle text-success me-2"></i>
                                                <?= esc($feature['name']) ?>
                                                <?php if ($feature['value']): ?>
                                                    <span class="text-muted">(<?= esc($feature['value']) ?>)</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Property Map -->
            <?php if ($property['latitude'] && $property['longitude']): ?>
                <div class="property-section mb-4">
                    <h3 class="section-title mb-3">
                        <i class="fas fa-map-marker-alt me-2"></i>
                        UBICACIÓN
                    </h3>
                    <div class="property-map">
                        <div id="propertyMap" style="height: 400px; width: 100%;"></div>
                    </div>
                </div>
            <?php endif; ?>
        </div>

        <!-- Agent Sidebar -->
        <div class="col-lg-4">
            <div class="agent-card sticky-top">
                <div class="card">
                    <div class="card-header bg-primary text-white text-center">
                        <h5 class="mb-0"><?= !empty($property['agents']) && count($property['agents']) > 1 ? 'Contacta a los Asesores' : 'Contacta al Asesor' ?></h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($property['agents'])): ?>
                            <?php foreach ($property['agents'] as $index => $agent): ?>
                                <div class="agent-info <?= $index > 0 ? 'border-top pt-3 mt-3' : '' ?>">
                                    <div class="text-center">
                                        <div class="agent-photo mb-3">
                                            <?php if ($agent['photo']): ?>
                                                <img src="<?= base_url('assets/images/agents/' . $agent['photo']) ?>"
                                                     alt="<?= esc($agent['first_name']) ?>"
                                                     class="rounded-circle" width="<?= $index === 0 ? '100' : '80' ?>" height="<?= $index === 0 ? '100' : '80' ?>">
                                            <?php else: ?>
                                                <img src="<?= base_url('assets/images/agent-placeholder.jpg') ?>"
                                                     alt="Agente" class="rounded-circle" width="<?= $index === 0 ? '100' : '80' ?>" height="<?= $index === 0 ? '100' : '80' ?>">
                                            <?php endif; ?>
                                        </div>

                                        <h<?= $index === 0 ? '5' : '6' ?> class="agent-name"><?= esc($agent['first_name'] . ' ' . $agent['last_name']) ?></h<?= $index === 0 ? '5' : '6' ?>>

                                        <!-- Agent Role Badge -->
                                        <div class="mb-2">
                                            <?php if ($agent['is_primary']): ?>
                                                <span class="badge bg-primary">Agente Principal</span>
                                            <?php else: ?>
                                                <span class="badge bg-secondary"><?= ucfirst(esc($agent['role'])) ?></span>
                                            <?php endif; ?>
                                        </div>

                                        <p class="agent-email text-muted <?= $index === 0 ? '' : 'small' ?>"><?= esc($agent['email']) ?></p>
                                        <p class="agent-company <?= $index === 0 ? '' : 'small' ?>"><?= esc($agent['company']) ?></p>
                                        <p class="agent-phone <?= $index === 0 ? '' : 'small' ?>">Celular: <?= esc($agent['cell_phone']) ?></p>

                                        <!-- Contact Buttons -->
                                        <div class="contact-buttons mb-3">
                                            <a href="tel:<?= $agent['cell_phone'] ?>" class="btn btn-outline-primary btn-sm me-1">
                                                <i class="fas fa-phone"></i>
                                            </a>
                                            <a href="https://wa.me/51<?= preg_replace('/[^0-9]/', '', $agent['cell_phone']) ?>"
                                               class="btn btn-outline-success btn-sm me-1" target="_blank">
                                                <i class="fab fa-whatsapp"></i>
                                            </a>
                                            <a href="mailto:<?= $agent['email'] ?>" class="btn btn-outline-danger btn-sm">
                                                <i class="fas fa-envelope"></i>
                                            </a>
                                        </div>

                                        <?php if ($index === 0): ?>
                                            <a href="<?= base_url('asesor/' . $agent['agent_id']) ?>" class="btn btn-primary w-100 mb-3">
                                                PROPIEDADES DEL ASESOR
                                            </a>
                                        <?php else: ?>
                                            <a href="<?= base_url('asesor/' . $agent['agent_id']) ?>" class="btn btn-outline-primary btn-sm w-100 mb-2">
                                                Ver Perfil
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <!-- Fallback para compatibilidad -->
                            <div class="text-center">
                                <div class="agent-photo mb-3">
                                    <?php if (isset($property['agent_photo']) && $property['agent_photo']): ?>
                                        <img src="<?= base_url('assets/images/agents/' . $property['agent_photo']) ?>"
                                             alt="<?= esc($property['agent_first_name']) ?>"
                                             class="rounded-circle" width="100" height="100">
                                    <?php else: ?>
                                        <img src="<?= base_url('assets/images/agent-placeholder.jpg') ?>"
                                             alt="Agente" class="rounded-circle" width="100" height="100">
                                    <?php endif; ?>
                                </div>

                                <?php if (isset($property['agent_first_name'])): ?>
                                    <h5 class="agent-name"><?= esc($property['agent_first_name'] . ' ' . $property['agent_last_name']) ?></h5>
                                    <p class="agent-email text-muted"><?= esc($property['agent_email']) ?></p>
                                    <p class="agent-company"><?= esc($property['agent_company']) ?></p>
                                    <p class="agent-phone">Celular: <?= esc($property['agent_cell_phone']) ?></p>

                                    <!-- Contact Buttons -->
                                    <div class="contact-buttons mb-3">
                                        <a href="tel:<?= $property['agent_cell_phone'] ?>" class="btn btn-outline-primary btn-sm me-2">
                                            <i class="fas fa-phone"></i>
                                        </a>
                                        <a href="https://wa.me/51<?= preg_replace('/[^0-9]/', '', $property['agent_cell_phone']) ?>"
                                           class="btn btn-outline-success btn-sm me-2" target="_blank">
                                            <i class="fab fa-whatsapp"></i>
                                        </a>
                                        <a href="mailto:<?= $property['agent_email'] ?>" class="btn btn-outline-danger btn-sm">
                                            <i class="fas fa-envelope"></i>
                                        </a>
                                    </div>

                                    <a href="<?= base_url('asesor/' . $property['agent_id']) ?>" class="btn btn-primary w-100 mb-3">
                                        PROPIEDADES DEL ASESOR
                                    </a>
                                <?php else: ?>
                                    <p class="text-muted">Información de agente no disponible</p>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Contact Form -->
                        <form id="inquiryForm" class="mt-4">
                            <input type="hidden" name="property_id" value="<?= $property['id'] ?>">

                            <?php if (!empty($property['agents']) && count($property['agents']) > 1): ?>
                                <div class="mb-3">
                                    <label for="agent_id" class="form-label">Contactar a:</label>
                                    <select name="agent_id" class="form-select" required>
                                        <option value="">Seleccionar asesor</option>
                                        <?php foreach ($property['agents'] as $agent): ?>
                                            <option value="<?= $agent['agent_id'] ?>" <?= $agent['is_primary'] ? 'selected' : '' ?>>
                                                <?= esc($agent['first_name'] . ' ' . $agent['last_name']) ?>
                                                <?= $agent['is_primary'] ? ' (Principal)' : ' (' . ucfirst($agent['role']) . ')' ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            <?php else: ?>
                                <input type="hidden" name="agent_id" value="<?= $property['agent_id'] ?? ($property['agents'][0]['agent_id'] ?? '') ?>">
                            <?php endif; ?>

                            <div class="mb-3">
                                <input type="text" name="name" class="form-control" placeholder="Nombre completo" required>
                            </div>
                            <div class="mb-3">
                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                            </div>
                            <div class="mb-3">
                                <input type="tel" name="phone" class="form-control" placeholder="Teléfono">
                            </div>
                            <div class="mb-3">
                                <select name="inquiry_type" class="form-select">
                                    <option value="info">Solicitar información</option>
                                    <option value="visit">Agendar visita</option>
                                    <option value="call">Solicitar llamada</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <textarea name="message" class="form-control" rows="3"
                                         placeholder="Mensaje (opcional)"></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100">
                                ENVIAR CONSULTA
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Disclaimer -->
                <div class="disclaimer mt-3 small text-muted">
                    <p>* Cada oficina es de propiedad y operación independiente.</p>
                    <p>* Las fotos, precios y descripción de esta propiedad son referenciales.</p>
                </div>

                <!-- Share Section -->
                <div class="share-section mt-3 text-center">
                    <h6>COMPARTIR EN</h6>
                    <div class="share-buttons">
                        <a href="#" class="btn btn-outline-primary btn-sm me-2" onclick="shareOnFacebook()">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm me-2" onclick="shareOnLinkedIn()">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <a href="#" class="btn btn-outline-success btn-sm me-2" onclick="shareOnWhatsApp()">
                            <i class="fab fa-whatsapp"></i>
                        </a>
                        <a href="#" class="btn btn-outline-primary btn-sm me-2" onclick="shareOnTwitter()">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="btn btn-outline-info btn-sm" onclick="shareOnTelegram()">
                            <i class="fab fa-telegram"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related Properties -->
    <?php if (!empty($related_properties)): ?>
        <div class="related-properties mt-5">
            <h3 class="text-center mb-4">Propiedades Relacionadas</h3>
            <div class="row">
                <?php foreach ($related_properties as $relatedProperty): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="card property-card">
                            <div class="property-image">
                                <?php if ($relatedProperty['main_image']): ?>
                                    <img src="<?= base_url('assets/images/properties/' . $relatedProperty['main_image']['image_url']) ?>"
                                         class="card-img-top" alt="<?= esc($relatedProperty['title']) ?>">
                                <?php else: ?>
                                    <img src="<?= base_url('assets/images/property-placeholder.jpg') ?>"
                                         class="card-img-top" alt="<?= esc($relatedProperty['title']) ?>">
                                <?php endif; ?>
                            </div>
                            <div class="card-body">
                                <h6 class="card-title"><?= esc($relatedProperty['title']) ?></h6>
                                <p class="text-muted small">
                                    <i class="fas fa-map-marker-alt me-1"></i>
                                    <?= esc($relatedProperty['location_name']) ?>
                                </p>
                                <p class="price text-danger fw-bold">
                                    S/. <?= number_format($relatedProperty['price_pen'], 2) ?>
                                </p>
                                <a href="<?= base_url('propiedad/' . $relatedProperty['property_code']) ?>"
                                   class="btn btn-outline-primary btn-sm">Ver Detalles</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key=YOUR_GOOGLE_MAPS_API_KEY&callback=initMap" async defer></script>

<script>
// Initialize Google Map
function initMap() {
    <?php if ($property['latitude'] && $property['longitude']): ?>
    const propertyLocation = {
        lat: <?= $property['latitude'] ?>,
        lng: <?= $property['longitude'] ?>
    };

    const map = new google.maps.Map(document.getElementById("propertyMap"), {
        zoom: 15,
        center: propertyLocation,
    });

    const marker = new google.maps.Marker({
        position: propertyLocation,
        map: map,
        title: "<?= esc($property['title']) ?>",
        icon: '<?= base_url("assets/images/remax-marker.png") ?>'
    });

    const infoWindow = new google.maps.InfoWindow({
        content: `
            <div>
                <h6><?= esc($property['title']) ?></h6>
                <p class="mb-1"><?= esc($property['location_name']) ?></p>
                <p class="text-danger fw-bold mb-0">S/. <?= number_format($property['price_pen'], 2) ?></p>
            </div>
        `
    });

    marker.addListener("click", () => {
        infoWindow.open(map, marker);
    });
    <?php endif; ?>
}

// Contact form submission
document.getElementById('inquiryForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);

    fetch('<?= base_url("property/inquiry") ?>', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            this.reset();
        } else {
            alert(data.message || 'Error al enviar la consulta');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al enviar la consulta');
    });
});

// Share functions
function shareOnFacebook() {
    const url = encodeURIComponent(window.location.href);
    window.open(`https://www.facebook.com/sharer/sharer.php?u=${url}`, '_blank');
}

function shareOnLinkedIn() {
    const url = encodeURIComponent(window.location.href);
    const title = encodeURIComponent('<?= esc($property["title"]) ?>');
    window.open(`https://www.linkedin.com/sharing/share-offsite/?url=${url}&title=${title}`, '_blank');
}

function shareOnWhatsApp() {
    const text = encodeURIComponent('<?= esc($property["title"]) ?> - <?= site_url("propiedad/" . $property["property_code"]) ?>');
    window.open(`https://wa.me/?text=${text}`, '_blank');
}

function shareOnTwitter() {
    const text = encodeURIComponent('<?= esc($property["title"]) ?>');
    const url = encodeURIComponent(window.location.href);
    window.open(`https://twitter.com/intent/tweet?text=${text}&url=${url}`, '_blank');
}

function shareOnTelegram() {
    const text = encodeURIComponent('<?= esc($property["title"]) ?> - <?= site_url("propiedad/" . $property["property_code"]) ?>');
    window.open(`https://t.me/share/url?url=${text}`, '_blank');
}
</script>
<?= $this->endSection() ?>

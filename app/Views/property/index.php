<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container mt-5">
    <h1 class="mb-4">Buscar Propiedades</h1>

    <div class="card mb-4">
        <div class="card-header">Filtros de Búsqueda</div>
        <div class="card-body">
            <form action="/buscar-propiedades" method="get">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="search">Palabra Clave</label>
                        <input type="text" class="form-control" id="search" name="search" value="<?= esc($filters['search'] ?? '') ?>" placeholder="Ej: Casa con jardín">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="transaction_type">Tipo de Transacción</label>
                        <select class="form-control" id="transaction_type" name="transaction_type">
                            <option value="">Cualquiera</option>
                            <option value="venta" <?= (isset($filters['transaction_type']) && $filters['transaction_type'] === 'venta') ? 'selected' : '' ?>>Venta</option>
                            <option value="alquiler" <?= (isset($filters['transaction_type']) && $filters['transaction_type'] === 'alquiler') ? 'selected' : '' ?>>Alquiler</option>
                            <option value="anticresis" <?= (isset($filters['transaction_type']) && $filters['transaction_type'] === 'anticresis') ? 'selected' : '' ?>>Anticresis</option>
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="property_type_id">Tipo de Propiedad</label>
                        <select class="form-control" id="property_type_id" name="property_type_id">
                            <option value="">Cualquiera</option>
                            <?php foreach ($property_types as $type) : ?>
                                <option value="<?= esc($type['id']) ?>" <?= (isset($filters['property_type_id']) && (int)$filters['property_type_id'] === (int)$type['id']) ? 'selected' : '' ?>><?= esc($type['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="location_id">Ubicación</label>
                        <select class="form-control" id="location_id" name="location_id">
                            <option value="">Cualquiera</option>
                            <?php foreach ($locations as $location) : ?>
                                <option value="<?= esc($location['id']) ?>" <?= (isset($filters['location_id']) && (int)$filters['location_id'] === (int)$location['id']) ? 'selected' : '' ?>><?= esc($location['name']) ?>, <?= esc($location['district']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="price_min">Precio Mín.</label>
                        <input type="number" class="form-control" id="price_min" name="price_min" value="<?= esc($filters['price_min'] ?? '') ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="price_max">Precio Máx.</label>
                        <input type="number" class="form-control" id="price_max" name="price_max" value="<?= esc($filters['price_max'] ?? '') ?>">
                    </div>
                    <div class="col-md-2 mb-3">
                        <label for="currency">Moneda</label>
                        <select class="form-control" id="currency" name="currency">
                            <option value="pen" <?= (isset($filters['currency']) && $filters['currency'] === 'pen') ? 'selected' : '' ?>>PEN</option>
                            <option value="usd" <?= (isset($filters['currency']) && $filters['currency'] === 'usd') ? 'selected' : '' ?>>USD</option>
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="bedrooms">Dormitorios</label>
                        <input type="number" class="form-control" id="bedrooms" name="bedrooms" value="<?= esc($filters['bedrooms'] ?? '') ?>">
                    </div>
                    <div class="col-md-3 mb-3">
                        <label for="bathrooms">Baños</label>
                        <input type="number" class="form-control" id="bathrooms" name="bathrooms" value="<?= esc($filters['bathrooms'] ?? '') ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Aplicar Filtros</button>
                <a href="/buscar-propiedades" class="btn btn-secondary">Limpiar Filtros</a>
            </form>
        </div>
    </div>

    <?php if (empty($properties)) : ?>
        <div class="alert alert-info" role="alert">
            No se encontraron propiedades que coincidan con tu búsqueda.
        </div>
    <?php else : ?>
        <div class="row">
            <?php foreach ($properties as $property) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100 shadow-sm">
                        <?php if (isset($property['main_image'])) : ?>
                            <img src="<?= base_url('public/uploads/properties/' . esc($property['main_image']['image_url'])) ?>" class="card-img-top" alt="<?= esc($property['title']) ?>">
                        <?php else : ?>
                            <img src="<?= base_url('public/assets/img/no-image.jpg') ?>" class="card-img-top" alt="No Image">
                        <?php endif; ?>
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?= esc($property['title']) ?></h5>
                            <p class="card-text text-muted"><?= esc($property['address']) ?></p>
                            <p class="card-text"><strong><?= esc(strtoupper($property['transaction_type'])) ?>: </strong>
                                <?php if (isset($filters['currency']) && $property['currency'] === 'usd') : ?>
                                    $<?= number_format($property['price_usd'], 2) ?> USD
                                <?php else : ?>
                                    S/<?= number_format($property['price_pen'], 2) ?> PEN
                                <?php endif; ?>
                            </p>
                            <ul class="list-unstyled">
                                <li><i class="fas fa-bed"></i> <?= esc($property['bedrooms']) ?> Dorm.</li>
                                <li><i class="fas fa-bath"></i> <?= esc($property['bathrooms']) ?> Baños</li>
                                <li><i class="fas fa-ruler-combined"></i> <?= esc($property['built_area']) ?> m²</li>
                            </ul>
                            <a href="/propiedad/<?= esc($property['property_code']) ?>" class="btn btn-primary mt-auto">Ver Detalles</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?= $this->include('partials/_pagination') ?>

    <?php endif; ?>
</div>
<?= $this->endSection() ?>

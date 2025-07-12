<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nueva Característica</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Crear Característica</h6>
        </div>
        <div class="card-body">
            <?php if (session()->has('errors')) : ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach (session('errors') as $error) : ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="/admin/caracteristicas/create" method="post">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" required>
                </div>

                <div class="form-group">
                    <label for="category">Categoría</label>
                    <input type="text" class="form-control" id="category" name="category" value="<?= old('category') ?>">
                </div>

                <div class="form-group">
                    <label for="icon">Icono (Font Awesome)</label>
                    <input type="text" class="form-control" id="icon" name="icon" value="<?= old('icon') ?>">
                    <small class="form-text text-muted">Ej: 'water', 'wifi', 'car'. Encuentra más en <a href="https://fontawesome.com/icons" target="_blank">Font Awesome</a>.</small>
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="/admin/caracteristicas" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

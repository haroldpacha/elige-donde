<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Nueva Ubicación</h1>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Crear Ubicación</h6>
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

            <form action="/admin/ubicaciones/create" method="post">
                <?= csrf_field() ?>

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?= old('name') ?>" required>
                </div>

                <div class="form-group">
                    <label for="department">Departamento</label>
                    <input type="text" class="form-control" id="department" name="department" value="<?= old('department') ?>" required>
                </div>

                <div class="form-group">
                    <label for="province">Provincia</label>
                    <input type="text" class="form-control" id="province" name="province" value="<?= old('province') ?>" required>
                </div>

                <div class="form-group">
                    <label for="district">Distrito</label>
                    <input type="text" class="form-control" id="district" name="district" value="<?= old('district') ?>" required>
                </div>

                <button type="submit" class="btn btn-primary">Guardar</button>
                <a href="/admin/ubicaciones" class="btn btn-secondary">Cancelar</a>
            </form>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

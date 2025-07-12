<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestionar Tipos de Propiedades</h1>
        <a href="/admin/tipos-propiedades/new" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm"><i class="fas fa-plus fa-sm text-white-50"></i> Nuevo Tipo</a>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Lista de Tipos de Propiedades</h6>
        </div>
        <div class="card-body">
            <?php if (session()->has('message')) : ?>
                <div class="alert alert-success">
                    <?= session('message') ?>
                </div>
            <?php endif; ?>
            <?php if (session()->has('error')) : ?>
                <div class="alert alert-danger">
                    <?= session('error') ?>
                </div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Slug</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($types as $type) : ?>
                            <tr>
                                <td><?= $type['id'] ?></td>
                                <td><?= esc($type['name']) ?></td>
                                <td><?= esc($type['slug']) ?></td>
                                <td>
                                    <a href="/admin/tipos-propiedades/<?= $type['id'] ?>/edit" class="btn btn-sm btn-warning">Editar</a>
                                    <form action="/admin/tipos-propiedades/delete/<?= $type['id'] ?>" method="post" style="display: inline-block;">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Estás seguro de que quieres eliminar este tipo de propiedad?');">Eliminar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>
<div class="container">
    <h1>Contacto</h1>
    <p>Ponte en contacto con nosotros.</p>

    <form action="/contacto" method="post">
        <?= csrf_field() ?>
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="message">Mensaje</label>
            <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
    </form>
</div>
<?= $this->endSection() ?>

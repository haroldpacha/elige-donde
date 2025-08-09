<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Configuración General</h1>
    </div>

    <form action="<?= base_url('admin/configuracion/guardar') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">Datos de la Empresa y Redes Sociales</h6>
                <button type="submit" class="btn btn-primary btn-sm">Guardar Cambios</button>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('success')): ?>
                    <div class="alert alert-success">
                        <?= session()->getFlashdata('success') ?>
                    </div>
                <?php endif; ?>

                <div class="row">
                    <!-- Columna de Información de la Empresa -->
                    <div class="col-lg-6">
                        <h5>Información de la Empresa</h5>
                        <hr>
                        <div class="form-group">
                            <label for="company_name">Nombre de la Empresa</label>
                            <input type="text" class="form-control" id="company_name" name="company_name" value="<?= esc($settings['company_name'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="company_phone">Teléfono</label>
                            <input type="text" class="form-control" id="company_phone" name="company_phone" value="<?= esc($settings['company_phone'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="company_email">Correo Electrónico</label>
                            <input type="email" class="form-control" id="company_email" name="company_email" value="<?= esc($settings['company_email'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="company_address">Dirección</label>
                            <textarea class="form-control" id="company_address" name="company_address" rows="3"><?= esc($settings['company_address'] ?? '') ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="company_logo">Logo de la Empresa</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="company_logo" name="company_logo" onchange="previewLogo(this);">
                                <label class="custom-file-label" for="company_logo">Elegir archivo...</label>
                            </div>
                        </div>
                        <div class="form-group text-center">
                            <label>Vista Previa del Logo</label>
                            <div>
                                <img id="logo-preview" src="<?= !empty($settings['company_logo']) ? base_url('uploads/settings/' . esc($settings['company_logo'])) : 'https://via.placeholder.com/150x80?text=Logo' ?>" alt="Vista previa del logo" class="img-thumbnail" style="max-height: 100px;">
                            </div>
                        </div>
                    </div>

                    <!-- Columna de Redes Sociales -->
                    <div class="col-lg-6">
                        <h5>Redes Sociales</h5>
                        <hr>
                        <div class="form-group">
                            <label for="social_facebook">Facebook URL</label>
                            <input type="url" class="form-control" id="social_facebook" name="social_facebook" value="<?= esc($settings['social_facebook'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="social_twitter">X (Twitter) URL</label>
                            <input type="url" class="form-control" id="social_twitter" name="social_twitter" value="<?= esc($settings['social_twitter'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="social_instagram">Instagram URL</label>
                            <input type="url" class="form-control" id="social_instagram" name="social_instagram" value="<?= esc($settings['social_instagram'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="social_youtube">YouTube URL</label>
                            <input type="url" class="form-control" id="social_youtube" name="social_youtube" value="<?= esc($settings['social_youtube'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="social_tiktok">TikTok URL</label>
                            <input type="url" class="form-control" id="social_tiktok" name="social_tiktok" value="<?= esc($settings['social_tiktok'] ?? '') ?>">
                        </div>
                        <div class="form-group">
                            <label for="social_whatsapp">Número de WhatsApp</label>
                            <input type="text" class="form-control" id="social_whatsapp" name="social_whatsapp" placeholder="Ej: 51987654321" value="<?= esc($settings['social_whatsapp'] ?? '') ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('logo-preview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
        // Actualizar el label del custom file input
        var fileName = input.files[0].name;
        $(input).next('.custom-file-label').html(fileName);
    }
}
</script>
<?= $this->endSection() ?>

<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Configuración General</h1>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Datos de la Empresa y Redes Sociales</h6>
                </div>
                <div class="card-body">
                    <?php if (session()->getFlashdata('success')): ?>
                        <div class="alert alert-success">
                            <?= session()->getFlashdata('success') ?>
                        </div>
                    <?php endif; ?>

                    <form action="<?= base_url('admin/configuracion/guardar') ?>" method="post" enctype="multipart/form-data">
                        <?= csrf_field() ?>

                        <h5 class="mt-4">Información de la Empresa</h5>
                        <hr>

                        <div class="form-group row">
                            <label for="company_name" class="col-sm-3 col-form-label">Nombre de la Empresa</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="company_name" name="company_name" value="<?= esc($settings['company_name'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="company_phone" class="col-sm-3 col-form-label">Teléfono</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="company_phone" name="company_phone" value="<?= esc($settings['company_phone'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="company_email" class="col-sm-3 col-form-label">Correo Electrónico</label>
                            <div class="col-sm-9">
                                <input type="email" class="form-control" id="company_email" name="company_email" value="<?= esc($settings['company_email'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="company_address" class="col-sm-3 col-form-label">Dirección</label>
                            <div class="col-sm-9">
                                <textarea class="form-control" id="company_address" name="company_address" rows="3"><?= esc($settings['company_address'] ?? '') ?></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="company_logo" class="col-sm-3 col-form-label">Logo de la Empresa</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control-file" id="company_logo" name="company_logo">
                                <?php if (!empty($settings['company_logo'])): ?>
                                    <div class="mt-2">
                                        <img src="<?= base_url('uploads/settings/' . esc($settings['company_logo'])) ?>" alt="Logo Actual" style="max-height: 80px;">
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <h5 class="mt-5">Redes Sociales</h5>
                        <hr>

                        <div class="form-group row">
                            <label for="social_facebook" class="col-sm-3 col-form-label">Facebook URL</label>
                            <div class="col-sm-9">
                                <input type="url" class="form-control" id="social_facebook" name="social_facebook" value="<?= esc($settings['social_facebook'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="social_twitter" class="col-sm-3 col-form-label">X (Twitter) URL</label>
                            <div class="col-sm-9">
                                <input type="url" class="form-control" id="social_twitter" name="social_twitter" value="<?= esc($settings['social_twitter'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="social_instagram" class="col-sm-3 col-form-label">Instagram URL</label>
                            <div class="col-sm-9">
                                <input type="url" class="form-control" id="social_instagram" name="social_instagram" value="<?= esc($settings['social_instagram'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="social_youtube" class="col-sm-3 col-form-label">YouTube URL</label>
                            <div class="col-sm-9">
                                <input type="url" class="form-control" id="social_youtube" name="social_youtube" value="<?= esc($settings['social_youtube'] ?? '') ?>">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="social_tiktok" class="col-sm-3 col-form-label">TikTok URL</label>
                            <div class="col-sm-9">
                                <input type="url" class="form-control" id="social_tiktok" name="social_tiktok" value="<?= esc($settings['social_tiktok'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="social_whatsapp" class="col-sm-3 col-form-label">Número de WhatsApp</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="social_whatsapp" name="social_whatsapp" placeholder="Ej: 51987654321" value="<?= esc($settings['social_whatsapp'] ?? '') ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

</div>
<?= $this->endSection() ?>

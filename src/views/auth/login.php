<?php $this->render('layouts/header', ['title' => $title]) ?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h3 class="text-center">Iniciar Sesión</h3>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="email">Correo electrónico</label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="<?= htmlspecialchars($email ?? '') ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Contraseña</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
                    </form>
                    
                    <div class="text-center mt-3">
                        <a href="/forgot-password">¿Olvidaste tu contraseña?</a>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-3">
                ¿No tienes cuenta? <a href="/register">Regístrate aquí</a>
            </div>
        </div>
    </div>
</div>

<?php $this->render('layouts/footer') ?>
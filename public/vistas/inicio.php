<?php include 'header.php'; ?>

<div class="container">

    <!-- Bienvenida -->
    <div class="row justify-content-center mb-4">
        <div class="col-lg-8">
            <div class="card shadow-sm border-0 rounded-4">
                <div class="card-body text-center py-5">
                    <div class="mb-3">
                        <i class="fas fa-user-circle fa-5x text-primary"></i>
                    </div>
                    <h2 class="fw-bold mb-1">¡Bienvenido, <span id="spanNombre"></span>!</h2>
                    <p class="text-muted mb-3">Has iniciado sesión como <span class="badge bg-primary fs-6" id="spanRol"></span></p>
                    <hr>
                    <div class="row text-start mt-3" id="datosUsuario">
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-user me-2 text-secondary"></i>
                            <strong>Usuario:</strong> <span id="spanUsuario"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-envelope me-2 text-secondary"></i>
                            <strong>Correo:</strong> <span id="spanCorreo"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-phone me-2 text-secondary"></i>
                            <strong>Teléfono:</strong> <span id="spanTelefono"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-map-marker-alt me-2 text-secondary"></i>
                            <strong>Ubicación:</strong> <span id="spanUbicacion"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-calendar me-2 text-secondary"></i>
                            <strong>Fecha nac.:</strong> <span id="spanFecha"></span>
                        </div>
                        <div class="col-md-6 mb-2">
                            <i class="fas fa-venus-mars me-2 text-secondary"></i>
                            <strong>Sexo:</strong> <span id="spanSexo"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tarjetas de acceso rápido según rol -->
    <div class="row justify-content-center" id="tarjetasAcceso"></div>

</div>

<?php include 'footer.php'; ?>
<script src="../js/usuarios/inicio.js"></script>
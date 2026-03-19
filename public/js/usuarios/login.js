// ==========================================
// JS: login.js
// AJAX login + contraseña temporal
// ==========================================

// ── LOGIN ─────────────────────────────────────────────────────────
function loginUsuario() {

    const usuario  = $('#login').val().trim();
    const password = $('#password').val().trim();

    // Validación cliente
    if (!usuario || !password) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos requeridos',
            text: 'Por favor ingresa tu usuario y contraseña.',
            confirmButtonColor: '#0d6efd'
        });
        return false;
    }

    // Deshabilitar botón
    const btn = $('input[type="submit"]');
    btn.val('Verificando...').prop('disabled', true);

    $.ajax({
        url: 'controller/UsuarioController.php',
        method: 'POST',
        data: {
            action: 'login',
            usuario: usuario,
            password: password
        },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                Swal.fire({
                    icon: 'success',
                    title: `¡Bienvenido, ${res.nombre}!`,
                    text: `Rol: ${res.rol}`,
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = res.redirect;
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Acceso denegado',
                    text: res.msg || 'Credenciales incorrectas.',
                    confirmButtonColor: '#0d6efd'
                });
                btn.val('Entrar').prop('disabled', false);
            }
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudo conectar con el servidor.',
                confirmButtonColor: '#0d6efd'
            });
            btn.val('Entrar').prop('disabled', false);
        }
    });

    return false; // Evitar submit nativo del form
}

// ── CONTRASEÑA TEMPORAL ───────────────────────────────────────────
function recuperarPassword() {

    const usuario = $('#recover_usuario').val().trim();
    const correo  = $('#recover_correo').val().trim();

    if (!usuario || !correo) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos requeridos',
            text: 'Ingresa tu usuario y correo electrónico.',
            confirmButtonColor: '#0d6efd'
        });
        return false;
    }

    const btn = $('#btnRecuperar');
    btn.prop('disabled', true).text('Verificando...');

    $.ajax({
        url: 'controller/UsuarioController.php',
        method: 'POST',
        data: {
            action: 'password_temporal',
            usuario: usuario,
            correo: correo
        },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                // Cerrar modal
                $('#modalContacto').modal('hide');

                // Mostrar contraseña temporal
                Swal.fire({
                    icon: 'success',
                    title: `¡Hola, ${res.nombre}!`,
                    html: `
                        <p>Tu contraseña temporal es:</p>
                        <div style="
                            background: #f0f4ff;
                            border: 2px dashed #0d6efd;
                            border-radius: 8px;
                            padding: 12px 20px;
                            font-size: 22px;
                            font-weight: bold;
                            letter-spacing: 3px;
                            color: #0d6efd;
                            margin: 10px 0;
                        ">${res.password}</div>
                        <p style="font-size:13px; color:#666;">
                            Úsala para ingresar y luego cámbiala en <b>Editar perfil</b>.
                        </p>
                    `,
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#0d6efd'
                });

                // Limpiar campos del modal
                $('#recover_usuario').val('');
                $('#recover_correo').val('');

            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'No encontrado',
                    text: res.msg || 'No se encontró ninguna cuenta con esos datos.',
                    confirmButtonColor: '#0d6efd'
                });
            }
            btn.prop('disabled', false).text('Obtener contraseña temporal');
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo conectar con el servidor.',
                confirmButtonColor: '#0d6efd'
            });
            btn.prop('disabled', false).text('Obtener contraseña temporal');
        }
    });

    return false;
}

// ── MOSTRAR/OCULTAR CLAVE SECRETA SEGÚN ROL ───────────────────
function verificarRol() {
    const rol = $('#reg_rol').val();
    if (rol === 'Tecnico' || rol === 'Admin') {
        $('#divClaveSecreta').show();
        $('#reg_clave_secreta').prop('required', true);
    } else {
        $('#divClaveSecreta').hide();
        $('#reg_clave_secreta').val('').prop('required', false);
    }
}

// ── REGISTRO ──────────────────────────────────────────────────
function registrarUsuario() {

    const nombre    = $('#reg_nombre').val().trim();
    const paterno   = $('#reg_paterno').val().trim();
    const materno   = $('#reg_materno').val().trim();
    const fecha_nac = $('#reg_fecha_nac').val().trim();
    const sexo      = $('#reg_sexo').val().trim();
    const telefono  = $('#reg_telefono').val().trim();
    const correo    = $('#reg_correo').val().trim();
    const usuario   = $('#reg_usuario').val().trim();
    const password  = $('#reg_password').val().trim();
    const ubicacion = $('#reg_ubicacion').val().trim();
    const rol       = $('#reg_rol').val();
    const clave     = $('#reg_clave_secreta').val().trim();

    // ── Campos obligatorios vacíos ────────────────────────────────
    if (!nombre || !paterno || !usuario || !password || !correo || !fecha_nac) {
        Swal.fire({
            icon: 'warning',
            title: 'Campos requeridos',
            html: 'Los siguientes campos son obligatorios:<br><b>Nombre, Apellido Paterno, Fecha de nacimiento, Correo, Usuario y Contraseña.</b>',
            confirmButtonColor: '#0d6efd'
        });
        return false;
    }

    // ── Fecha de nacimiento no vacía ──────────────────────────────
    if (!fecha_nac) {
        Swal.fire({
            icon: 'warning',
            title: 'Fecha requerida',
            text: 'Por favor ingresa tu fecha de nacimiento.',
            confirmButtonColor: '#0d6efd'
        });
        return false;
    }

    // ── Formato de correo ─────────────────────────────────────────
    const regexCorreo = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!regexCorreo.test(correo)) {
        Swal.fire({
            icon: 'warning',
            title: 'Correo inválido',
            text: 'Por favor ingresa un correo electrónico válido. Ej: usuario@correo.com',
            confirmButtonColor: '#0d6efd'
        });
        return false;
    }

    // ── Teléfono: solo números y 10 dígitos ───────────────────────
    if (telefono && !/^\d{10}$/.test(telefono)) {
        Swal.fire({
            icon: 'warning',
            title: 'Teléfono inválido',
            text: 'El teléfono debe contener exactamente 10 dígitos numéricos.',
            confirmButtonColor: '#0d6efd'
        });
        return false;
    }

    // ── Contraseña mínimo 8 caracteres ────────────────────────────
    if (password.length < 8) {
        Swal.fire({
            icon: 'warning',
            title: 'Contraseña muy corta',
            text: 'La contraseña debe tener al menos 8 caracteres.',
            confirmButtonColor: '#0d6efd'
        });
        return false;
    }

    // ── Clave secreta para Técnico o Admin ────────────────────────
    if ((rol === 'Tecnico' || rol === 'Admin') && !clave) {
        Swal.fire({
            icon: 'warning',
            title: 'Clave requerida',
            text: 'Para registrarse como Técnico o Admin necesitas la clave secreta.',
            confirmButtonColor: '#0d6efd'
        });
        return false;
    }

    const btn = $('#formRegistro button[type=submit]');
    btn.prop('disabled', true).text('Registrando...');

    $.ajax({
        url: 'controller/UsuarioController.php',
        method: 'POST',
        data: {
            action:        'registro',
            nombre:        nombre,
            paterno:       paterno,
            materno:       materno,
            fecha_nac:     fecha_nac,
            sexo:          sexo,
            telefono:      telefono,
            correo:        correo,
            usuario:       usuario,
            password:      password,
            ubicacion:     ubicacion,
            rol:           rol,
            clave_secreta: clave
        },
        dataType: 'json',
        success: function (res) {
            if (res.success) {
                $('#modalRegistro').modal('hide');
                $('#formRegistro')[0].reset();
                $('#divClaveSecreta').hide();
                Swal.fire({
                    icon: 'success',
                    title: '¡Cuenta creada!',
                    text: 'Ya puedes iniciar sesión con tus credenciales.',
                    confirmButtonColor: '#0d6efd'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: res.msg || 'No se pudo crear la cuenta.',
                    confirmButtonColor: '#0d6efd'
                });
            }
            btn.prop('disabled', false).text('Registrarse');
        },
        error: function () {
            Swal.fire({
                icon: 'error',
                title: 'Error de conexión',
                text: 'No se pudo conectar con el servidor.',
                confirmButtonColor: '#0d6efd'
            });
            btn.prop('disabled', false).text('Registrarse');
        }
    });

    return false;
}
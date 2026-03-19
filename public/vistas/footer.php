<!-- ── FOOTER ─────────────────────────────────────────────────── -->
<footer class="footer mt-auto py-3 bg-light shadow-sm">
    <div class="container text-center">
        <span class="text-muted" style="font-size:13px;">
            &copy; <?= date('Y') ?> Help-Desk &mdash; Todos los derechos reservados
        </span>
    </div>
</footer>

<script src="../jquery/jquery-3.6.0.min.js"></script>
<script src="../bootstrap/bootstrap.min.js"></script>
<script src="../sweetalert2/sweetalert2@11.js"></script>

<script>
function cerrarSesion() {
    Swal.fire({
        title: '¿Cerrar sesión?',
        text: '¿Estás seguro que deseas salir?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, salir',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '../../controller/UsuarioController.php',
                method: 'POST',
                data: { action: 'logout' },
                dataType: 'json',
                success: function(res) {
                    if (res.success) window.location.href = res.redirect;
                }
            });
        }
    });
}
</script>
</body>
</html>
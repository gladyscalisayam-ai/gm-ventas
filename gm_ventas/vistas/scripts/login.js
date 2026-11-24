$(function () {
  function iniciarSesion(logina, clavea) {
    $('#loginAlert').addClass('d-none');
    $.post(
      '../ajax/usuario.php?op=verificar',
      { logina: logina, clavea: clavea },
      function (data) {
        if (data && data.idusuario) {
          window.location.href = 'listado.php';
        } else {
          $('#loginAlert')
            .text('Usuario o contraseña incorrectos, o cuenta desactivada.')
            .removeClass('d-none');
        }
      },
      'json'
    ).fail(function () {
      $('#loginAlert')
        .text('No se pudo conectar con el servidor.')
        .removeClass('d-none');
    });
  }

  $('#frmAcceso').on('submit', function (e) {
    e.preventDefault();
    iniciarSesion($('#logina').val(), $('#clavea').val());
  });
});

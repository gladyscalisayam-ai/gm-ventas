var tabla;

function init() {
  mostrarform(false);
  listar();
  cargarPermisos(0);

  $('#formulario').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: '../ajax/usuario.php?op=guardaryeditar',
      type: 'POST',
      data: new FormData($('#formulario')[0]),
      contentType: false,
      processData: false,
      success: function () {
        mostrarform(false);
        tabla.ajax.reload();
      },
    });
  });
}

function cargarPermisos(idusuario) {
  $.post('../ajax/usuario.php?op=permisos&id=' + idusuario, function (r) {
    $('#permisos').html(r);
  });
}

function limpiar() {
  $('#idusuario, #nombre, #num_documento, #direccion, #telefono, #email, #cargo, #login, #clave, #imagenactual').val('');
  cargarPermisos(0);
}

function mostrarform(flag) {
  limpiar();
  $('#listadoregistros').toggleClass('d-none', flag);
  $('#formulario').toggleClass('d-none', !flag);
  $('#btnagregar').toggleClass('d-none', flag);
}

function cancelarform() {
  mostrarform(false);
}

function listar() {
  tabla = $('#tbllistado').DataTable({
    ajax: { url: '../ajax/usuario.php?op=listar', type: 'get', dataType: 'json' },
    bDestroy: true,
    iDisplayLength: 10,
  });
}

function mostrar(idusuario) {
  $.post('../ajax/usuario.php?op=mostrar', { idusuario: idusuario }, function (data) {
    mostrarform(true);
    $('#idusuario').val(data.idusuario);
    $('#nombre').val(data.nombre);
    $('#tipo_documento').val(data.tipo_documento);
    $('#num_documento').val(data.num_documento);
    $('#direccion').val(data.direccion);
    $('#telefono').val(data.telefono);
    $('#email').val(data.email);
    $('#cargo').val(data.cargo);
    $('#login').val(data.login);
    $('#imagenactual').val(data.imagen);
    cargarPermisos(idusuario);
  });
}

function desactivar(idusuario) {
  $.post('../ajax/usuario.php?op=desactivar', { idusuario: idusuario }, function () {
    tabla.ajax.reload();
  });
}

function activar(idusuario) {
  $.post('../ajax/usuario.php?op=activar', { idusuario: idusuario }, function () {
    tabla.ajax.reload();
  });
}

function eliminar(idusuario) {
  $.post('../ajax/usuario.php?op=eliminar', { idusuario: idusuario }, function () {
    tabla.ajax.reload();
  });
}

$(init);

var tabla;

function init() {
  mostrarform(false);
  listar();
  $('#formulario').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: '../ajax/cliente.php?op=guardaryeditar',
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

function limpiar() {
  $('#idpersona, #nombre, #num_documento, #direccion, #telefono, #email').val('');
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
    ajax: { url: '../ajax/cliente.php?op=listar', type: 'get', dataType: 'json' },
    bDestroy: true,
    iDisplayLength: 10,
  });
}

function mostrar(idpersona) {
  $.post('../ajax/cliente.php?op=mostrar', { idpersona: idpersona }, function (data) {
    mostrarform(true);
    $('#idpersona').val(data.idpersona);
    $('#nombre').val(data.nombre);
    $('#tipo_documento').val(data.tipo_documento);
    $('#num_documento').val(data.num_documento);
    $('#direccion').val(data.direccion);
    $('#telefono').val(data.telefono);
    $('#email').val(data.email);
  });
}

function desactivar(idpersona) {
  $.post('../ajax/cliente.php?op=desactivar', { idpersona: idpersona }, function () {
    tabla.ajax.reload();
  });
}

function activar(idpersona) {
  $.post('../ajax/cliente.php?op=activar', { idpersona: idpersona }, function () {
    tabla.ajax.reload();
  });
}

function eliminar(idpersona) {
  $.post('../ajax/cliente.php?op=eliminar', { idpersona: idpersona }, function () {
    tabla.ajax.reload();
  });
}

$(init);

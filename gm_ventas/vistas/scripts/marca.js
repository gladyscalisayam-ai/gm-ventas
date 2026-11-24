var tabla;

function init() {
  mostrarform(false);
  listar();
  $('#formulario').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: '../ajax/marca.php?op=guardaryeditar',
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
  $('#idmarca').val('');
  $('#nombre').val('');
  $('#descripcion').val('');
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
    ajax: { url: '../ajax/marca.php?op=listar', type: 'get', dataType: 'json' },
    bDestroy: true,
    iDisplayLength: 10,
  });
}

function mostrar(idmarca) {
  $.post('../ajax/marca.php?op=mostrar', { idmarca: idmarca }, function (data) {
    mostrarform(true);
    $('#idmarca').val(data.idmarca);
    $('#nombre').val(data.nombre);
    $('#descripcion').val(data.descripcion);
  });
}

function desactivar(idmarca) {
  $.post('../ajax/marca.php?op=desactivar', { idmarca: idmarca }, function () {
    tabla.ajax.reload();
  });
}

function activar(idmarca) {
  $.post('../ajax/marca.php?op=activar', { idmarca: idmarca }, function () {
    tabla.ajax.reload();
  });
}

function eliminar(idmarca) {
  $.post('../ajax/marca.php?op=eliminar', { idmarca: idmarca }, function () {
    tabla.ajax.reload();
  });
}

$(init);

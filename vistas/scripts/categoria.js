var tabla;

function init() {
  mostrarform(false);
  listar();
  $('#formulario').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: '../ajax/Categoria.php?op=guardaryeditar',
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
  $('#idcategoria').val('');
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
    ajax: { url: '../ajax/Categoria.php?op=listar', type: 'get', dataType: 'json' },
    bDestroy: true,
    iDisplayLength: 10,
  });
}

function mostrar(idcategoria) {
  $.post('../ajax/Categoria.php?op=mostrar', { idcategoria: idcategoria }, function (data) {
    mostrarform(true);
    $('#idcategoria').val(data.idcategoria);
    $('#nombre').val(data.nombre);
    $('#descripcion').val(data.descripcion);
  });
}

function desactivar(idcategoria) {
  $.post('../ajax/Categoria.php?op=desactivar', { idcategoria: idcategoria }, function () {
    tabla.ajax.reload();
  });
}

function activar(idcategoria) {
  $.post('../ajax/Categoria.php?op=activar', { idcategoria: idcategoria }, function () {
    tabla.ajax.reload();
  });
}

function eliminar(idcategoria) {
  $.post('../ajax/Categoria.php?op=eliminar', { idcategoria: idcategoria }, function () {
    tabla.ajax.reload();
  });
}

$(init);

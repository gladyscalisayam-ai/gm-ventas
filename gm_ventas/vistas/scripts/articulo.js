var tabla;

function init() {
  mostrarform(false);
  listar();

  $('#formulario').on('submit', function (e) {
    guardaryeditar(e);
  });

  $.post('../ajax/articulo.php?op=selectCategoria', function (r) {
    $('#idcategoria').html(r);
  });

  $.post('../ajax/articulo.php?op=selectMarca', function (r) {
    $('#idmarca').html(r);
  });
}

function limpiar() {
  $('#idarticulo').val('');
  $('#codigo').val('');
  $('#nombre').val('');
  $('#stock').val('0');
  $('#precio_venta').val('0');
  $('#descripcion').val('');
  $('#imagenactual').val('');
}

function mostrarform(flag) {
  limpiar();
  if (flag) {
    $('#listadoregistros').addClass('d-none');
    $('#formulario').removeClass('d-none');
    $('#btnagregar').addClass('d-none');
  } else {
    $('#listadoregistros').removeClass('d-none');
    $('#formulario').addClass('d-none');
    $('#btnagregar').removeClass('d-none');
  }
}

function cancelarform() {
  limpiar();
  mostrarform(false);
}

function listar() {
  tabla = $('#tbllistado').DataTable({
    aProcessing: true,
    aServerSide: false,
    ajax: {
      url: '../ajax/articulo.php?op=listar',
      type: 'get',
      dataType: 'json',
    },
    bDestroy: true,
    iDisplayLength: 10,
    order: [[1, 'asc']],
  });
}

function guardaryeditar(e) {
  e.preventDefault();
  var formData = new FormData($('#formulario')[0]);
  $.ajax({
    url: '../ajax/articulo.php?op=guardaryeditar',
    type: 'POST',
    data: formData,
    contentType: false,
    processData: false,
    success: function () {
      mostrarform(false);
      tabla.ajax.reload();
    },
  });
}

function mostrar(idarticulo) {
  $.post('../ajax/articulo.php?op=mostrar', { idarticulo: idarticulo }, function (data) {
    mostrarform(true);
    $('#idarticulo').val(data.idarticulo);
    $('#idcategoria').val(data.idcategoria);
    $('#idmarca').val(data.idmarca);
    $('#codigo').val(data.codigo);
    $('#nombre').val(data.nombre);
    $('#stock').val(data.stock);
    $('#precio_venta').val(data.precio_venta);
    $('#descripcion').val(data.descripcion);
    $('#imagenactual').val(data.imagen);
  });
}

function desactivar(idarticulo) {
  $.post('../ajax/articulo.php?op=desactivar', { idarticulo: idarticulo }, function () {
    tabla.ajax.reload();
  });
}

function activar(idarticulo) {
  $.post('../ajax/articulo.php?op=activar', { idarticulo: idarticulo }, function () {
    tabla.ajax.reload();
  });
}

function eliminar(idarticulo) {
  $.post('../ajax/articulo.php?op=eliminar', { idarticulo: idarticulo }, function () {
    tabla.ajax.reload();
  });
}

$(init);

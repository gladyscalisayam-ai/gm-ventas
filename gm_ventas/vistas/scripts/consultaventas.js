var tabla;

function listar() {
  tabla = $('#tbllistado').DataTable({
    ajax: {
      url: '../ajax/consultacompras.php?op=ventasfechacliente',
      data: {
        fecha_inicio: $('#fecha_inicio').val(),
        fecha_fin: $('#fecha_fin').val(),
        idcliente: $('#idcliente').val(),
        estado: $('#estado').val(),
        metodo_pago: $('#metodo_pago').val(),
      },
      type: 'get',
      dataType: 'json',
    },
    bDestroy: true,
    iDisplayLength: 10,
  });
}

$(function () {
  $.post('../ajax/venta.php?op=selectCliente', function (r) {
    $('#idcliente').append(r);
  });
  listar();
  $('#btnFiltrar').on('click', listar);
});

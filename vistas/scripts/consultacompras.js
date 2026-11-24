var tabla;

function listar() {
  tabla = $('#tbllistado').DataTable({
    ajax: {
      url: '../ajax/consultacompras.php?op=comprasfecha',
      data: {
        fecha_inicio: $('#fecha_inicio').val(),
        fecha_fin: $('#fecha_fin').val(),
        idproveedor: $('#idproveedor').val(),
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
  $.post('../ajax/ingreso.php?op=selectProveedor', function (r) {
    $('#idproveedor').append(r);
  });
  listar();
  $('#btnFiltrar').on('click', listar);
});

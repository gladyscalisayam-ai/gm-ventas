var tabla;
var cont = 0;

function init() {
  mostrarform(false);
  listar();
  listarArticulos();

  $.post('../ajax/ingreso.php?op=selectProveedor', function (r) {
    $('#idproveedor').html(r);
  });

  $('#formulario').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: '../ajax/ingreso.php?op=guardaryeditar',
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
  $('#idingreso, #num_comprobante').val('');
  $('#fecha_hora').val(new Date().toISOString().slice(0, 10));
  $('#detalles tbody').empty();
  $('#total').text('0.00');
  $('#total_compra').val('0');
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
    ajax: { url: '../ajax/ingreso.php?op=listar', type: 'get', dataType: 'json' },
    bDestroy: true,
    iDisplayLength: 10,
  });
}

function listarArticulos() {
  $('#tblarticulos').DataTable({
    ajax: { url: '../ajax/ingreso.php?op=listarArticulos', type: 'get', dataType: 'json' },
    bDestroy: true,
    iDisplayLength: 5,
  });
}

function agregarDetalle(idarticulo, nombre, precio_venta) {
  var precioCompra = (Number(precio_venta) * 0.72).toFixed(2);
  var fila =
    '<tr id="fila' + cont + '">' +
    '<td><button type="button" class="btn btn-sm btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
    '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + nombre + '</td>' +
    '<td><input type="number" name="cantidad[]" value="1" min="1" class="form-control form-control-sm" oninput="modificarSubtotales()"></td>' +
    '<td><input type="number" name="precio_compra[]" value="' + precioCompra + '" step="0.01" min="0" class="form-control form-control-sm" oninput="modificarSubtotales()"></td>' +
    '<td><input type="number" name="precio_venta[]" value="' + precio_venta + '" step="0.01" min="0" class="form-control form-control-sm"></td>' +
    '<td class="subtotal fw-bold">' + precioCompra + '</td>' +
    '</tr>';
  cont++;
  $('#detalles tbody').append(fila);
  modificarSubtotales();
}

function modificarSubtotales() {
  var total = 0;
  $('#detalles tbody tr').each(function () {
    var cant = Number($(this).find('input[name="cantidad[]"]').val() || 0);
    var pCompra = Number($(this).find('input[name="precio_compra[]"]').val() || 0);
    var sub = cant * pCompra;
    $(this).find('.subtotal').text(sub.toFixed(2));
    total += sub;
  });
  $('#total').text(total.toFixed(2));
  $('#total_compra').val(total.toFixed(2));
}

function eliminarDetalle(indice) {
  $('#fila' + indice).remove();
  modificarSubtotales();
}

function mostrar(idingreso) {
  $.post('../ajax/ingreso.php?op=mostrar', { idingreso: idingreso }, function (data) {
    var c = data.cabecera;
    var rows = (data.detalles || [])
      .map(function (d) {
        return '<tr><td>' + d.articulo + '</td><td>' + d.cantidad + '</td><td>S/ ' + Number(d.precio_compra).toFixed(2) + '</td><td>S/ ' + (d.cantidad * d.precio_compra).toFixed(2) + '</td></tr>';
      })
      .join('');
    $('#detalleContenido').html(
      '<p class="small mb-2"><strong>Proveedor:</strong> ' + c.proveedor + ' · <strong>Método:</strong> ' + c.metodo_pago + ' · <strong>Fecha:</strong> ' + c.fecha_hora + '</p>' +
      '<table class="table table-sm table-bordered"><thead><tr><th>Producto</th><th>Cant.</th><th>P. Compra</th><th>Subtotal</th></tr></thead><tbody>' + rows + '</tbody></table>' +
      '<div class="text-end fw-bold" style="color:#5b21b6;">Total: S/ ' + Number(c.total_compra).toFixed(2) + '</div>'
    );
    new bootstrap.Modal(document.getElementById('modalDetalle')).show();
  });
}

function anular(idingreso) {
  $.post('../ajax/ingreso.php?op=anular', { idingreso: idingreso }, function () {
    tabla.ajax.reload();
  });
}

$(init);

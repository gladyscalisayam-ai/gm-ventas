var tabla;
var cont = 0;

function init() {
  mostrarform(false);
  listar();
  listarArticulos();

  $.post('../ajax/venta.php?op=selectCliente', function (r) {
    $('#idcliente').html(r);
  });

  $('#formulario').on('submit', function (e) {
    e.preventDefault();
    $.ajax({
      url: '../ajax/venta.php?op=guardaryeditar',
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
  $('#idventa, #num_comprobante').val('');
  $('#fecha_hora').val(new Date().toISOString().slice(0, 10));
  $('#detalles tbody').empty();
  $('#total').text('0.00');
  $('#total_venta').val('0');
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
    ajax: { url: '../ajax/venta.php?op=listar', type: 'get', dataType: 'json' },
    bDestroy: true,
    iDisplayLength: 10,
  });
}

function listarArticulos() {
  $('#tblarticulos').DataTable({
    ajax: { url: '../ajax/venta.php?op=listarArticulosVenta', type: 'get', dataType: 'json' },
    bDestroy: true,
    iDisplayLength: 5,
  });
}

function agregarDetalle(idarticulo, nombre, precio_venta, stock) {
  var fila =
    '<tr id="fila' + cont + '">' +
    '<td><button type="button" class="btn btn-sm btn-danger" onclick="eliminarDetalle(' + cont + ')">X</button></td>' +
    '<td><input type="hidden" name="idarticulo[]" value="' + idarticulo + '">' + nombre + ' <small class="text-muted">(Disp: ' + stock + ')</small></td>' +
    '<td><input type="number" name="cantidad[]" value="1" min="1" max="' + stock + '" class="form-control form-control-sm" oninput="modificarSubtotales()"></td>' +
    '<td><input type="number" name="precio_venta[]" value="' + precio_venta + '" step="0.01" min="0" class="form-control form-control-sm" oninput="modificarSubtotales()"></td>' +
    '<td><input type="number" name="descuento[]" value="0" step="0.01" min="0" class="form-control form-control-sm" oninput="modificarSubtotales()"></td>' +
    '<td class="subtotal fw-bold">' + Number(precio_venta).toFixed(2) + '</td>' +
    '</tr>';
  cont++;
  $('#detalles tbody').append(fila);
  modificarSubtotales();
}

function modificarSubtotales() {
  var total = 0;
  $('#detalles tbody tr').each(function () {
    var cant = Number($(this).find('input[name="cantidad[]"]').val() || 0);
    var pVenta = Number($(this).find('input[name="precio_venta[]"]').val() || 0);
    var desc = Number($(this).find('input[name="descuento[]"]').val() || 0);
    var sub = Math.max(0, cant * pVenta - desc);
    $(this).find('.subtotal').text(sub.toFixed(2));
    total += sub;
  });
  $('#total').text(total.toFixed(2));
  $('#total_venta').val(total.toFixed(2));
}

function eliminarDetalle(indice) {
  $('#fila' + indice).remove();
  modificarSubtotales();
}

function mostrarComprobante(idventa) {
  $.post('../ajax/venta.php?op=mostrar', { idventa: idventa }, function (data) {
    var c = data.cabecera;
    var total = Number(c.total_venta || 0);
    var base = total / 1.18;
    var igv = total - base;
    var rows = (data.detalles || [])
      .map(function (d) {
        var sub = Math.max(0, d.cantidad * d.precio_venta - d.descuento);
        return '<tr><td>' + d.articulo + '</td><td class="text-end">' + d.cantidad + '</td><td class="text-end">S/ ' + Number(d.precio_venta).toFixed(2) + '</td><td class="text-end">S/ ' + Number(d.descuento).toFixed(2) + '</td><td class="text-end fw-bold">S/ ' + sub.toFixed(2) + '</td></tr>';
      })
      .join('');
    $('#comprobanteContenido').html(
      '<div class="d-flex justify-content-between align-items-start border-bottom pb-2 mb-3">' +
      '<div><strong style="color:#5b21b6; font-size:1.1rem;">GM VENTAS</strong><div class="small text-muted">Salón &amp; Cosmética · RUC 20609481205</div></div>' +
      '<div class="text-end border rounded px-3 py-1 bg-light"><div class="small fw-bold">' + c.tipo_comprobante + '</div><div class="fw-bold">' + c.serie_comprobante + '-' + c.num_comprobante + '</div></div>' +
      '</div>' +
      '<div class="row small mb-3"><div class="col-6"><strong>Cliente:</strong> ' + c.cliente + '</div><div class="col-6"><strong>Fecha:</strong> ' + c.fecha_hora + '</div><div class="col-6"><strong>Doc:</strong> ' + c.tipo_documento + ' ' + c.num_documento + '</div><div class="col-6"><strong>Pago:</strong> ' + c.metodo_pago + '</div></div>' +
      '<table class="table table-sm table-bordered"><thead><tr><th>Producto</th><th class="text-end">Cant.</th><th class="text-end">P. Unit</th><th class="text-end">Desc.</th><th class="text-end">Subtotal</th></tr></thead><tbody>' + rows + '</tbody></table>' +
      '<div class="text-end small">Op. Gravada: <strong>S/ ' + base.toFixed(2) + '</strong><br>IGV (18%): <strong>S/ ' + igv.toFixed(2) + '</strong><br><span class="fs-6 fw-bold" style="color:#5b21b6;">TOTAL: S/ ' + total.toFixed(2) + '</span></div>'
    );
    new bootstrap.Modal(document.getElementById('modalComprobante')).show();
  });
}

function anular(idventa) {
  $.post('../ajax/venta.php?op=anular', { idventa: idventa }, function () {
    tabla.ajax.reload();
  });
}

$(init);

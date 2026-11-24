<?php require_once "header.php"; ?>

<section class="gm-card p-4 no-print">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
      <h1 class="h5 fw-bold mb-0" style="color:#4c1d95;">Ventas / Punto de Venta (POS)</h1>
      <small class="text-muted">Ventas · Descuento automático de stock, IGV (18%) y comprobante imprimible</small>
    </div>
    <button type="button" id="btnagregar" class="btn btn-gm btn-sm" onclick="mostrarform(true)">
      <i class="bi bi-plus-lg me-1"></i>Nueva Venta POS
    </button>
  </div>

  <div id="listadoregistros" class="table-responsive">
    <table id="tbllistado" class="table table-hover table-gm align-middle w-100">
      <thead>
        <tr>
          <th>Opciones</th>
          <th>Fecha</th>
          <th>Cliente</th>
          <th>Vendedor</th>
          <th>Comprobante</th>
          <th>Método Pago</th>
          <th>Total</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <form id="formulario" name="formulario" method="POST" class="d-none">
    <input type="hidden" name="idventa" id="idventa">
    <div class="row g-3 mb-3">
      <div class="col-md-4">
        <label for="idcliente" class="form-label small fw-bold">Cliente *</label>
        <select name="idcliente" id="idcliente" class="form-select" required></select>
      </div>
      <div class="col-md-2">
        <label for="tipo_comprobante" class="form-label small fw-bold">Comprobante *</label>
        <select name="tipo_comprobante" id="tipo_comprobante" class="form-select" required>
          <option value="Boleta">Boleta</option>
          <option value="Factura">Factura</option>
          <option value="Ticket">Ticket</option>
        </select>
      </div>
      <div class="col-md-2">
        <label for="metodo_pago" class="form-label small fw-bold">Método Pago *</label>
        <select name="metodo_pago" id="metodo_pago" class="form-select" required>
          <option value="Efectivo">Efectivo</option>
          <option value="Yape/Plin">Yape/Plin</option>
          <option value="Tarjeta">Tarjeta</option>
          <option value="Transferencia">Transferencia</option>
        </select>
      </div>
      <div class="col-md-2">
        <label for="serie_comprobante" class="form-label small fw-bold">Serie</label>
        <input type="text" name="serie_comprobante" id="serie_comprobante" class="form-control" value="B001" required>
      </div>
      <div class="col-md-2">
        <label for="num_comprobante" class="form-label small fw-bold">Número</label>
        <input type="text" name="num_comprobante" id="num_comprobante" class="form-control" required>
      </div>
      <div class="col-md-3">
        <label for="fecha_hora" class="form-label small fw-bold">Fecha *</label>
        <input type="date" name="fecha_hora" id="fecha_hora" class="form-control" required>
      </div>
      <div class="col-md-2">
        <label for="impuesto" class="form-label small fw-bold">IGV (%)</label>
        <input type="number" name="impuesto" id="impuesto" class="form-control" value="18" readonly>
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <button type="button" class="btn btn-outline-primary btn-sm" style="border-color:#d8b4fe; color:#4c1d95;" data-bs-toggle="modal" data-bs-target="#modalArticulosVenta">
          <i class="bi bi-plus-circle me-1"></i>Agregar Productos
        </button>
      </div>
    </div>

    <div class="table-responsive mb-3">
      <table id="detalles" class="table table-bordered table-gm align-middle">
        <thead>
          <tr>
            <th>Opciones</th>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Precio Venta</th>
            <th>Descuento</th>
            <th>Subtotal</th>
          </tr>
        </thead>
        <tbody></tbody>
        <tfoot>
          <tr>
            <th colspan="5" class="text-end">TOTAL S/</th>
            <th><span id="total">0.00</span><input type="hidden" name="total_venta" id="total_venta" value="0"></th>
          </tr>
        </tfoot>
      </table>
    </div>

    <div class="d-flex gap-2">
      <button type="submit" id="btnGuardar" class="btn btn-gm btn-sm">Registrar Venta</button>
      <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cancelarform()">Cancelar</button>
    </div>
  </form>
</section>

<div class="modal fade no-print" id="modalArticulosVenta" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header" style="background:#f3e8ff; color:#4c1d95;">
        <h2 class="modal-title h6 fw-bold">Productos con Stock Disponible</h2>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
      </div>
      <div class="modal-body table-responsive">
        <table id="tblarticulos" class="table table-hover table-gm w-100">
          <thead>
            <tr>
              <th>Opciones</th>
              <th>Nombre</th>
              <th>Categoría / Marca</th>
              <th>Código</th>
              <th>Stock</th>
              <th>Precio</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="modalComprobante" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header no-print" style="background:#f3e8ff; color:#4c1d95;">
        <h2 class="modal-title h6 fw-bold">Comprobante de Venta — GM VENTAS</h2>
        <div class="ms-auto d-flex gap-2">
          <button type="button" class="btn btn-gm btn-sm" onclick="window.print()">
            <i class="bi bi-printer me-1"></i>Imprimir
          </button>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
        </div>
      </div>
      <div class="modal-body" id="comprobanteContenido"></div>
    </div>
  </div>
</div>

<?php require_once "footer.php"; ?>
<script src="scripts/venta.js"></script>

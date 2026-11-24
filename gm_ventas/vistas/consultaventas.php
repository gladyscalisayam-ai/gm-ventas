<?php require_once "header.php"; ?>

<section class="gm-card p-4">
  <div class="mb-3">
    <h1 class="h5 fw-bold mb-0" style="color:#4c1d95;">Consulta Ventas</h1>
    <small class="text-muted">Reportes · Filtros por fecha, cliente, estado y método de pago</small>
  </div>

  <div class="row g-3 mb-3">
    <div class="col-md-2">
      <label for="fecha_inicio" class="form-label small fw-bold">Fecha Inicio</label>
      <input type="date" id="fecha_inicio" class="form-control form-control-sm" value="<?php echo date('Y-m-01'); ?>">
    </div>
    <div class="col-md-2">
      <label for="fecha_fin" class="form-label small fw-bold">Fecha Fin</label>
      <input type="date" id="fecha_fin" class="form-control form-control-sm" value="<?php echo date('Y-m-t'); ?>">
    </div>
    <div class="col-md-3">
      <label for="idcliente" class="form-label small fw-bold">Cliente</label>
      <select id="idcliente" class="form-select form-select-sm">
        <option value="">Todos</option>
      </select>
    </div>
    <div class="col-md-2">
      <label for="estado" class="form-label small fw-bold">Estado</label>
      <select id="estado" class="form-select form-select-sm">
        <option value="">Todos</option>
        <option value="Aceptado">Aceptado</option>
        <option value="Anulado">Anulado</option>
      </select>
    </div>
    <div class="col-md-2">
      <label for="metodo_pago" class="form-label small fw-bold">Método Pago</label>
      <select id="metodo_pago" class="form-select form-select-sm">
        <option value="">Todos</option>
        <option value="Efectivo">Efectivo</option>
        <option value="Yape/Plin">Yape/Plin</option>
        <option value="Tarjeta">Tarjeta</option>
        <option value="Transferencia">Transferencia</option>
      </select>
    </div>
    <div class="col-md-1 d-flex align-items-end">
      <button type="button" id="btnFiltrar" class="btn btn-gm btn-sm w-100">Filtrar</button>
    </div>
  </div>

  <div class="table-responsive">
    <table id="tbllistado" class="table table-hover table-gm align-middle w-100">
      <thead>
        <tr>
          <th>Fecha</th>
          <th>Vendedor</th>
          <th>Cliente</th>
          <th>Comprobante</th>
          <th>Número</th>
          <th>Método Pago</th>
          <th>Total Venta</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</section>

<?php require_once "footer.php"; ?>
<script src="scripts/consultaventas.js"></script>

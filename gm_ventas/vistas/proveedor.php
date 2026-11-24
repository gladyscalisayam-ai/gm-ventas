<?php require_once "header.php"; ?>

<section class="gm-card p-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
      <h1 class="h5 fw-bold mb-0" style="color:#4c1d95;">Proveedores</h1>
      <small class="text-muted">Compras · Mayoristas y distribuidores de salón</small>
    </div>
    <button type="button" id="btnagregar" class="btn btn-gm btn-sm" onclick="mostrarform(true)">
      <i class="bi bi-plus-lg me-1"></i>Agregar Proveedor
    </button>
  </div>

  <div id="listadoregistros" class="table-responsive">
    <table id="tbllistado" class="table table-hover table-gm align-middle w-100">
      <thead>
        <tr>
          <th>Opciones</th>
          <th>Nombre / Razón Social</th>
          <th>Documento</th>
          <th>Teléfono</th>
          <th>Email</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <form id="formulario" name="formulario" method="POST" class="d-none">
    <input type="hidden" name="idpersona" id="idpersona">
    <div class="row g-3">
      <div class="col-md-6">
        <label for="nombre" class="form-label small fw-bold">Nombre / Razón Social *</label>
        <input type="text" class="form-control" name="nombre" id="nombre" required>
      </div>
      <div class="col-md-3">
        <label for="tipo_documento" class="form-label small fw-bold">Tipo Doc. *</label>
        <select name="tipo_documento" id="tipo_documento" class="form-select">
          <option value="RUC">RUC</option>
          <option value="DNI">DNI</option>
          <option value="CE">CE</option>
        </select>
      </div>
      <div class="col-md-3">
        <label for="num_documento" class="form-label small fw-bold">N° Documento *</label>
        <input type="text" class="form-control" name="num_documento" id="num_documento" required>
      </div>
      <div class="col-md-6">
        <label for="direccion" class="form-label small fw-bold">Dirección</label>
        <input type="text" class="form-control" name="direccion" id="direccion">
      </div>
      <div class="col-md-3">
        <label for="telefono" class="form-label small fw-bold">Teléfono</label>
        <input type="text" class="form-control" name="telefono" id="telefono">
      </div>
      <div class="col-md-3">
        <label for="email" class="form-label small fw-bold">Email</label>
        <input type="email" class="form-control" name="email" id="email">
      </div>
      <div class="col-12 d-flex gap-2">
        <button type="submit" id="btnGuardar" class="btn btn-gm btn-sm">Guardar</button>
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cancelarform()">Cancelar</button>
      </div>
    </div>
  </form>
</section>

<?php require_once "footer.php"; ?>
<script src="scripts/proveedor.js"></script>

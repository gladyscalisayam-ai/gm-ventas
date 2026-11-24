<?php require_once "header.php"; ?>

<section class="gm-card p-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
      <h1 class="h5 fw-bold mb-0" style="color:#4c1d95;">Marcas</h1>
      <small class="text-muted">Almacén · Líneas y fabricantes profesionales de salón</small>
    </div>
    <button type="button" id="btnagregar" class="btn btn-gm btn-sm" onclick="mostrarform(true)">
      <i class="bi bi-plus-lg me-1"></i>Agregar Marca
    </button>
  </div>

  <div id="listadoregistros" class="table-responsive">
    <table id="tbllistado" class="table table-hover table-gm align-middle w-100">
      <thead>
        <tr>
          <th>Opciones</th>
          <th>Nombre</th>
          <th>Descripción</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <form id="formulario" name="formulario" method="POST" class="d-none">
    <input type="hidden" name="idmarca" id="idmarca">
    <div class="row g-3">
      <div class="col-md-6">
        <label for="nombre" class="form-label small fw-bold">Nombre de la Marca *</label>
        <input type="text" class="form-control" name="nombre" id="nombre" required>
      </div>
      <div class="col-md-6">
        <label for="descripcion" class="form-label small fw-bold">Descripción</label>
        <input type="text" class="form-control" name="descripcion" id="descripcion">
      </div>
      <div class="col-12 d-flex gap-2">
        <button type="submit" id="btnGuardar" class="btn btn-gm btn-sm">Guardar</button>
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cancelarform()">Cancelar</button>
      </div>
    </div>
  </form>
</section>

<?php require_once "footer.php"; ?>
<script src="scripts/marca.js"></script>

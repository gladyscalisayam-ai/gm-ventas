<?php require_once "header.php"; ?>

<section class="gm-card p-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
      <h1 class="h5 fw-bold mb-0" style="color:#4c1d95;">Artículos de Salón</h1>
      <small class="text-muted">Almacén · Productos capilares, tintes y equipos</small>
    </div>
    <button type="button" id="btnagregar" class="btn btn-gm btn-sm" onclick="mostrarform(true)">
      <i class="bi bi-plus-lg me-1"></i>Agregar Producto
    </button>
  </div>

  <div id="listadoregistros" class="table-responsive">
    <table id="tbllistado" class="table table-hover table-gm align-middle w-100">
      <thead>
        <tr>
          <th>Opciones</th>
          <th>Nombre</th>
          <th>Categoría</th>
          <th>Marca</th>
          <th>Código</th>
          <th>Stock</th>
          <th>Precio Venta</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>

  <form id="formulario" name="formulario" method="POST" enctype="multipart/form-data" class="d-none">
    <input type="hidden" name="idarticulo" id="idarticulo">
    <input type="hidden" name="imagenactual" id="imagenactual">
    <div class="row g-3">
      <div class="col-md-6">
        <label for="nombre" class="form-label small fw-bold">Nombre *</label>
        <input type="text" class="form-control" name="nombre" id="nombre" required>
      </div>
      <div class="col-md-3">
        <label for="idcategoria" class="form-label small fw-bold">Categoría *</label>
        <select name="idcategoria" id="idcategoria" class="form-select" required></select>
      </div>
      <div class="col-md-3">
        <label for="idmarca" class="form-label small fw-bold">Marca *</label>
        <select name="idmarca" id="idmarca" class="form-select" required></select>
      </div>
      <div class="col-md-4">
        <label for="codigo" class="form-label small fw-bold">Código (ej. SLN-006) *</label>
        <input type="text" class="form-control" name="codigo" id="codigo" required>
      </div>
      <div class="col-md-4">
        <label for="stock" class="form-label small fw-bold">Stock *</label>
        <input type="number" class="form-control" name="stock" id="stock" min="0" required>
      </div>
      <div class="col-md-4">
        <label for="precio_venta" class="form-label small fw-bold">Precio Venta (S/) *</label>
        <input type="number" class="form-control" name="precio_venta" id="precio_venta" step="0.01" min="0" required>
      </div>
      <div class="col-md-8">
        <label for="descripcion" class="form-label small fw-bold">Descripción</label>
        <input type="text" class="form-control" name="descripcion" id="descripcion">
      </div>
      <div class="col-md-4">
        <label for="imagen" class="form-label small fw-bold">Imagen</label>
        <input type="file" class="form-control" name="imagen" id="imagen" accept="image/*">
      </div>
      <div class="col-12 d-flex gap-2">
        <button type="submit" id="btnGuardar" class="btn btn-gm btn-sm">Guardar</button>
        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="cancelarform()">Cancelar</button>
      </div>
    </div>
  </form>
</section>

<?php require_once "footer.php"; ?>
<script src="scripts/articulo.js"></script>

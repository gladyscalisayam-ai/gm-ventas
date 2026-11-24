<?php require_once "header.php"; ?>

<section class="gm-card p-4">
  <div class="mb-3">
    <h1 class="h5 fw-bold mb-0" style="color:#4c1d95;">Permisos del Sistema</h1>
    <small class="text-muted">Acceso · Lista de permisos asignables a usuarios</small>
  </div>

  <div class="table-responsive">
    <table id="tbllistado" class="table table-hover table-gm align-middle w-100">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nombre del Permiso</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>
  </div>
</section>

<?php require_once "footer.php"; ?>
<script src="scripts/permiso.js"></script>

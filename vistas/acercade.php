<?php require_once "header.php"; ?>

<section class="row g-4">
  <div class="col-lg-6">
    <div class="gm-card p-4 h-100">
      <h1 class="h5 fw-bold mb-2" style="color:#4c1d95;"><i class="bi bi-stars me-2"></i>Acerca de GM VENTAS v2.6</h1>
      <p class="small text-muted">Sistema web de inventario y punto de venta (POS) en tema morado, diseñado bajo arquitectura MVC modular para productos de salón de belleza.</p>
      <ul class="small list-unstyled mb-3">
        <li><strong>Administradora:</strong> Gladys (<code>gladys / 123456</code>)</li>
        <li><strong>Vendedora:</strong> Mery (<code>mery / 12345</code>)</li>
        <li><strong>Tecnologías:</strong> PHP 8 · MySQLi · Bootstrap 5 · jQuery AJAX · Vue.js 3 · Chart.js</li>
      </ul>
      <div class="border-top pt-3">
        <h2 class="h6 fw-bold mb-2" style="color:#4c1d95;">Exportación directa de base de datos</h2>
        <div class="d-flex flex-wrap gap-2">
          <a href="../ajax/api_articulo.php?op=exportar_sql" class="btn btn-gm btn-sm">
            <i class="bi bi-filetype-sql me-1"></i>Exportar .sql
          </a>
          <a href="../ajax/api_articulo.php?op=exportar_json" class="btn btn-outline-primary btn-sm" style="border-color:#d8b4fe; color:#4c1d95;">
            <i class="bi bi-filetype-json me-1"></i>Exportar .json
          </a>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="gm-card p-4 h-100">
      <h2 class="h6 fw-bold mb-3" style="color:#4c1d95;"><i class="bi bi-diagram-3 me-2"></i>Arquitectura Modular (gm_ventas/)</h2>
      <pre class="bg-light border rounded-3 p-3 small mb-0">gm_ventas/
├── config/ (global.php, Conexion.php)
├── database/gm_ventas.sql
├── modelos/ (Categoria, Marca, articulo, cliente, proveedor, Ingreso, venta, usuario, permiso, consultacompras)
├── ajax/ (controladores JSON + api_articulo.php)
├── vistas/ (login.html, header.php, footer.php, listado.php, CRUDs, consultas, ayuda.php, acercade.php)
├── vistas/scripts/ (vue-stock-manager.js + scripts AJAX)
└── public/css/purple-theme.css</pre>
    </div>
  </div>
</section>

<?php require_once "footer.php"; ?>

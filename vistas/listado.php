<?php require_once "header.php"; ?>

<section class="gm-hero p-4 p-md-5 mb-4 shadow-sm">
  <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
    <div>
      <span class="badge bg-white bg-opacity-25 text-white mb-2">Panel Principal · Salón &amp; Cosmética</span>
      <h1 class="h3 fw-bold mb-1">GM VENTAS</h1>
      <p class="mb-3 opacity-75 small">Administra productos de salón, categorías, marcas, compras, ventas POS y clientes en un solo lugar.</p>
      <div class="d-flex flex-wrap gap-2">
        <a href="venta.php" class="btn btn-light btn-sm fw-bold" style="color:#5b21b6;"><i class="bi bi-cart-plus me-1"></i>Ir a Ventas / POS</a>
        <a href="Categoria.php" class="btn btn-outline-light btn-sm fw-semibold">Administrar categorías</a>
      </div>
    </div>
  </div>
</section>

<section class="row g-3 mb-4" aria-label="Resumen del sistema">
  <div class="col-6 col-lg-3">
    <div class="gm-card p-3 h-100">
      <div class="small text-muted fw-semibold">Productos</div>
      <div id="kpiProductos" class="fs-2 fw-bold" style="color:#4c1d95;">--</div>
      <a href="articulo.php" class="small text-decoration-none fw-bold" style="color:#7c3aed;">Ver productos +</a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="gm-card p-3 h-100">
      <div class="small text-muted fw-semibold">Categorías</div>
      <div id="kpiCategorias" class="fs-2 fw-bold" style="color:#4c1d95;">--</div>
      <a href="Categoria.php" class="small text-decoration-none fw-bold" style="color:#7c3aed;">Ver categorías +</a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="gm-card p-3 h-100">
      <div class="small text-muted fw-semibold">Clientes</div>
      <div id="kpiClientes" class="fs-2 fw-bold" style="color:#4c1d95;">--</div>
      <a href="cliente.php" class="small text-decoration-none fw-bold" style="color:#7c3aed;">Ver clientes +</a>
    </div>
  </div>
  <div class="col-6 col-lg-3">
    <div class="gm-card p-3 h-100">
      <div class="small text-muted fw-semibold">Ventas</div>
      <div id="kpiVentas" class="fs-2 fw-bold" style="color:#4c1d95;">--</div>
      <a href="venta.php" class="small text-decoration-none fw-bold" style="color:#7c3aed;">Ver ventas +</a>
    </div>
  </div>
</section>

<section class="row g-3 mb-4">
  <div class="col-lg-7">
    <div class="gm-card p-4 h-100">
      <h2 class="h6 fw-bold mb-3" style="color:#4c1d95;"><i class="bi bi-lightning-charge me-1"></i>Accesos rápidos</h2>
      <div class="row g-2">
        <div class="col-6 col-md-4"><a href="articulo.php" class="btn btn-light w-100 border text-start small fw-semibold"><i class="bi bi-box-seam d-block fs-5 mb-1" style="color:#7c3aed;"></i>Productos</a></div>
        <div class="col-6 col-md-4"><a href="Categoria.php" class="btn btn-light w-100 border text-start small fw-semibold"><i class="bi bi-tags d-block fs-5 mb-1" style="color:#7c3aed;"></i>Categorías</a></div>
        <div class="col-6 col-md-4"><a href="marca.php" class="btn btn-light w-100 border text-start small fw-semibold"><i class="bi bi-award d-block fs-5 mb-1" style="color:#7c3aed;"></i>Marcas</a></div>
        <div class="col-6 col-md-4"><a href="proveedor.php" class="btn btn-light w-100 border text-start small fw-semibold"><i class="bi bi-truck d-block fs-5 mb-1" style="color:#7c3aed;"></i>Proveedores</a></div>
        <div class="col-6 col-md-4"><a href="ingreso.php" class="btn btn-light w-100 border text-start small fw-semibold"><i class="bi bi-bag-plus d-block fs-5 mb-1" style="color:#7c3aed;"></i>Compras</a></div>
        <div class="col-6 col-md-4"><a href="venta.php" class="btn btn-light w-100 border text-start small fw-semibold"><i class="bi bi-cart-check d-block fs-5 mb-1" style="color:#7c3aed;"></i>Ventas POS</a></div>
      </div>
    </div>
  </div>

  <div class="col-lg-5">
    <div class="gm-card p-4 h-100">
      <h2 class="h6 fw-bold mb-2" style="color:#4c1d95;"><i class="bi bi-stars me-1"></i>GM VENTAS</h2>
      <p class="small text-muted mb-2">Plataforma integral para salones de belleza y distribuidoras capilares:</p>
      <ul class="small mb-0 ps-3">
        <li>Control de productos SLN-001 a SLN-005 por marca</li>
        <li>Ingresos con incremento automático de stock</li>
        <li>Punto de venta POS con comprobante imprimible</li>
        <li>Consultas por método de pago y exportación SQL/JSON</li>
      </ul>
    </div>
  </div>
</section>

<section class="row g-3 mb-4" aria-label="Gráficos de stock y categorías">
  <div class="col-lg-7">
    <div class="gm-card p-4 h-100">
      <h2 class="h6 fw-bold mb-3" style="color:#4c1d95;">Stock por Producto de Salón</h2>
      <canvas id="chartStockProductos" height="160"></canvas>
    </div>
  </div>
  <div class="col-lg-5">
    <div class="gm-card p-4 h-100">
      <h2 class="h6 fw-bold mb-3" style="color:#4c1d95;">Productos por Categoría</h2>
      <canvas id="chartCategorias" height="160"></canvas>
    </div>
  </div>
</section>

<section id="vueStockApp" class="gm-card p-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
      <h2 class="h6 fw-bold mb-0" style="color:#4c1d95;">Control Reactivo de Stock (Vue.js 3)</h2>
      <small class="text-muted">Actualización instantánea de existencias vía AJAX</small>
    </div>
    <input type="search" v-model="busqueda" class="form-control form-control-sm" style="max-width:260px;" placeholder="Filtrar producto o código...">
  </div>
  <div class="row g-3">
    <div class="col-md-6 col-xl-4" v-for="item in articulosFiltrados" :key="item.idarticulo">
      <stock-card :item="item" @ajustar="ajustarStock"></stock-card>
    </div>
  </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.prod.js"></script>
<script src="scripts/vue-stock-manager.js"></script>
<?php require_once "footer.php"; ?>

<?php require_once "header.php"; ?>

<section class="gm-card p-4 mb-4">
  <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
    <div>
      <h1 class="h5 fw-bold mb-0" style="color:#4c1d95;"><i class="bi bi-book me-2"></i>Ayuda y Guía Rápida de Uso</h1>
      <small class="text-muted">Manual operativo de GM VENTAS · Imprimible en formato PDF</small>
    </div>
    <button type="button" class="btn btn-gm btn-sm no-print" onclick="window.print()">
      <i class="bi bi-printer me-1"></i>Imprimir / Guardar PDF
    </button>
  </div>

  <div class="row g-3">
    <div class="col-md-6">
      <div class="p-3 rounded-3 border bg-light h-100">
        <h2 class="h6 fw-bold" style="color:#5b21b6;">1. Almacén (Artículos, Categorías y Marcas)</h2>
        <p class="small mb-0">Gestiona categorías, marcas de belleza y productos de salón (SLN-001 a SLN-005) con columna de opciones a la izquierda para editar, activar/desactivar o eliminar.</p>
      </div>
    </div>
    <div class="col-md-6">
      <div class="p-3 rounded-3 border bg-light h-100">
        <h2 class="h6 fw-bold" style="color:#5b21b6;">2. Compras / Ingresos</h2>
        <p class="small mb-0">Registra ingresos de mercadería seleccionando proveedor, comprobante y método de pago. Cada compra incrementa automáticamente el stock en MySQL.</p>
      </div>
    </div>
    <div class="col-md-6">
      <div class="p-3 rounded-3 border bg-light h-100">
        <h2 class="h6 fw-bold" style="color:#5b21b6;">3. Ventas / Punto de Venta (POS)</h2>
        <p class="small mb-0">Emite boletas, facturas o tickets con cálculo automático de IGV (18%), descuento de stock en tiempo real y comprobante imprimible.</p>
      </div>
    </div>
    <div class="col-md-6">
      <div class="p-3 rounded-3 border bg-light h-100">
        <h2 class="h6 fw-bold" style="color:#5b21b6;">4. Cuentas Configuradas</h2>
        <p class="small mb-0">
          <strong>Gladys (Administradora):</strong> usuario <code>gladys</code> · clave <code>123456</code><br>
          <strong>Mery (Vendedora):</strong> usuario <code>mery</code> · clave <code>12345</code>
        </p>
      </div>
    </div>
  </div>
</section>

<?php require_once "footer.php"; ?>

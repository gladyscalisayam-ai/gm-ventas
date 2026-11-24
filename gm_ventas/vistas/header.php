<?php
if (strlen(session_id()) < 1) {
    session_start();
}
if (!isset($_SESSION["nombre"])) {
    header("Location: login.html");
    exit;
}
$paginaActual = basename($_SERVER["PHP_SELF"]);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>GM VENTAS — Sistema de Ventas</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
  <link href="../public/css/purple-theme.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
  <header class="gm-topbar fixed-top px-3 px-md-4 d-flex align-items-center justify-content-between text-white shadow-sm no-print">
    <div class="d-flex align-items-center gap-3">
      <button type="button" id="btnToggleSidebar" class="btn btn-sm text-white border border-white border-opacity-25" aria-label="Alternar menú lateral">
        <i class="bi bi-list fs-5"></i>
      </button>
      <a href="listado.php" class="text-white text-decoration-none fw-bold fs-5 d-flex align-items-center gap-2">
        <span>GM VENTAS</span>
        <i class="bi bi-stars"></i>
      </a>
    </div>

    <div class="dropdown">
      <button class="btn btn-sm text-white border border-white border-opacity-25 rounded-pill px-3 dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="bi bi-person-circle me-1"></i>
        <?php echo htmlspecialchars($_SESSION["nombre"], ENT_QUOTES, "UTF-8"); ?>
      </button>
      <ul class="dropdown-menu dropdown-menu-end shadow-sm">
        <li class="px-3 py-2 small text-muted border-bottom">
          <strong><?php echo htmlspecialchars($_SESSION["cargo"] ?? "", ENT_QUOTES, "UTF-8"); ?></strong><br>
          @<?php echo htmlspecialchars($_SESSION["login"] ?? "", ENT_QUOTES, "UTF-8"); ?>
        </li>
        <li>
          <a class="dropdown-item text-danger fw-semibold" href="../ajax/usuario.php?op=salir">
            <i class="bi bi-box-arrow-right me-1"></i> Cerrar sesión
          </a>
        </li>
      </ul>
    </div>
  </header>

  <div class="d-flex flex-grow-1" style="padding-top: 64px;">
    <nav id="sidebarNav" class="gm-sidebar p-3 flex-shrink-0 no-print" aria-label="Menú principal del sistema">
      <ul class="list-unstyled d-grid gap-1 mb-0">
        <?php if (!empty($_SESSION["escritorio"])): ?>
        <li>
          <a href="listado.php" class="gm-nav-link <?php echo $paginaActual === 'listado.php' ? 'active' : ''; ?>">
            <span><i class="bi bi-house-door me-2 text-primary" style="color:#7c3aed !important;"></i>Escritorio</span>
          </a>
        </li>
        <?php endif; ?>

        <?php if (!empty($_SESSION["almacen"])): ?>
        <?php $enAlmacen = in_array($paginaActual, ["articulo.php", "Categoria.php", "marca.php"]); ?>
        <li>
          <a class="gm-nav-link <?php echo $enAlmacen ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#menuAlmacen" role="button" aria-expanded="<?php echo $enAlmacen ? 'true' : 'false'; ?>">
            <span><i class="bi bi-box-seam me-2" style="color:#7c3aed;"></i>Almacén</span>
            <i class="bi bi-chevron-down small"></i>
          </a>
          <div class="collapse <?php echo $enAlmacen ? 'show' : ''; ?>" id="menuAlmacen">
            <div class="gm-submenu">
              <a href="articulo.php" class="<?php echo $paginaActual === 'articulo.php' ? 'active' : ''; ?>">Artículos</a>
              <a href="Categoria.php" class="<?php echo $paginaActual === 'Categoria.php' ? 'active' : ''; ?>">Categorías</a>
              <a href="marca.php" class="<?php echo $paginaActual === 'marca.php' ? 'active' : ''; ?>">Marcas</a>
            </div>
          </div>
        </li>
        <?php endif; ?>

        <?php if (!empty($_SESSION["compras"])): ?>
        <?php $enCompras = in_array($paginaActual, ["ingreso.php", "proveedor.php"]); ?>
        <li>
          <a class="gm-nav-link <?php echo $enCompras ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#menuCompras" role="button" aria-expanded="<?php echo $enCompras ? 'true' : 'false'; ?>">
            <span><i class="bi bi-truck me-2" style="color:#7c3aed;"></i>Compras</span>
            <i class="bi bi-chevron-down small"></i>
          </a>
          <div class="collapse <?php echo $enCompras ? 'show' : ''; ?>" id="menuCompras">
            <div class="gm-submenu">
              <a href="ingreso.php" class="<?php echo $paginaActual === 'ingreso.php' ? 'active' : ''; ?>">Ingresos</a>
              <a href="proveedor.php" class="<?php echo $paginaActual === 'proveedor.php' ? 'active' : ''; ?>">Proveedores</a>
            </div>
          </div>
        </li>
        <?php endif; ?>

        <?php if (!empty($_SESSION["ventas"])): ?>
        <?php $enVentas = in_array($paginaActual, ["venta.php", "cliente.php"]); ?>
        <li>
          <a class="gm-nav-link <?php echo $enVentas ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#menuVentas" role="button" aria-expanded="<?php echo $enVentas ? 'true' : 'false'; ?>">
            <span><i class="bi bi-cart3 me-2" style="color:#7c3aed;"></i>Ventas</span>
            <i class="bi bi-chevron-down small"></i>
          </a>
          <div class="collapse <?php echo $enVentas ? 'show' : ''; ?>" id="menuVentas">
            <div class="gm-submenu">
              <a href="venta.php" class="<?php echo $paginaActual === 'venta.php' ? 'active' : ''; ?>">Ventas / POS</a>
              <a href="cliente.php" class="<?php echo $paginaActual === 'cliente.php' ? 'active' : ''; ?>">Clientes</a>
            </div>
          </div>
        </li>
        <?php endif; ?>

        <?php if (!empty($_SESSION["acceso"])): ?>
        <?php $enAcceso = in_array($paginaActual, ["usuario.php", "permiso.php"]); ?>
        <li>
          <a class="gm-nav-link <?php echo $enAcceso ? 'active' : ''; ?>" data-bs-toggle="collapse" href="#menuAcceso" role="button" aria-expanded="<?php echo $enAcceso ? 'true' : 'false'; ?>">
            <span><i class="bi bi-shield-lock me-2" style="color:#7c3aed;"></i>Acceso</span>
            <i class="bi bi-chevron-down small"></i>
          </a>
          <div class="collapse <?php echo $enAcceso ? 'show' : ''; ?>" id="menuAcceso">
            <div class="gm-submenu">
              <a href="usuario.php" class="<?php echo $paginaActual === 'usuario.php' ? 'active' : ''; ?>">Usuarios</a>
              <a href="permiso.php" class="<?php echo $paginaActual === 'permiso.php' ? 'active' : ''; ?>">Permisos</a>
            </div>
          </div>
        </li>
        <?php endif; ?>

        <?php if (!empty($_SESSION["consultacompras"])): ?>
        <li>
          <a href="consultacompras.php" class="gm-nav-link <?php echo $paginaActual === 'consultacompras.php' ? 'active' : ''; ?>">
            <span><i class="bi bi-bar-chart-line me-2" style="color:#7c3aed;"></i>Consulta Compras</span>
          </a>
        </li>
        <?php endif; ?>

        <?php if (!empty($_SESSION["consultaventas"])): ?>
        <li>
          <a href="consultaventas.php" class="gm-nav-link <?php echo $paginaActual === 'consultaventas.php' ? 'active' : ''; ?>">
            <span><i class="bi bi-pie-chart me-2" style="color:#7c3aed;"></i>Consulta Ventas</span>
          </a>
        </li>
        <?php endif; ?>

        <li>
          <a href="ayuda.php" class="gm-nav-link <?php echo $paginaActual === 'ayuda.php' ? 'active' : ''; ?>">
            <span><i class="bi bi-question-circle me-2" style="color:#7c3aed;"></i>Ayuda</span>
            <span class="gm-badge">PDF</span>
          </a>
        </li>

        <li>
          <a href="acercade.php" class="gm-nav-link <?php echo $paginaActual === 'acercade.php' ? 'active' : ''; ?>">
            <span><i class="bi bi-info-circle me-2" style="color:#7c3aed;"></i>Acerca de...</span>
            <span class="gm-badge">GM</span>
          </a>
        </li>
      </ul>
    </nav>

    <div class="flex-grow-1 d-flex flex-column min-w-0">
      <main class="flex-grow-1 p-3 p-md-4">

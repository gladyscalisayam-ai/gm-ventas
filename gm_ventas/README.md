# GM VENTAS — Sistema de Inventario y Punto de Venta (Salón de Belleza)

Sistema web desarrollado en **PHP 8 nativo (arquitectura MVC)**, **MySQL (InnoDB)**, **Bootstrap 5**, **jQuery / AJAX**, **DataTables**, **Chart.js** y **Vue.js 3**, listo para ejecutarse en **XAMPP** y editarse en **Visual Studio Code**.

## Credenciales de Acceso
- **Administradora (`gladys`):** Contraseña `123456` — Acceso total a todos los módulos.
- **Vendedora (`mery`):** Contraseña `12345` — Acceso a Escritorio, Almacén y Ventas / POS.

## Instalación Rápida en XAMPP
1. Copia la carpeta `gm_ventas/` dentro de `C:\xampp\htdocs\`.
2. Inicia **Apache** y **MySQL** desde el Panel de Control de XAMPP.
3. Abre **phpMyAdmin** (`http://localhost/phpmyadmin`) e importa `database/gm_ventas.sql`.
4. Verifica las credenciales de conexión en `config/global.php` (`DB_NAME = "gm_ventas"`).
5. Accede en tu navegador a: `http://localhost/gm_ventas/vistas/login.html`.

## Estructura Modular MVC
- `config/`: Constantes de entorno (`global.php`) y conexión MySQLi UTF-8 (`Conexion.php`).
- `database/`: Script SQL completo (`gm_ventas.sql`) con 5 registros de ejemplo por módulo (`SLN-001` a `SLN-005`).
- `modelos/`: Reglas de negocio, transacciones SQL y consultas analíticas.
- `ajax/`: Controladores JSON, endpoints DataTables, `api_articulo.php` y exportación `.sql`/`.json`.
- `vistas/`: Vistas HTML5 semánticas (`<header>`, `<nav>`, `<main>`, `<section>`, `<footer>`) y tema morado accesible.
- `vistas/scripts/`: Scripts jQuery/AJAX y módulo reactivo Vue.js 3 (`vue-stock-manager.js`).

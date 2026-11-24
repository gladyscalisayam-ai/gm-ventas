      </main>
      <footer class="gm-footer px-4 py-3 d-flex flex-wrap justify-content-between align-items-center gap-2 no-print">
        <div>
          <i class="bi bi-stars" style="color:#7c3aed;"></i>
          <strong style="color:#6d28d9;">GM VENTAS</strong> | Sistema de Gestión de Ventas · Productos de Salón
        </div>
        <span class="fw-bold" style="color:#4c1d95;">v. GM VENTAS 2.6</span>
      </footer>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
  <script>
    document.getElementById('btnToggleSidebar')?.addEventListener('click', function () {
      var nav = document.getElementById('sidebarNav');
      if (window.innerWidth < 992) {
        nav.classList.toggle('show');
      } else {
        nav.classList.toggle('d-none');
      }
    });
  </script>
</body>
</html>

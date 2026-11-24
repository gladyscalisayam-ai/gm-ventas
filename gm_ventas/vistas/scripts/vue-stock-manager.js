document.addEventListener('DOMContentLoaded', function () {
  const { createApp } = Vue;

  const StockCard = {
    props: ['item'],
    emits: ['ajustar'],
    template: `
      <div class="border rounded-3 p-3 h-100 bg-white shadow-sm">
        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
          <div>
            <span class="badge" style="background:#f3e8ff; color:#4c1d95;">{{ item.codigo }}</span>
            <h3 class="h6 fw-bold mt-1 mb-0">{{ item.nombre }}</h3>
            <small class="text-muted">{{ item.categoria }} · {{ item.marca }}</small>
          </div>
          <span class="fw-bold" style="color:#6d28d9;">S/ {{ item.precio_venta.toFixed(2) }}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center pt-2 border-top">
          <span class="small">
            Stock:
            <strong :class="item.stock <= 10 ? 'text-danger' : 'text-success'">{{ item.stock }} und.</strong>
          </span>
          <div class="btn-group btn-group-sm">
            <button type="button" class="btn btn-outline-secondary" @click="$emit('ajustar', item.idarticulo, -1)">-1</button>
            <button type="button" class="btn btn-gm" @click="$emit('ajustar', item.idarticulo, 1)">+1</button>
          </div>
        </div>
      </div>
    `,
  };

  createApp({
    components: { StockCard },
    data() {
      return {
        articulos: [],
        busqueda: '',
      };
    },
    computed: {
      articulosFiltrados() {
        const q = this.busqueda.trim().toLowerCase();
        if (!q) return this.articulos;
        return this.articulos.filter(
          (a) =>
            a.nombre.toLowerCase().includes(q) ||
            a.codigo.toLowerCase().includes(q) ||
            a.marca.toLowerCase().includes(q)
        );
      },
    },
    mounted() {
      this.cargarDashboard();
    },
    methods: {
      async cargarDashboard() {
        const resp = await fetch('../ajax/api_articulo.php?op=dashboard');
        const data = await resp.json();
        this.articulos = data.articulos || [];

        if (data.kpis) {
          document.getElementById('kpiProductos').textContent = data.kpis.productos;
          document.getElementById('kpiCategorias').textContent = data.kpis.categorias;
          document.getElementById('kpiClientes').textContent = data.kpis.clientes;
          document.getElementById('kpiVentas').textContent = data.kpis.ventas;
        }

        this.renderCharts(this.articulos, data.categorias_chart || []);
      },
      async ajustarStock(idarticulo, delta) {
        const formData = new FormData();
        formData.append('idarticulo', idarticulo);
        formData.append('delta', delta);
        const resp = await fetch('../ajax/api_articulo.php?op=ajustar_stock', {
          method: 'POST',
          body: formData,
        });
        const res = await resp.json();
        if (res.ok) {
          const item = this.articulos.find((a) => a.idarticulo === idarticulo);
          if (item) item.stock = res.stock;
        }
      },
      renderCharts(articulos, categoriasChart) {
        const ctxStock = document.getElementById('chartStockProductos');
        if (ctxStock) {
          new Chart(ctxStock, {
            type: 'bar',
            data: {
              labels: articulos.map((a) => a.codigo),
              datasets: [
                {
                  label: 'Unidades en Stock',
                  data: articulos.map((a) => a.stock),
                  backgroundColor: '#7c3aed',
                  borderRadius: 8,
                },
              ],
            },
            options: { responsive: true, plugins: { legend: { display: false } } },
          });
        }

        const ctxCat = document.getElementById('chartCategorias');
        if (ctxCat) {
          new Chart(ctxCat, {
            type: 'doughnut',
            data: {
              labels: categoriasChart.map((c) => c.categoria),
              datasets: [
                {
                  data: categoriasChart.map((c) => c.total),
                  backgroundColor: ['#7c3aed', '#9333ea', '#a855f7', '#c084fc', '#5b21b6'],
                },
              ],
            },
            options: { responsive: true },
          });
        }
      },
    },
  }).mount('#vueStockApp');
});

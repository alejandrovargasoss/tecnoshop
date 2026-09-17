  <?php
// Dashboard principal — Chart.js + IA + Últimas ventas
$totalProductos = $conexion->query("SELECT COUNT(*) AS t FROM productos WHERE estado='activo'")->fetch_assoc()['t'] ?? 0;
$totalClientes  = $conexion->query("SELECT COUNT(*) AS t FROM clientes  WHERE estado='activo'")->fetch_assoc()['t'] ?? 0;
$totalVentas    = $conexion->query("SELECT COUNT(*) AS t FROM ventas WHERE MONTH(fecha)=MONTH(NOW())")->fetch_assoc()['t'] ?? 0;
$ingresosMes    = $conexion->query("SELECT COALESCE(SUM(total),0) AS t FROM ventas WHERE MONTH(fecha)=MONTH(NOW()) AND estado='completada'")->fetch_assoc()['t'] ?? 0;
$stockBajo      = $conexion->query("SELECT COUNT(*) AS t FROM productos WHERE stock < 5 AND estado='activo'")->fetch_assoc()['t'] ?? 0;
$ultimasVentas  = $conexion->query(
    "SELECT v.id,v.fecha,v.total,v.estado,CONCAT(c.nombre,' ',c.apellido) AS cliente
     FROM ventas v LEFT JOIN clientes c ON v.id_cliente=c.id
     ORDER BY v.fecha DESC LIMIT 5"
)->fetch_all(MYSQLI_ASSOC);
?>

<!-- TARJETAS RESUMEN -->
<div class="stats-grid">
  <div class="stat-card">
    <div class="stat-icon">📦</div>
    <div class="stat-info"><p>Productos activos</p><h3><?= $totalProductos ?></h3></div>
  </div>
  <div class="stat-card azul">
    <div class="stat-icon">👥</div>
    <div class="stat-info"><p>Total Clientes</p><h3><?= $totalClientes ?></h3></div>
  </div>
  <div class="stat-card morado">
    <div class="stat-icon">🧾</div>
    <div class="stat-info"><p>Ventas del Mes</p><h3><?= $totalVentas ?></h3></div>
  </div>
  <div class="stat-card naranja">
    <div class="stat-icon">💰</div>
    <div class="stat-info"><p>Ingresos del Mes</p><h3>$<?= number_format($ingresosMes,0,',','.') ?></h3></div>
  </div>
  <div class="stat-card rojo">
    <div class="stat-icon">⚠️</div>
    <div class="stat-info"><p>Stock bajo (&lt;5)</p><h3><?= $stockBajo ?></h3></div>
  </div>
</div>

<!-- IA — RESUMEN INTELIGENTE -->
<div class="card" x-data="resumenIAApp()" x-init="cargar()">
  <div class="card-header">
    <h2>🤖 Análisis inteligente del negocio — IA</h2>
    <button class="btn btn-secondary btn-sm" @click="cargar()">🔄 Actualizar</button>
  </div>
  <div class="card-body">
    <div x-show="cargando" class="spinner-wrap"><div class="spinner"></div> Analizando...</div>
    <template x-if="!cargando && datos">
      <div class="ia-grid">
        <div class="ia-card">
          <p>Ventas este mes</p>
          <h3 x-text="'$' + Number(datos.ventas_mes).toLocaleString('es-CO')"></h3>
        </div>
        <div class="ia-card">
          <p>Tendencia vs mes anterior</p>
          <h3 :style="datos.crecimiento_pct>=0?'color:#39A900':'color:#ef4444'"
              x-text="datos.emoji + ' ' + datos.crecimiento_pct + '%'"></h3>
        </div>
        <div class="ia-card consejo">
          <p><strong>💡 Consejo IA:</strong></p>
          <p x-text="datos.consejo_ia"></p>
        </div>
      </div>
    </template>
  </div>
</div>

<!-- GRÁFICAS CHART.JS -->
<div class="charts-grid">
  <div class="card">
    <div class="card-header"><h2>📊 Ventas por mes — <?= date('Y') ?></h2></div>
    <div class="card-body"><canvas id="graficaVentasMes" height="200"></canvas></div>
  </div>
  <div class="card">
    <div class="card-header"><h2>🍩 Ventas por categoría</h2></div>
    <div class="card-body"><canvas id="graficaCategoria" height="200"></canvas></div>
  </div>
</div>

<!-- IA — PREDICCIÓN AGOTAMIENTO -->
<div class="card" x-data="prediccionApp()" x-init="cargar()">
  <div class="card-header">
    <h2>🤖 Predicción de agotamiento — IA</h2>
    <span class="badge-info">Últimos 30 días</span>
  </div>
  <div class="card-body">
    <div x-show="cargando" class="spinner-wrap"><div class="spinner"></div> Calculando...</div>
    <template x-if="!cargando && predicciones.length === 0">
      <div class="empty-state"><p>✅ No hay productos en riesgo de agotamiento.</p></div>
    </template>
    <div class="table-responsive" x-show="!cargando && predicciones.length > 0">
      <table>
        <thead><tr><th>Producto</th><th>Stock</th><th>Ventas 30d</th><th>Días restantes</th><th>Alerta IA</th></tr></thead>
        <tbody>
          <template x-for="p in predicciones" :key="p.id">
            <tr>
              <td><strong x-text="p.nombre"></strong></td>
              <td x-text="p.stock + ' uds.'"></td>
              <td x-text="p.ventas_30d + ' vendidos'"></td>
              <td><strong x-text="p.dias_restantes + ' días'"></strong></td>
              <td><span :class="'badge badge-' + p.color" x-text="'🤖 ' + p.alerta"></span></td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </div>
</div>

<!-- ÚLTIMAS VENTAS -->
<div class="card">
  <div class="card-header">
    <h2>🧾 Últimas 5 ventas</h2>
    <a href="/tecnoshop/?modulo=ventas&accion=listar" class="btn btn-secondary btn-sm">Ver todas</a>
  </div>
  <div class="card-body">
    <?php if (empty($ultimasVentas)): ?>
      <div class="empty-state"><p>No hay ventas registradas aún.</p></div>
    <?php else: ?>
    <div class="table-responsive">
      <table>
        <thead><tr><th>#</th><th>Cliente</th><th>Fecha</th><th>Total</th><th>Estado</th></tr></thead>
        <tbody>
          <?php foreach ($ultimasVentas as $v): ?>
          <tr>
            <td><strong>#<?= $v['id'] ?></strong></td>
            <td><?= htmlspecialchars($v['cliente'] ?? '—') ?></td>
            <td><?= date('d/m/Y H:i', strtotime($v['fecha'])) ?></td>
            <td><strong>$<?= number_format($v['total'],0,',','.') ?></strong></td>
            <td><span class="badge <?= $v['estado']==='completada'?'badge-success':($v['estado']==='pendiente'?'badge-warning':'badge-danger') ?>"><?= ucfirst($v['estado']) ?></span></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    <?php endif; ?>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
  try {
    const r1  = await fetch('/tecnoshop/?modulo=reportes&accion=ventasPorMes');
    const j1  = await r1.json();
    new Chart(document.getElementById('graficaVentasMes'), {
      type: 'bar',
      data: {
        labels: j1.datos.map(d => d.mes),
        datasets: [{ label: 'Ventas (COP)',
          data: j1.datos.map(d => Number(d.total)),
          backgroundColor: ['rgba(57,169,0,0.85)','rgba(59,130,246,0.85)',
            'rgba(168,85,247,0.85)','rgba(249,115,22,0.85)',
            'rgba(15,118,110,0.85)','rgba(236,72,153,0.85)',
            'rgba(245,158,11,0.85)','rgba(34,211,238,0.85)'],
          borderRadius: 8, borderWidth: 0 }]
      },
      options: { responsive:true, plugins:{legend:{display:false}},
        scales:{y:{ticks:{callback:v=>'$'+(v/1000).toFixed(0)+'K'}}} }
    });
  } catch(e) { console.log('Sin datos ventas por mes'); }

  try {
    const r2  = await fetch('/tecnoshop/?modulo=reportes&accion=ventasPorCategoria');
    const j2  = await r2.json();
    new Chart(document.getElementById('graficaCategoria'), {
      type: 'doughnut',
      data: {
        labels: j2.datos.map(d => d.categoria || 'Sin categoría'),
        datasets: [{ data: j2.datos.map(d => Number(d.total)),
          backgroundColor: ['rgba(57,169,0,0.88)','rgba(59,130,246,0.88)',
                            'rgba(168,85,247,0.88)','rgba(249,115,22,0.88)'],
          borderWidth: 2, borderColor: '#ffffff' }]
      },
      options: { responsive:true, plugins:{legend:{position:'bottom'}} }
    });
  } catch(e) { console.log('Sin datos por categoría'); }
});
</script>

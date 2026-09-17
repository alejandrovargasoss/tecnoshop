<?php require_once __DIR__ . '/../../vistas/layout/header.php'; ?>
<div class="card" x-data="ventasApp()">
  <template x-if="mensaje">
    <div :class="`alert alert-${tipoMsg}`" x-text="mensaje"></div>
  </template>
  <div class="card-header">
    <h2>🧾 Lista de Ventas</h2>
    <button class="btn btn-primary" @click="abrirFormulario()"><i class="bi bi-plus-lg"></i> Nueva Venta</button>
  </div>
  <div class="card-body">
    <div x-show="cargando" class="spinner-wrap"><div class="spinner"></div> Cargando...</div>
    <div x-show="!cargando" class="table-responsive">
      <table>
        <thead><tr><th>#</th><th>Cliente</th><th>Fecha</th><th>Total</th><th>Estado</th><th>Acciones</th></tr></thead>
        <tbody>
          <template x-for="v in ventas" :key="v.id">
            <tr>
              <td><strong x-text="'#'+v.id"></strong></td>
              <td x-text="v.cliente || '—'"></td>
              <td x-text="v.fecha"></td>
              <td><strong x-text="'$'+Number(v.total).toLocaleString('es-CO')"></strong></td>
              <td>
                <span :class="v.estado==='completada'?'badge badge-success':v.estado==='pendiente'?'badge badge-warning':'badge badge-danger'"
                      x-text="v.estado"></span>
              </td>
              <td>
                <button x-show="v.estado!=='cancelada'" class="btn btn-danger btn-sm"
                        @click="cancelar(v.id)">Cancelar</button>
              </td>
            </tr>
          </template>
        </tbody>
      </table>
    </div>
  </div>

  <!-- Modal nueva venta -->
  <div class="modal-overlay" x-show="mostrarModal" x-cloak>
    <div class="modal">
      <div class="modal-header">
        <h3>➕ Nueva Venta</h3>
        <button class="btn-close" @click="cerrarModal()">✕</button>
      </div>
      <div class="modal-body">
        <div class="form-group"><label>ID Cliente *</label><input type="number" x-model="form.id_cliente" placeholder="Número ID del cliente"></div>
        <div class="form-group"><label>Total</label><input type="number" x-model="form.total" placeholder="0"></div>
        <div class="form-group"><label>Estado</label>
          <select x-model="form.estado"><option value="pendiente">Pendiente</option><option value="completada">Completada</option></select>
        </div>
        <div class="form-group"><label>Observación</label><textarea x-model="form.observacion" rows="2"></textarea></div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" @click="cerrarModal()">Cancelar</button>
        <button class="btn btn-primary" @click="guardar()">Registrar Venta</button>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../../vistas/layout/footer.php'; ?>

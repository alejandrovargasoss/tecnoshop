/**
 * app.js — Funciones Alpine.js TecnoShop
 * CRUD + IA + Reportes
 * TecnoShop · SENA ADSO · Juan Carlos Huertas Niño · 2026
 */

const API = '/tecnoshop/index.php';

/** Helper para mostrar alertas */
function crearAlerta(ctx, mensaje, tipo = 'success') {
  ctx.mensaje  = mensaje;
  ctx.tipoMsg  = tipo;
  setTimeout(() => { ctx.mensaje = ''; }, 4000);
}

// ══════════════════════════════════════════════════════
// PRODUCTOS
// ══════════════════════════════════════════════════════
function productosApp() {
  return {
    productos:    [],
    categorias:   [],
    cargando:     true,
    mostrarModal: false,
    modoEdicion:  false,
    mensaje:      '',
    tipoMsg:      'success',
    form: { id:null, nombre:'', descripcion:'', precio:'',
            stock:'', marca:'', categoria_id:'' },

    async init()   { await this.cargar(); await this.cargarCategorias(); },

    async cargar() {
      this.cargando = true;
      try {
        const res  = await fetch(`${API}?modulo=productos&accion=obtenerTodos`);
        const json = await res.json();
        this.productos = json.ok ? json.datos : [];
      } catch(e) { crearAlerta(this, 'Error de conexión.', 'danger'); }
      finally { this.cargando = false; }
    },

    async cargarCategorias() {
      const res  = await fetch(`${API}?modulo=productos&accion=categorias`);
      const json = await res.json();
      this.categorias = json.ok ? json.datos : [];
    },

    abrirFormulario() {
      this.modoEdicion = false;
      this.form = { id:null, nombre:'', descripcion:'', precio:'',
                    stock:'', marca:'', categoria_id:'' };
      this.mostrarModal = true;
    },

    editar(p) {
      this.modoEdicion  = true;
      this.form         = { ...p };
      this.mostrarModal = true;
    },

    cerrarModal() { this.mostrarModal = false; },

    async guardar() {
      if (!this.form.nombre) {
        crearAlerta(this, 'El nombre es obligatorio.', 'warning'); return;
      }
      if (this.form.precio <= 0) {
        crearAlerta(this, 'El precio debe ser mayor a 0.', 'warning'); return;
      }
      const accion = this.modoEdicion ? 'actualizar' : 'guardar';
      try {
        const res  = await fetch(`${API}?modulo=productos&accion=${accion}`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(this.form),
        });
        const json = await res.json();
        crearAlerta(this, json.mensaje, json.ok ? 'success' : 'danger');
        if (json.ok) { this.cerrarModal(); await this.cargar(); }
      } catch(e) { crearAlerta(this, 'Error al guardar.', 'danger'); }
    },

    async eliminar(id) {
      if (!confirm('¿Eliminar este producto?')) return;
      try {
        const res  = await fetch(`${API}?modulo=productos&accion=eliminar&id=${id}`,
                                 { method: 'DELETE' });
        const json = await res.json();
        crearAlerta(this, json.mensaje, json.ok ? 'success' : 'danger');
        if (json.ok) await this.cargar();
      } catch(e) { crearAlerta(this, 'Error al eliminar.', 'danger'); }
    },
  };
}

// ══════════════════════════════════════════════════════
// CLIENTES con IA
// ══════════════════════════════════════════════════════
function clientesApp() {
  return {
    clientes:            [],
    cargando:            true,
    mostrarModal:        false,
    mostrarIA:           false,
    cargandoIA:          false,
    sugerencias:         [],
    clienteSeleccionado: null,
    modoEdicion:         false,
    mensaje:             '',
    tipoMsg:             'success',
    form: { id:null, nombre:'', apellido:'', documento:'',
            telefono:'', email:'', direccion:'', estado:'activo' },

    async init()   { await this.cargar(); },

    async cargar() {
      this.cargando = true;
      try {
        const res  = await fetch(`${API}?modulo=clientes&accion=obtenerTodos`);
        const json = await res.json();
        this.clientes = json.ok ? json.datos : [];
      } catch(e) { crearAlerta(this, 'Error de conexión.', 'danger'); }
      finally { this.cargando = false; }
    },

    abrirFormulario() {
      this.modoEdicion = false;
      this.form = { id:null, nombre:'', apellido:'', documento:'',
                    telefono:'', email:'', direccion:'', estado:'activo' };
      this.mostrarModal = true;
    },

    editar(c)     { this.modoEdicion=true; this.form={...c}; this.mostrarModal=true; },
    cerrarModal() { this.mostrarModal = false; },

    async guardar() {
      if (!this.form.nombre || !this.form.apellido || !this.form.documento) {
        crearAlerta(this, 'Nombre, apellido y documento son obligatorios.', 'warning');
        return;
      }
      const accion = this.modoEdicion ? 'actualizar' : 'guardar';
      try {
        const res  = await fetch(`${API}?modulo=clientes&accion=${accion}`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(this.form),
        });
        const json = await res.json();
        crearAlerta(this, json.mensaje, json.ok ? 'success' : 'danger');
        if (json.ok) { this.cerrarModal(); await this.cargar(); }
      } catch(e) { crearAlerta(this, 'Error al guardar.', 'danger'); }
    },

    async eliminar(id) {
      if (!confirm('¿Eliminar este cliente?')) return;
      try {
        const res  = await fetch(`${API}?modulo=clientes&accion=eliminar&id=${id}`,
                                 { method: 'DELETE' });
        const json = await res.json();
        crearAlerta(this, json.mensaje, json.ok ? 'success' : 'danger');
        if (json.ok) await this.cargar();
      } catch(e) { crearAlerta(this, 'Error al eliminar.', 'danger'); }
    },

    // 🤖 Sugerencias IA para un cliente
    async verSugerenciasIA(cliente) {
      this.clienteSeleccionado = cliente;
      this.sugerencias         = [];
      this.cargandoIA          = true;
      this.mostrarIA           = true;
      try {
        const res  = await fetch(`${API}?modulo=ia&accion=sugerirProductos&id=${cliente.id}`);
        const json = await res.json();
        this.sugerencias = json.datos?.sugerencias ?? [];
      } catch(e) {
        this.sugerencias = ['Cable USB-C certificado', 'Funda protectora premium', 'Cargador rápido 65W'];
      } finally { this.cargandoIA = false; }
    },
  };
}

// ══════════════════════════════════════════════════════
// VENTAS
// ══════════════════════════════════════════════════════
function ventasApp() {
  return {
    ventas:       [],
    cargando:     true,
    mostrarModal: false,
    mensaje:      '',
    tipoMsg:      'success',
    form: { id_cliente:'', total:'', estado:'pendiente', observacion:'' },

    async init()   { await this.cargar(); },

    async cargar() {
      this.cargando = true;
      try {
        const res  = await fetch(`${API}?modulo=ventas&accion=obtenerTodos`);
        const json = await res.json();
        this.ventas  = json.ok ? json.datos : [];
      } catch(e) { crearAlerta(this, 'Error de conexión.', 'danger'); }
      finally { this.cargando = false; }
    },

    abrirFormulario() {
      this.form = { id_cliente:'', total:'', estado:'pendiente', observacion:'' };
      this.mostrarModal = true;
    },

    cerrarModal() { this.mostrarModal = false; },

    async guardar() {
      if (!this.form.id_cliente) {
        crearAlerta(this, 'El ID de cliente es obligatorio.', 'warning'); return;
      }
      try {
        const res  = await fetch(`${API}?modulo=ventas&accion=guardar`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(this.form),
        });
        const json = await res.json();
        crearAlerta(this, json.mensaje, json.ok ? 'success' : 'danger');
        if (json.ok) { this.cerrarModal(); await this.cargar(); }
      } catch(e) { crearAlerta(this, 'Error al registrar venta.', 'danger'); }
    },

    async cancelar(id) {
      if (!confirm('¿Cancelar esta venta?')) return;
      try {
        const res  = await fetch(`${API}?modulo=ventas&accion=cancelar&id=${id}`,
                                 { method: 'DELETE' });
        const json = await res.json();
        crearAlerta(this, json.mensaje, json.ok ? 'success' : 'danger');
        if (json.ok) await this.cargar();
      } catch(e) { crearAlerta(this, 'Error al cancelar.', 'danger'); }
    },
  };
}

// ══════════════════════════════════════════════════════
// REPORTES
// ══════════════════════════════════════════════════════
function reportesApp() {
  return {
    topProductos: [],
    stockBajo:    [],
    cargando:     true,

    async init() {
      this.cargando = true;
      try {
        const [r1, r2] = await Promise.all([
          fetch(`${API}?modulo=reportes&accion=topProductos`).then(r => r.json()),
          fetch(`${API}?modulo=reportes&accion=stockBajo`).then(r => r.json()),
        ]);
        this.topProductos = r1.ok ? r1.datos : [];
        this.stockBajo    = r2.ok ? r2.datos : [];
      } catch(e) { console.error('Error reportes:', e); }
      finally { this.cargando = false; }
    },
  };
}

// ══════════════════════════════════════════════════════
// IA — RESUMEN INTELIGENTE
// ══════════════════════════════════════════════════════
function resumenIAApp() {
  return {
    datos:    null,
    cargando: true,
    async cargar() {
      this.cargando = true;
      try {
        const res  = await fetch(`${API}?modulo=ia&accion=resumenNegocio`);
        const json = await res.json();
        this.datos = json.ok ? json.datos : null;
      } catch(e) { console.error('IA no disponible:', e); }
      finally { this.cargando = false; }
    },
  };
}

// ══════════════════════════════════════════════════════
// IA — PREDICCIÓN DE AGOTAMIENTO
// ══════════════════════════════════════════════════════
function prediccionApp() {
  return {
    predicciones: [],
    cargando:     true,
    async cargar() {
      this.cargando = true;
      try {
        const res  = await fetch(`${API}?modulo=ia&accion=predecirAgotamiento`);
        const json = await res.json();
        this.predicciones = json.ok ? json.datos : [];
      } catch(e) { console.error('Error predicción:', e); }
      finally { this.cargando = false; }
    },
  };
}

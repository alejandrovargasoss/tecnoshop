<?php $moduloActivo = $_GET['modulo'] ?? 'dashboard'; ?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>TecnoShop — Sistema de Gestión Integral</title>
  <link rel="stylesheet" href="/tecnoshop/publico/css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body>
<aside class="sidebar">
  <div class="sidebar-logo">
    <h2>📱 TecnoShop</h2>
    <span class="ia-badge">✨ Con IA + Dashboards</span>
    <span>Sistema de Gestión · SENA ADSO</span>
  </div>
  <nav>
    <a href="/tecnoshop/" class="<?= $moduloActivo==='dashboard'?'active':'' ?>">
      <i class="bi bi-speedometer2"></i> Dashboard + IA
    </a>
    <a href="/tecnoshop/?modulo=productos&accion=listar" class="<?= $moduloActivo==='productos'?'active':'' ?>">
      <i class="bi bi-box-seam"></i> Productos
    </a>
    <a href="/tecnoshop/?modulo=clientes&accion=listar" class="<?= $moduloActivo==='clientes'?'active':'' ?>">
      <i class="bi bi-people"></i> Clientes 🤖
    </a>
    <a href="/tecnoshop/?modulo=ventas&accion=listar" class="<?= $moduloActivo==='ventas'?'active':'' ?>">
      <i class="bi bi-receipt"></i> Ventas
    </a>
    <a href="/tecnoshop/?modulo=reportes&accion=dashboard" class="<?= $moduloActivo==='reportes'?'active':'' ?>">
      <i class="bi bi-bar-chart-line"></i> Reportes
    </a>
  </nav>
  <div class="sidebar-footer">
    <strong style="color:#86EFAC;">🤖 IA: Gemini API</strong><br>
    <strong style="color:rgba(255,255,255,0.6)">SENA · ADSO 2026</strong><br>
    GA6→GA8 · Sesiones 1-11
  </div>
</aside>
<div class="main-wrapper">
  <header class="topbar">
    <h1>
      <?php
      $titulos = [
        'dashboard'=>'📊 Dashboard + IA',
        'productos'=>'📦 Gestión de Productos',
        'clientes' =>'👥 Clientes + IA 🤖',
        'ventas'   =>'🧾 Gestión de Ventas',
        'reportes' =>'📈 Reportes del Sistema',
      ];
      echo $titulos[$moduloActivo] ?? 'TecnoShop';
      ?>
    </h1>
    <div class="topbar-right">
      <span class="badge-ia">🤖 IA Activa</span>
      <span class="badge-sena">SENA ADSO</span>
      <span class="topbar-user"><i class="bi bi-person-circle"></i> Instructor</span>
    </div>
  </header>
  <main class="content">

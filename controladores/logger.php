<?php
// Logger.php — Sistema de logs para TecnoShop
class Logger {

    // En Railway usar error_log() que va al panel de Logs
    public static function log($nivel, $mensaje, $datos = []) {
        $fecha     = date('Y-m-d H:i:s');
        $ip        = $_SERVER['REMOTE_ADDR'] ?? 'CLI';
        $datos_str = empty($datos) ? '' : ' | ' . json_encode($datos);
        $linea     = "[{$fecha}] [{$nivel}] [{$ip}] {$mensaje}{$datos_str}";

        // error_log envía al panel de Logs de Railway
        error_log($linea);
    }

    public static function info($msg, $d=[])  { self::log('INFO',  $msg, $d); }
    public static function error($msg, $d=[]) { self::log('ERROR', $msg, $d); }
    public static function warn($msg, $d=[])  { self::log('WARN',  $msg, $d); }
}
<?php

namespace Database\Seeders;

use App\Models\Module;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    public function run(): void
    {
        $modules = [
            ['id' => 'dashboard', 'name' => 'Dashboard / Resumen', 'description' => 'Panel principal con resumen de flota'],
            ['id' => 'vehicles', 'name' => 'Vehículos', 'description' => 'Gestión de vehículos de la flota'],
            ['id' => 'maintenance', 'name' => 'Mantenimiento', 'description' => 'Control de mantenimiento preventivo y correctivo'],
            ['id' => 'fuel', 'name' => 'Combustible', 'description' => 'Registro y control de combustible'],
            ['id' => 'routes', 'name' => 'Rutas', 'description' => 'Administración de rutas y viajes'],
            ['id' => 'geolocation', 'name' => 'Geolocalización', 'description' => 'Mapa y seguimiento en tiempo real'],
            ['id' => 'calendar', 'name' => 'Calendario', 'description' => 'Calendario de eventos y programación'],
            ['id' => 'operators', 'name' => 'Operadores', 'description' => 'Gestión de operadores y choferes'],
            ['id' => 'incidents', 'name' => 'Incidencias', 'description' => 'Registro de incidentes y reportes'],
            ['id' => 'evidence', 'name' => 'Evidencias', 'description' => 'Galería de evidencias fotográficas'],
            ['id' => 'gate', 'name' => 'Portería', 'description' => 'Registro de entrada y salida de unidades'],
            ['id' => 'bitacora', 'name' => 'Bitácora', 'description' => 'Bitácora de entradas y salidas de portería'],
            ['id' => 'reports', 'name' => 'Reportes', 'description' => 'Reportes y estadísticas del sistema'],
            ['id' => 'users', 'name' => 'Usuarios', 'description' => 'Administración de usuarios del sistema'],
            ['id' => 'security', 'name' => 'Seguridad OWASP', 'description' => 'Panel de seguridad y vulnerabilidades'],
            ['id' => 'catalogs', 'name' => 'Catálogos', 'description' => 'Catálogos generales del sistema'],
            ['id' => 'notifications', 'name' => 'Notificaciones', 'description' => 'Centro de notificaciones'],
            ['id' => 'expenses', 'name' => 'Gastos', 'description' => 'Control de gastos operativos de la flota'],
        ];

        foreach ($modules as $module) {
            Module::updateOrCreate(['id' => $module['id']], $module);
        }
    }
}

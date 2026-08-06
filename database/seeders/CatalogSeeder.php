<?php

namespace Database\Seeders;

use App\Domains\Catalog\Models\Catalog;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $catalogs = [
            // Vehículos
            ['group' => 'TipoCombustible', 'value' => 'Gasolina', 'label' => 'Gasolina'],
            ['group' => 'TipoCombustible', 'value' => 'Diesel', 'label' => 'Diesel'],
            ['group' => 'TipoCombustible', 'value' => 'Gas', 'label' => 'Gas LP'],
            ['group' => 'GPSInstalado', 'value' => '1', 'label' => 'Instalado (Activo)'],
            ['group' => 'GPSInstalado', 'value' => '0', 'label' => 'No instalado'],
            // Operadores
            ['group' => 'TipoLicencia', 'value' => 'Chofer A', 'label' => 'Chofer A'],
            ['group' => 'TipoLicencia', 'value' => 'Chofer B', 'label' => 'Chofer B'],
            ['group' => 'TipoLicencia', 'value' => 'Chofer C', 'label' => 'Chofer C'],
            ['group' => 'EstatusOperador', 'value' => 'Activo', 'label' => 'Activo'],
            ['group' => 'EstatusOperador', 'value' => 'Inactivo', 'label' => 'Inactivo'],
            ['group' => 'EstatusOperador', 'value' => 'Suspendido', 'label' => 'Suspendido'],
            // Rutas
            ['group' => 'EstatusRuta', 'value' => 'Programada', 'label' => 'Programada'],
            ['group' => 'EstatusRuta', 'value' => 'Asignada', 'label' => 'Asignada'],
            ['group' => 'EstatusRuta', 'value' => 'En tránsito', 'label' => 'En Tránsito'],
            ['group' => 'EstatusRuta', 'value' => 'Instalando', 'label' => 'Instalando'],
            ['group' => 'EstatusRuta', 'value' => 'Completada', 'label' => 'Completada'],
            ['group' => 'EstatusRuta', 'value' => 'Cancelada', 'label' => 'Cancelada'],
            // Mantenimiento
            ['group' => 'TipoMantenimiento', 'value' => 'Preventivo', 'label' => 'Preventivo (Afinación, Llantas, Balatas)'],
            ['group' => 'TipoMantenimiento', 'value' => 'Correctivo', 'label' => 'Correctivo (Reparación mecánica, Falla)'],
            // Combustible
            ['group' => 'NivelCombustible', 'value' => '100', 'label' => '100% (Lleno)'],
            ['group' => 'NivelCombustible', 'value' => '80', 'label' => '80%'],
            ['group' => 'NivelCombustible', 'value' => '60', 'label' => '60%'],
            ['group' => 'NivelCombustible', 'value' => '40', 'label' => '40%'],
            ['group' => 'NivelCombustible', 'value' => '20', 'label' => '20% (Reserva)'],
            ['group' => 'MetodoPago', 'value' => 'Tarjeta', 'label' => 'Tarjeta'],
            ['group' => 'MetodoPago', 'value' => 'Efectivo', 'label' => 'Efectivo'],
            ['group' => 'MetodoPago', 'value' => 'Vales', 'label' => 'Vales'],
            // Bitácora
            ['group' => 'MovimientoExtra', 'value' => 'Desviación', 'label' => 'Desviación'],
            ['group' => 'MovimientoExtra', 'value' => 'Parada no programada', 'label' => 'Parada no programada'],
            ['group' => 'MovimientoExtra', 'value' => 'Espera prolongada', 'label' => 'Espera prolongada'],
            ['group' => 'MovimientoExtra', 'value' => 'Otro', 'label' => 'Otro'],
            ['group' => 'SeveridadIncidencia', 'value' => 'Baja', 'label' => 'Baja (No compromete ruta)'],
            ['group' => 'SeveridadIncidencia', 'value' => 'Media', 'label' => 'Media (Afecta horario)'],
            ['group' => 'SeveridadIncidencia', 'value' => 'Alta', 'label' => 'Alta (Accidente / Grúa)'],
            ['group' => 'SeveridadIncidencia', 'value' => 'Crítica', 'label' => 'Crítica (Urgente)'],
            // Usuarios
            ['group' => 'EstadoUsuario', 'value' => 'Activo', 'label' => 'Activo'],
            ['group' => 'EstadoUsuario', 'value' => 'Inactivo', 'label' => 'Inactivo'],
            // Roles
            ['group' => 'RolUsuario', 'value' => 'Administrador del sistema', 'label' => 'Administrador del sistema'],
            ['group' => 'RolUsuario', 'value' => 'Dirección', 'label' => 'Dirección'],
            ['group' => 'RolUsuario', 'value' => 'Calidad', 'label' => 'Calidad'],
            ['group' => 'RolUsuario', 'value' => 'Logística', 'label' => 'Logística'],
            ['group' => 'RolUsuario', 'value' => 'Jefe de Distribución', 'label' => 'Jefe de Distribución'],
            ['group' => 'RolUsuario', 'value' => 'Chofer', 'label' => 'Chofer'],
            ['group' => 'RolUsuario', 'value' => 'Instalador', 'label' => 'Instalador'],
            ['group' => 'RolUsuario', 'value' => 'Operador', 'label' => 'Operador'],
            // Vehículos
            ['group' => 'CapacidadCarga', 'value' => '1.0 Toneladas', 'label' => '1.0 Toneladas'],
            ['group' => 'CapacidadCarga', 'value' => '1.2 Toneladas', 'label' => '1.2 Toneladas'],
            ['group' => 'CapacidadCarga', 'value' => '1.8 Toneladas', 'label' => '1.8 Toneladas'],
            ['group' => 'CapacidadCarga', 'value' => '2.5 Toneladas', 'label' => '2.5 Toneladas'],
            // Documentos
            ['group' => 'TipoDocumento', 'value' => 'INE', 'label' => 'INE'],
            ['group' => 'TipoDocumento', 'value' => 'Pasaporte', 'label' => 'Pasaporte'],
            ['group' => 'TipoDocumento', 'value' => 'Cédula', 'label' => 'Cédula'],

            // Combustible
            ['group' => 'EstatusCombustible', 'value' => 'pendiente', 'label' => 'Pendiente'],
            ['group' => 'EstatusCombustible', 'value' => 'aprobado', 'label' => 'Aprobado'],
            ['group' => 'EstatusCombustible', 'value' => 'rechazado', 'label' => 'Rechazado'],
            // Mantenimiento
            ['group' => 'EstatusMantenimiento', 'value' => 'programado', 'label' => 'Programado'],
            ['group' => 'EstatusMantenimiento', 'value' => 'en_progreso', 'label' => 'En Progreso'],
            ['group' => 'EstatusMantenimiento', 'value' => 'completado', 'label' => 'Completado'],
            ['group' => 'EstatusMantenimiento', 'value' => 'cancelado', 'label' => 'Cancelado'],
            // Incidencias
            ['group' => 'EstatusIncidente', 'value' => 'Reportada', 'label' => 'Reportada'],
            ['group' => 'EstatusIncidente', 'value' => 'Atendida', 'label' => 'Atendida'],
            ['group' => 'EstatusIncidente', 'value' => 'Resuelta', 'label' => 'Resuelta'],
            ['group' => 'EstatusIncidente', 'value' => 'Cerrada', 'label' => 'Cerrada'],
            ['group' => 'TipoIncidente', 'value' => 'Accidente', 'label' => 'Accidente'],
            ['group' => 'TipoIncidente', 'value' => 'Avería Mecánica', 'label' => 'Avería Mecánica'],
            ['group' => 'TipoIncidente', 'value' => 'Incidente de Tránsito', 'label' => 'Incidente de Tránsito'],
            ['group' => 'TipoIncidente', 'value' => 'Problema con Cliente', 'label' => 'Problema con Cliente'],
            ['group' => 'TipoIncidente', 'value' => 'Retraso', 'label' => 'Retraso'],
            ['group' => 'TipoIncidente', 'value' => 'Otro', 'label' => 'Otro'],
            // Vehículos
            ['group' => 'TipoVehiculo', 'value' => 'Torton', 'label' => 'Torton'],
            ['group' => 'TipoVehiculo', 'value' => 'Rabón', 'label' => 'Rabón'],
            ['group' => 'TipoVehiculo', 'value' => 'Camión', 'label' => 'Camión'],
            ['group' => 'TipoVehiculo', 'value' => 'Pickup', 'label' => 'Pickup'],
            ['group' => 'TipoVehiculo', 'value' => 'Panel', 'label' => 'Panel'],
            ['group' => 'TipoVehiculo', 'value' => 'Caja Seca', 'label' => 'Caja Seca'],
            ['group' => 'TipoVehiculo', 'value' => 'Plataforma', 'label' => 'Plataforma'],
            ['group' => 'TipoVehiculo', 'value' => 'Refrigerado', 'label' => 'Refrigerado'],
            ['group' => 'TipoVehiculo', 'value' => 'Van', 'label' => 'Van'],
            // Portería
            ['group' => 'CondicionVehiculo', 'value' => 'Bueno', 'label' => 'Bueno'],
            ['group' => 'CondicionVehiculo', 'value' => 'Regular', 'label' => 'Regular'],
            ['group' => 'CondicionVehiculo', 'value' => 'Malo', 'label' => 'Malo'],
        ];

        foreach ($catalogs as $c) {
            Catalog::firstOrCreate(
                ['group' => $c['group'], 'value' => $c['value']],
                ['label' => $c['label']]
            );
        }
    }
}

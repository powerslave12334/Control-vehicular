<?php

namespace Database\Seeders;

use App\Domains\Notification\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();
        Notification::insert([
            [
                'user_id' => 1, 'type' => 'ruta_asignada',
                'title' => 'Nueva ruta asignada',
                'message' => 'Se ha asignado una ruta a tu unidad para mañana.',
                'read' => false, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'user_id' => 1, 'type' => 'instalacion_programada',
                'title' => 'Instalación programada',
                'message' => 'Se ha programado una instalación para el viernes en la sucursal Centro.',
                'read' => false, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'user_id' => 2, 'type' => 'incidencia_registrada',
                'title' => 'Incidencia reportada',
                'message' => 'Unidad V-103 reportó falla en alternador en Toluca.',
                'read' => false, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'user_id' => 1, 'type' => 'mantenimiento_proximo',
                'title' => 'Mantenimiento próximo',
                'message' => 'La unidad V-101 requiere servicio de mantenimiento en 15 días.',
                'read' => false, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'user_id' => 3, 'type' => 'registro_pendiente',
                'title' => 'Registros pendientes',
                'message' => 'Hay 3 bitácoras de viaje pendientes por completar.',
                'read' => false, 'created_at' => $now, 'updated_at' => $now,
            ],
            [
                'user_id' => 1, 'type' => 'evento_admin',
                'title' => 'Evento administrativo',
                'message' => 'Revisar reporte de combustible pendiente del periodo actual.',
                'read' => false, 'created_at' => $now, 'updated_at' => $now,
            ],
        ]);
    }
}

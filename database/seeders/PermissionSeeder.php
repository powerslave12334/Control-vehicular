<?php

namespace Database\Seeders;

use App\Domains\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    protected function resetCachedPermissions(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }

    public function run(): void
    {
        $this->resetCachedPermissions();

        $resources = [
            'vehicle', 'operator', 'route', 'refuel', 'maintenance',
            'incident', 'work_order', 'part', 'document', 'insurance',
            'notification', 'catalog', 'gate_log', 'dashboard', 'user',
        ];

        $actions = ['create', 'read', 'update', 'delete'];

        foreach ($resources as $resource) {
            foreach ($actions as $action) {
                Permission::create(['name' => "$resource.$action", 'guard_name' => 'web']);
            }
        }

        Permission::create(['name' => 'user.manage_roles', 'guard_name' => 'web']);
        Permission::create(['name' => 'notifications.read_all', 'guard_name' => 'web']);

        $allPermissions = Permission::pluck('name')->toArray();

        $readPermissions = [];
        foreach ($resources as $resource) {
            $readPermissions[] = "$resource.read";
        }

        // --- Administrador del sistema ---
        $adminRole = Role::create(['name' => 'Administrador del sistema', 'guard_name' => 'web']);
        $adminRole->givePermissionTo($allPermissions);

        // --- Dirección ---
        $dirRole = Role::create(['name' => 'Dirección', 'guard_name' => 'web']);
        $dirRole->givePermissionTo($readPermissions);
        $dirRole->givePermissionTo('dashboard.read');
        $dirRole->givePermissionTo('notification.read');
        $dirRole->givePermissionTo('notifications.read_all');

        // --- Calidad ---
        $calidadRole = Role::create(['name' => 'Calidad', 'guard_name' => 'web']);
        $calidadRole->givePermissionTo([
            'vehicle.read', 'operator.read', 'route.read', 'document.read',
        ]);
        $calidadRole->givePermissionTo([
            'incident.create', 'incident.read', 'incident.update', 'incident.delete',
            'notification.create', 'notification.read', 'notification.update', 'notification.delete',
        ]);

        // --- Logística ---
        $logisticaRole = Role::create(['name' => 'Logística', 'guard_name' => 'web']);
        $logisticaFull = ['route', 'refuel', 'maintenance', 'work_order', 'part'];
        foreach ($logisticaFull as $res) {
            foreach ($actions as $act) {
                $logisticaRole->givePermissionTo("$res.$act");
            }
        }
        $logisticaRead = array_filter($readPermissions, function ($p) use ($logisticaFull) {
            $res = explode('.', $p)[0];
            return !in_array($res, $logisticaFull);
        });
        $logisticaRole->givePermissionTo($logisticaRead);

        // --- Jefe de Distribución ---
        $jefeRole = Role::create(['name' => 'Jefe de Distribución', 'guard_name' => 'web']);
        $jefeFull = ['route', 'refuel', 'vehicle', 'operator'];
        foreach ($jefeFull as $res) {
            foreach ($actions as $act) {
                $jefeRole->givePermissionTo("$res.$act");
            }
        }
        $jefeRead = array_filter($readPermissions, function ($p) use ($jefeFull) {
            $res = explode('.', $p)[0];
            return !in_array($res, $jefeFull);
        });
        $jefeRole->givePermissionTo($jefeRead);

        // --- Chofer ---
        $choferRole = Role::create(['name' => 'Chofer', 'guard_name' => 'web']);
        $choferRole->givePermissionTo([
            'route.read', 'refuel.read',
            'notification.read', 'notifications.read_all',
        ]);

        // --- Instalador ---
        $instaladorRole = Role::create(['name' => 'Instalador', 'guard_name' => 'web']);
        $instaladorRole->givePermissionTo([
            'work_order.read', 'part.read',
            'notification.read', 'notifications.read_all',
        ]);

        // Assign roles to existing users
        $users = User::all();
        foreach ($users as $user) {
            if ($user->role) {
                $user->assignRole($user->role);
            }
        }

        Artisan::call('cache:forget', ['key' => 'spatie.permission.cache']);
    }
}

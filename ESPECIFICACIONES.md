# Sistema de Control Vehicular — Agua Inmaculada

## Especificaciones del Sistema

---

## 1. Tecnologías

### Backend
| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| PHP | ^8.3 | Lenguaje base |
| Laravel Framework | ^13.8 | Framework MVC |
| Livewire | ^4.3 | Componentes reactivos SFC (Single-File Components) |
| Eloquent ORM | — | ORM incluido en Laravel |
| Monolog | — | Logging |
| PHPUnit | ^12.5 | Testing |

### Frontend / Build Tooling
| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| Vite | ^8.0 | Bundler y dev server |
| laravel-vite-plugin | ^3.1 | Integración Laravel + Vite |
| Tailwind CSS | ^4.0 | Framework CSS utility-first |
| Chart.js | ^4.5 | Gráficos interactivos (dashboard) |
| Alpine.js | — | Reactividad UI (incluido en Livewire) |
| SweetAlert2 | CDN | Notificaciones y diálogos de confirmación |
| Instrument Sans | CDN (Bunny Fonts) | Tipografía |

### DevOps / Herramientas de desarrollo
| Herramienta | Propósito |
|-------------|-----------|
| Concurrently 9 | Ejecución paralela de procesos dev |
| Laravel Pail | Visor de logs en tiempo real |
| Laravel Pint | Code style fixer (PSR-12) |
| FakerPHP | Generación de datos de prueba |
| Mockery | Mocking para tests |
| EditorConfig | Consistencia de estilo de código |

---

## 2. Arquitectura del Sistema

### Patrón
- **Livewire SFC** (Single-File Components): Cada vista combina clase PHP + template Blade en un mismo archivo.
- **Sin controladores tradicionales**: La lógica de negocio reside en los componentes Livewire.
- **Navegación SPA-like**: Se usa `Route::livewire()` para evitar recargas completas de página.
- **Layouts diferenciados**: `app.blade.php` (admin con sidebar), `driver.blade.php` (conductor, header mínimo), `guest.blade.php` (login).

### Roles de usuario
| Rol | Acceso |
|-----|--------|
| **admin** | Todos los módulos del sistema |
| **driver** | Solo módulos de conductor (dashboard, ruta, incidente, recarga) |

Los permisos por módulo se controlan mediante la tabla pivote `module_user` (relación `User` ↔ `Module`).

---

## 3. Módulos

### Módulos del Sistema (Admin)

| Ruta | Componente | Funcionalidades principales |
|------|-----------|----------------------------|
| `/dashboard` | `dashboard` | KPIs, gráficos de flota (Chart.js): kilometraje, combustible, estado de vehículos |
| `/vehicles` | `vehicle-manager` | CRUD vehículos, filtros, asignación de conductor responsable, GPS |
| `/maintenance` | `maintenance-manager` | Registro de mantenimientos preventivos/correctivos, costo, taller, evidencia |
| `/fuel` | `fuel-manager` | Control de recargas: litros, precio, método pago, ticket |
| `/routes` | `route-manager` | Planificación de rutas, asignación de vehículo/conductor/asistente, estados |
| `/geolocation` | `geolocation` | Seguimiento GPS en tiempo real |
| `/calendar` | `calendar` | Vista calendario de rutas programadas |
| `/operators` | `operator-manager` | CRUD operadores/conductores, tipo de licencia, estado |
| `/incidents` | `incident-manager` | Reporte de incidentes, severidad, fotos, estado de resolución |
| `/evidence` | `evidence-gallery` | Galería de evidencia fotográfica (incidentes, mantenimientos, recargas) |
| `/reports` | `reports` | Generación de reportes exportables |
| `/users` | `user-manager` | Administración de usuarios del sistema, roles, permisos por módulo |
| `/security` | `security-panel` | Panel de seguridad OWASP (monitoreo de accesos, intentos fallidos) |
| `/catalogs` | `catalog-manager` | Gestión de catálogos dinámicos (grupo/valor/etiqueta) |

### Módulos del Conductor (prefix `/driver`)

| Ruta | Componente | Funcionalidades principales |
|------|-----------|----------------------------|
| `/driver/dashboard` | `driver-dashboard` | Panel con rutas asignadas, notificaciones, estado personal |
| `/driver/route/{routeId}` | `driver-route` | Ejecución de ruta: inicio/fin, captura odómetro, fotos, coordenadas GPS |
| `/driver/incident/{routeId}` | `driver-incident` | Reporte de incidentes en ruta: descripción, fotos, severidad |
| `/driver/refuel/{routeId}` | `driver-refuel` | Registro de recarga: litros, monto, ticket, odómetro |

---

## 4. Base de Datos

### Motor
| Entorno | Motor | Detalle |
|---------|-------|---------|
| Producción/Desarrollo | **MySQL** | Host: `127.0.0.1:3306`, DB: `control_vehicular` |
| Testing | **SQLite** (en memoria) | Configurado en `phpunit.xml` |

**Redis** configurado pero no activo — cache y queue usan driver `database`.

### Tablas y Entidades (14 migraciones)

| Tabla | Descripción | Relaciones (FK) |
|-------|-------------|-----------------|
| `users` | Usuarios del sistema | `assigned_vehicle_id` → vehicles, `assigned_operator_id` → operators |
| `password_reset_tokens` | Tokens de reseteo de contraseña | — |
| `sessions` | Sesiones de usuario | — |
| `cache` / `cache_locks` | Cache de datos | — |
| `jobs` / `job_batches` / `failed_jobs` | Cola de trabajos | — |
| `vehicles` | Vehículos de la flota | `responsible_user` → users |
| `operators` | Operadores/conductores | — |
| `routes` | Rutas programadas | `driver_id` → operators, `vehicle_id` → vehicles |
| `route_steps` | Pasos/eventos de una ruta | `route_id` → routes (CASCADE) |
| `refuels` | Recargas de combustible | `route_id` → routes (nullable), `vehicle_id` → vehicles, `driver_id` → operators |
| `maintenances` | Mantenimientos de vehículos | `vehicle_id` → vehicles |
| `incidents` | Incidentes reportados | `vehicle_id` → vehicles, `driver_id` → operators |
| `notifications` | Notificaciones a usuarios | `user_id` → users (nullable) |
| `catalogs` | Catálogos dinámicos (grupo → valor → etiqueta) | — |
| `extraordinary_movements` | Movimientos extraordinarios en ruta | `route_id` → routes (CASCADE) |
| `module_user` | Pivote usuarios ↔ módulos | `user_id` → users, `module_id` (string) |

### Modelos Eloquent (11)

| Modelo | Atributos clave | Relaciones |
|--------|----------------|------------|
| **User** | name, email, role, status, phone, assigned_vehicle_id, assigned_operator_id | `hasMany` Notification, `belongsToMany` Module |
| **Vehicle** | brand, model, year, plate, fuel_type, cargo_capacity, tank_capacity, gps_installed, vin, status, current_odometer, last/next_maintenance, incidents_count, authorized_fuel | `hasMany` Route, Refuel, Maintenance, Incident |
| **Operator** | name, license_type, phone, status | `hasMany` Route, Refuel, Incident |
| **Route** | date, week, driver_id/name, assistant_id/name, vehicle_id/plate, client_name, city, state, planned_km, actual_km, status | `belongsTo` Vehicle, Operator; `hasMany` RouteStep, Refuel, ExtraordinaryMovement |
| **RouteStep** | route_id, step_type, odometer, fuel_level, timestamp, photo, liters, amount, ticket_photo, observations, latitude, longitude | `belongsTo` Route |
| **Refuel** | date, route_id, vehicle_id, driver_id/name, liters, amount, price_per_liter, payment_method, ticket_photo, odometer | `belongsTo` Route, Vehicle, Operator |
| **Maintenance** | date, vehicle_id, type, description, cost, workshop, evidence, odometer | `belongsTo` Vehicle |
| **Incident** | date, time, vehicle_id, driver_id/name, description, severity, status, photo | `belongsTo` Vehicle, Operator |
| **Notification** | user_id, type, title, message, read, related_id, related_type | `belongsTo` User |
| **Catalog** | group, value, label | — (catálogo genérico key-value) |
| **ExtraordinaryMovement** | route_id, type, description, photo, observations, timestamp, latitude, longitude | `belongsTo` Route |

### Diagrama de Relaciones (ER)

```
User ──hasMany──> Notification
User ──belongsToMany──> Module (via module_user)

Vehicle ──hasMany──> Route
Vehicle ──hasMany──> Refuel
Vehicle ──hasMany──> Maintenance
Vehicle ──hasMany──> Incident

Operator ──hasMany──> Route (como driver)
Operator ──hasMany──> Refuel (como driver)
Operator ──hasMany──> Incident (como driver)

Route ──belongsTo──> Vehicle
Route ──belongsTo──> Operator
Route ──hasMany──> RouteStep
Route ──hasMany──> Refuel
Route ──hasMany──> ExtraordinaryMovement
```

---

## 5. Reglas de Negocio

### Rutas
- **Estados**: `Programada` → `En Progreso` → `Completada` / `Cancelada`
- El kilometraje real (`actual_km`) se captura al completar la ruta
- Se calcula la diferencia contra el kilometraje planificado (`planned_km`)

### Combustible
- Control de litros cargados, precio por litro, monto total
- Métodos de pago: efectivo, tarjeta, vale, otros
- Se asocia a una ruta y a un vehículo específico
- Foto del ticket como evidencia

### Mantenimientos
- **Tipos**: preventivo, correctivo, eléctrico, llantero, otros
- Se registran con odómetro, costo y taller
- El sistema trackea `last_maintenance` y `next_maintenance` en el vehículo

### Incidentes
- **Severidades**: baja, media, alta, crítica
- **Estados**: reportado, en revisión, resuelto
- Asociados a un vehículo y conductor en una fecha/hora específica
- Fotografía como evidencia obligatoria

### Usuarios y Permisos
- Dos roles: `admin` (acceso total) y `driver` (solo módulos de conductor)
- Permisos granulares por módulo via tabla pivote `module_user`

---

## 6. Almacenamiento de Archivos

- **Fotos/evidencias**: almacenamiento local en `storage/app/` (disco `local`)
- Tipos de imágenes: tickets de recarga, evidencias de incidentes, comprobantes de mantenimiento
- Sin integración con servicios cloud (S3, etc.) por ahora

---

## 7. Pruebas

| Aspecto | Detalle |
|---------|---------|
| Framework | PHPUnit ^12.5 |
| Mocking | Mockery ^1.6 |
| Datos de prueba | FakerPHP ^1.23 |
| Base de datos | SQLite en memoria (`:memory:`) |
| Ejecución | `composer test` (`php artisan test`) |
| Tests existentes | Unitarios y Feature (carpeta `tests/`) |

---

## 8. DevOps / Deployment

### Scripts disponibles
| Comando | Descripción |
|---------|-------------|
| `composer setup` | Instalación completa desde cero (composer, .env, key, migrate, npm, build) |
| `composer dev` | Entorno de desarrollo paralelo (serve + queue + logs + vite) |
| `composer test` | Ejecutar tests |

### Estado actual
- **Sin Docker** (no hay Dockerfile ni docker-compose)
- **Sin CI/CD** configurado
- Servidor embebido de PHP para desarrollo (`php artisan serve`)

---

## 9. Observaciones Técnicas

- **Modelo `Module`**: Referenciado en `User::modules()` (`belongsToMany(Module::class)`) pero no existe el archivo del modelo. La tabla pivote `module_user` sí está migrada. Pendiente de crear o verificar si es parte de un paquete externo.
- **`assistant_id` en `routes`**: Definido como `string` (nullable). Debería evaluarse si corresponde cambiarlo a `foreignId` referenciando `operators`.
- **Responsable de vehículo**: `responsible_user` es un `string` en el modelo, no una FK a `users`. Posible oportunidad de normalización.

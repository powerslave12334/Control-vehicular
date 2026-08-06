<p align="center">
  <img src="public/build/" alt="Control Vehicular" style="display: none;" />
  <h1 align="center">Control Vehicular</h1>
  <p align="center">Sistema web para la gestión integral de la flota vehicular de Agua Inmaculada: vehículos, operadores, rutas, combustible, mantenimientos, incidencias y control de portón.</p>
</p>

## Características

- **Autenticación y roles**: login con Laravel + roles y permisos (`spatie/laravel-permission`).
- **Vehículos** — control de flota, asignación de operador, alta/baja, documentos, seguros y proveedores.
- **Operadores** — gestión de conductores asignados a vehículos.
- **Rutas** — planificación y registro de rutas por semana, con pasos intermedios y asignación de operador.
- **Geolocalización** — seguimiento en tiempo real y reproducción de rutas sobre mapa.
- **Combustible (carga)** — registro de carga en tanques, con control de costos y volumen.
- **Mantenimiento** — órdenes de trabajo, inspecciones, refacciones, proveedores y documentos.
- **Incidencias** — registro y seguimiento de incidentes durante las rutas.
- **Gastos** — registro de gastos operativos.
- **Portón (Gate)** — libro de bitácora de entradas y salidas de vehículos.
- **Evidencias** — galería de fotografías asociadas a operaciones.
- **Reportes** — generación de reportes y estadísticas con Chart.js.
- **Calendario** — vista calendario de actividad de la flota.
- **Notificaciones** — centro de notificaciones con prioridades y preferencias.
- **Módulo conductor** — vista específica para operadores: dashboard, rutas, incidencias y carga de combustible.
- **Panel de seguridad y usuarios** — gestión de usuarios y auditoría (registro de actividad).
- **Módulos y catálogos** — catálogos configurables y control de módulos del sistema.

## Stack tecnológico

- **Laravel 13** (PHP 8.3)
- **Livewire 4** — componentes y SPA-like interactividad
- **Tailwind CSS 4** + **Vite**
- **Chart.js** — gráficas del dashboard y reportes
- **Sweetalert2** — notificaciones y confirmaciones
- **Laravel Sanctum** — autenticación de API
- **Spatie Laravel Permission** — roles y permisos

## Requisitos

- PHP >= 8.3
- Composer
- Node.js y npm
- Base de datos: MySQL, PostgreSQL o SQLite

## Instalación

```bash
# 1. Clonar el repositorio
git clone https://github.com/powerslave12334/Control-vehicular.git
cd Control-vehicular

# 2. Instalar dependencias de PHP
composer install

# 3. Configurar entorno
cp .env.example .env
php artisan key:generate

# 4. Configura los accesos a la base de datos en el archivo .env
#    y luego ejecuta las migraciones
php artisan migrate --seed

# 5. Instalar dependencias de JavaScript
npm install

# 6. Compilar assets
npm run build
```

También puedes usar el script de setup automático:

```bash
composer run setup
```

## Desarrollo local

```bash
composer run dev
```

Este script levanta en paralelo con `concurrently`:

- `php artisan serve` — servidor web
- `php artisan queue:listen` — worker de colas
- `php artisan pail` — logs en tiempo real
- `npm run dev` — Vite con hot reload

## Rutas principales

| Ruta | Descripción |
|------|-------------|
| `/` | Login |
| `/dashboard` | Panel principal |
| `/vehicles` | Gestión de vehículos |
| `/operators` | Gestión de operadores |
| `/routes` | Riesgos y rutas |
| `/geolocation` | Seguimiento geolocalizado |
| `/fuel` | Control de combustible |
| `/maintenance` | Mantenimientos |
| `/incidents` | Incidencias |
| `/expenses` | Gastos |
| `/gate` y `/gate/logs` | Portón y bitácora |
| `/reports` | Reportes |
| `/driver/dashboard` | Panel del conductor |

## Estructura

```
app/
  Http/Controllers/     # Controladores
  Livewire/             # Componentes Livewire
    Auth/ Vehicle/ Fuel/ Maintenance/ Operator/
    Route/ Incident/ Catalog/ Evidence/ Gate/
    User/ Security/ Reports/ Geolocation/
    Calendar/ Notification/ Expense/ Driver/
  Models/               # Modelos Eloquent
database/
  migrations/           # Esquema de la base de datos
  seeders/              # Datos iniciales
routes/
  web.php               # Rutas web
```

## Pruebas

```bash
composer run test
```

## Licencia

Proyecto de desarrollo para **Agua Inmaculada**. Todos los derechos reservados.
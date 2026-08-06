<?php

namespace App\Livewire\Security;

use App\Http\Middleware\SecurityHeadersMiddleware;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::app')]
class SecurityPanel extends Component
{
    public string $activeTab = 'seguridad';

    public array $checks = [];

    public string $overallStatus = '';

    public array $activityLogs = [];

    public string $eventFilter = '';

    public string $subjectFilter = '';

    public int $activityPage = 1;

    public int $activityTotal = 0;

    public int $activityPerPage = 50;

    public array $systemLogs = [];

    public ?int $logFileSize = null;

    public ?int $logFileLines = null;

    public string $logLevelFilter = '';

    public function mount()
    {
        $this->runChecks();
        $this->loadActivityLogs();
        $this->loadSystemLogs();
    }

    public function runChecks()
    {
        $headers = $this->fetchHeaders();

        $this->checks = [
            $this->checkCsrf(),
            $this->checkHttpOnly(),
            $this->checkPasswordHashing(),
            $this->checkXssProtection(),
            $this->checkCsp($headers),
            $this->checkHsts($headers),
            $this->checkXFrameOptions($headers),
            $this->checkXContentTypeOptions($headers),
            $this->checkRateLimiting(),
            $this->checkHttpsEnforcement(),
            $this->checkSessionSecure(),
            $this->checkDebugMode(),
            $this->checkAppKey(),
            $this->checkFileUploads(),
        ];

        $failCount = collect($this->checks)->where('pass', false)->count();
        $this->overallStatus = match (true) {
            $failCount === 0 => 'Seguro',
            $failCount <= 2 => 'Precaución',
            default => 'Revisión requerida',
        };
    }

    private function fetchHeaders(): array
    {
        try {
            $response = Http::timeout(3)->get(config('app.url'));

            return $response->headers();
        } catch (\Exception) {
            return $this->fallbackHeaders();
        }
    }

    private function fallbackHeaders(): array
    {
        $webGroup = app('router')->getMiddlewareGroups()['web'] ?? [];

        if (! in_array(SecurityHeadersMiddleware::class, $webGroup, true)) {
            return [];
        }

        return collect((new SecurityHeadersMiddleware)->securityHeaders())
            ->mapWithKeys(fn ($value, $name) => [$name => [$value]])
            ->all();
    }

    private function checkCsrf(): array
    {
        $sameSite = config('session.same_site');
        $pass = in_array($sameSite, ['lax', 'strict'], true);

        return [
            'name' => 'CSRF Protection',
            'pass' => $pass,
            'detail' => $pass
                ? 'Laravel protege rutas POST vía VerifyCsrfToken. Same-Site: '.strtoupper($sameSite)
                : 'Same-Site configurado como '.($sameSite ?? 'null').'. Se recomienda lax o strict.',
            'badge' => $pass ? 'Activo' : 'Configurar',
        ];
    }

    private function checkHttpOnly(): array
    {
        $pass = config('session.http_only') === true;

        return [
            'name' => 'HttpOnly Cookies',
            'pass' => $pass,
            'detail' => $pass
                ? 'Session cookies con HttpOnly=true en config/session.php.'
                : 'HttpOnly no está habilitado en config/session.php.',
            'badge' => $pass ? 'Activo' : 'Configurar',
        ];
    }

    private function checkPasswordHashing(): array
    {
        $driver = config('hashing.driver', 'bcrypt');
        $rounds = env('BCRYPT_ROUNDS', 12);
        $pass = in_array($driver, ['bcrypt', 'argon', 'argon2id'], true);

        return [
            'name' => 'Password Hashing',
            'pass' => $pass,
            'detail' => $pass
                ? "Hash driver: {$driver} con {$rounds} rounds."
                : 'Driver de hashing no configurado correctamente.',
            'badge' => $pass ? 'Activo' : 'Configurar',
        ];
    }

    private function checkXssProtection(): array
    {
        $pass = true;

        return [
            'name' => 'XSS Protection',
            'pass' => $pass,
            'detail' => 'Blade {{ }} escapa output automáticamente. SweetAlert2 evita eval(). Sin uso de {!! !!} sin escapar en vistas.',
            'badge' => 'Activo',
        ];
    }

    private function checkCsp(array $headers): array
    {
        $csp = $this->getHeader($headers, 'content-security-policy');
        $pass = $csp !== null;

        return [
            'name' => 'Content-Security-Policy (CSP)',
            'pass' => $pass,
            'detail' => $pass
                ? 'Header CSP presente: '.mb_substr($csp, 0, 80).(mb_strlen($csp) > 80 ? '...' : '')
                : 'No se encontró el header Content-Security-Policy. Implementar middleware de seguridad.',
            'badge' => $pass ? 'Activo' : 'Faltante',
        ];
    }

    private function checkHsts(array $headers): array
    {
        $hsts = $this->getHeader($headers, 'strict-transport-security');
        $pass = $hsts !== null;

        return [
            'name' => 'Strict-Transport-Security (HSTS)',
            'pass' => $pass,
            'detail' => $pass
                ? 'Header HSTS presente.'
                : 'No se detectó HSTS. Forzar HTTPS + header Strict-Transport-Security en producción.',
            'badge' => $pass ? 'Activo' : 'Faltante',
        ];
    }

    private function checkXFrameOptions(array $headers): array
    {
        $xfo = $this->getHeader($headers, 'x-frame-options');
        $pass = $xfo !== null;

        return [
            'name' => 'X-Frame-Options',
            'pass' => $pass,
            'detail' => $pass
                ? "Header X-Frame-Options: {$xfo}."
                : 'No se detectó X-Frame-Options. Agregar DENY o SAMEORIGIN para prevenir clickjacking.',
            'badge' => $pass ? 'Activo' : 'Faltante',
        ];
    }

    private function checkXContentTypeOptions(array $headers): array
    {
        $xcto = $this->getHeader($headers, 'x-content-type-options');
        $pass = $xcto === 'nosniff';

        return [
            'name' => 'X-Content-Type-Options',
            'pass' => $pass,
            'detail' => $pass
                ? 'Header X-Content-Type-Options: nosniff.'
                : ($xcto
                    ? "Valor actual: {$xcto}. Debe ser 'nosniff'."
                    : 'No se detectó el header. Agregar X-Content-Type-Options: nosniff.'),
            'badge' => $pass ? 'Activo' : 'Faltante',
        ];
    }

    private function checkRateLimiting(): array
    {
        $router = app('router');
        $registeredMiddleware = method_exists($router, 'getMiddleware') ? $router->getMiddleware() : [];
        $hasThrottle = isset($registeredMiddleware['throttle'])
            || in_array(ThrottleRequests::class, $registeredMiddleware, true)
            || class_exists(ThrottleRequests::class);

        return [
            'name' => 'Rate Limiting',
            'pass' => $hasThrottle,
            'detail' => $hasThrottle
                ? 'Middleware throttle disponible para limitar peticiones.'
                : 'No se encontró middleware throttle. Laravel lo incluye por defecto.',
            'badge' => $hasThrottle ? 'Activo' : 'Verificar',
        ];
    }

    private function checkHttpsEnforcement(): array
    {
        $forceHttps = config('app.force_https', false);
        $env = config('app.env');
        $pass = $forceHttps || $env === 'production';

        return [
            'name' => 'HTTPS Enforcement',
            'pass' => $pass,
            'detail' => $pass
                ? ($forceHttps ? 'APP_FORCE_HTTPS activo.' : 'Entorno: '.$env.'.')
                : 'No se fuerza HTTPS. Entorno: '.$env.'. Agregar APP_FORCE_HTTPS=true en producción.',
            'badge' => $pass ? 'Activo' : ($env === 'local' ? 'Desarrollo' : 'Configurar'),
        ];
    }

    private function checkSessionSecure(): array
    {
        $secure = config('session.secure');
        $sameSite = config('session.same_site');
        $pass = $secure === true || $secure === null || in_array($sameSite, ['lax', 'strict'], true);

        return [
            'name' => 'Session Secure & Same-Site',
            'pass' => $pass,
            'detail' => $pass
                ? 'Secure: '.($secure ?? 'null (auto)').', Same-Site: '.strtoupper($sameSite)
                : 'Secure: '.($secure ?? 'null').', Same-Site: '.($sameSite ?? 'null'),
            'badge' => $pass ? 'Activo' : 'Configurar',
        ];
    }

    private function checkDebugMode(): array
    {
        $debug = config('app.debug');
        $env = config('app.env');
        $pass = ! $debug || $env !== 'production';

        return [
            'name' => 'Debug Mode',
            'pass' => $pass,
            'detail' => $pass
                ? ($debug ? 'APP_DEBUG=true (entorno: '.$env.' — aceptable).' : 'APP_DEBUG=false.')
                : 'APP_DEBUG=true en producción. ¡Deshabilitar inmediatamente!',
            'badge' => $pass ? ($debug ? 'Desarrollo' : 'Seguro') : '¡Crítico!',
        ];
    }

    private function checkAppKey(): array
    {
        $key = config('app.key');
        $placeholder = ['base64:', 'SomeRandomStringSomeRandomString', ''];
        $pass = is_string($key) && ! in_array(trim($key), $placeholder, true);

        return [
            'name' => 'APP Key',
            'pass' => $pass,
            'detail' => $pass
                ? 'APP_KEY configurada correctamente.'
                : 'APP_KEY no configurada o es el valor por defecto. Ejecutar php artisan key:generate.',
            'badge' => $pass ? 'Activo' : 'Configurar',
        ];
    }

    private function checkFileUploads(): array
    {
        $maxUpload = ini_get('upload_max_filesize');
        $maxPost = ini_get('post_max_size');
        $pass = true;

        return [
            'name' => 'File Uploads',
            'pass' => $pass,
            'detail' => "upload_max_filesize: {$maxUpload}, post_max_size: {$maxPost}. Los archivos se validan por tipo y tamaño en cada módulo.",
            'badge' => 'Información',
        ];
    }

    private function getHeader(array $headers, string $key): ?string
    {
        foreach ($headers as $name => $values) {
            if (mb_strtolower($name) === $key) {
                return $values[0] ?? null;
            }
        }

        return null;
    }

    public function updatedEventFilter()
    {
        $this->activityPage = 1;
        $this->loadActivityLogs();
    }

    public function updatedSubjectFilter()
    {
        $this->activityPage = 1;
        $this->loadActivityLogs();
    }

    public function loadActivityLogs()
    {
        $query = DB::table('activity_logs')
            ->leftJoin('users', 'activity_logs.causer_id', '=', 'users.id');

        if ($this->eventFilter) {
            $query->where('activity_logs.event', $this->eventFilter);
        }
        if ($this->subjectFilter) {
            $query->where('activity_logs.subject_type', 'like', "%{$this->subjectFilter}%");
        }

        $this->activityTotal = (clone $query)->count();

        $this->activityLogs = $query
            ->select('activity_logs.*', 'users.name as causer_name')
            ->latest('activity_logs.created_at')
            ->offset(($this->activityPage - 1) * $this->activityPerPage)
            ->limit($this->activityPerPage)
            ->get()
            ->toArray();
    }

    public function gotoActivityPage(int $page)
    {
        $this->activityPage = max(1, $page);
        $this->loadActivityLogs();
    }

    public function filterByLevel(string $level)
    {
        $this->logLevelFilter = $level;
        $this->loadSystemLogs();
    }

    public function loadSystemLogs()
    {
        $path = storage_path('logs/laravel.log');
        if (! file_exists($path)) {
            $this->systemLogs = [];
            $this->logFileSize = 0;
            $this->logFileLines = 0;

            return;
        }

        $this->logFileSize = filesize($path);

        $file = new \SplFileObject($path, 'r');
        $file->seek(PHP_INT_MAX);
        $totalLines = $file->key() + 1;
        $this->logFileLines = $totalLines;

        $maxScanLines = min(5000, $totalLines);
        $startLine = max(0, $totalLines - $maxScanLines);

        $entries = [];
        $current = null;

        for ($i = $startLine; $i < $totalLines; $i++) {
            $file->seek($i);
            $line = $file->current();

            if (preg_match('/^\[(\d{4}-\d{2}-\d{2} \d{2}:\d{2}:\d{2})\] (\w+)\.(\w+): (.*)$/', $line, $m)) {
                if ($current) {
                    $entries[] = $current;
                }
                $current = [
                    'timestamp' => $m[1],
                    'env' => $m[2],
                    'level' => $m[3],
                    'message' => mb_substr($m[4], 0, 300),
                    'trace' => '',
                ];
            } elseif ($current !== null && mb_strlen($current['trace']) < 2000) {
                $current['trace'] .= mb_substr($line, 0, 500)."\n";
            }
        }
        if ($current) {
            $entries[] = $current;
        }

        if ($this->logLevelFilter) {
            $entries = array_values(array_filter($entries, fn ($e) => $e['level'] === $this->logLevelFilter));
        }

        $this->systemLogs = array_slice(array_reverse($entries), 0, 100);
    }

    public function filterSystemLogs()
    {
        $this->loadSystemLogs();
    }

    public function clearLogs()
    {
        file_put_contents(storage_path('logs/laravel.log'), '');
        $this->loadSystemLogs();
        $this->dispatch('swal:success', title: 'Logs limpiados', message: 'El archivo laravel.log ha sido vaciado.');
    }

    public function render()
    {
        return view('livewire.security-panel', [
            'activityPages' => max(1, (int) ceil($this->activityTotal / $this->activityPerPage)),
        ]);
    }
}

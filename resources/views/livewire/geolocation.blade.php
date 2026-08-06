<div class="space-y-6">
    {{-- HEADER --}}
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-lg font-black text-slate-800">Geolocalización de Rutas</h2>
        <select wire:change="selectRoute($event.target.value)"
            class="px-3 py-2 rounded-lg border border-slate-200 text-sm font-bold focus:ring-2 focus:ring-[#E72085]/20 focus:border-[#E72085] outline-none bg-white max-w-xs">
            @foreach($routes as $r)
            <option value="{{ $r->id }}" @if($r->id === $selectedRouteId) selected @endif>
                {{ $r->date?->format('d/m/Y') ?? '—' }} — {{ $r->client_name }}
            </option>
            @endforeach
        </select>
    </div>

    @if($routeSummary)
    @php
        $kmReal = 0;
        $firstOdo = null;
        $lastOdo = null;
        foreach ($routePoints as $p) {
            if (preg_match('/[\d,]+/', (string) $p['subtitle'], $m)) {
                $v = (int) str_replace(',', '', $m[0]);
                if ($firstOdo === null) $firstOdo = $v;
                $lastOdo = $v;
            }
        }
        $kmDisplay = $routeSummary['actual'] > 0 ? $routeSummary['actual'] : ($lastOdo !== null && $firstOdo !== null ? $lastOdo - $firstOdo : 0);
    @endphp
    {{-- MANIFEST --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 px-5 py-4">
        <div class="flex flex-wrap items-center gap-x-8 gap-y-3">
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Cliente</p>
                <p class="font-black text-slate-800 text-sm">{{ $routeSummary['client'] }}</p>
            </div>
            <div class="w-px h-8 bg-slate-100"></div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Unidad</p>
                <p class="font-mono font-black text-slate-800 text-sm tabular-nums">{{ $routeSummary['plate'] }}</p>
            </div>
            <div class="w-px h-8 bg-slate-100"></div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Conductor</p>
                <p class="font-bold text-slate-800 text-sm">{{ $routeSummary['driver'] }}</p>
            </div>
            <div class="w-px h-8 bg-slate-100"></div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Km</p>
                <p class="font-mono font-black text-slate-800 text-sm tabular-nums">{{ $kmDisplay > 0 ? number_format($kmDisplay) : '—' }}</p>
            </div>
            <div class="w-px h-8 bg-slate-100"></div>
            <div>
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Puntos</p>
                <p class="font-mono font-black text-slate-800 text-sm tabular-nums">{{ count($routePoints) }}</p>
            </div>
            <div class="ml-auto">
                <span class="px-2 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wider
                    @switch($routeSummary['status'])
                        @case(\App\Domains\Route\Enums\RouteStatusEnum::Completada->value) bg-emerald-50 text-emerald-600 @break
                        @case(\App\Domains\Route\Enums\RouteStatusEnum::EnTransito->value) bg-amber-50 text-amber-600 @break
                        @case(\App\Domains\Route\Enums\RouteStatusEnum::Instalando->value) bg-indigo-50 text-indigo-600 @break
                        @default bg-blue-50 text-blue-600
                    @endswitch">{{ $routeSummary['status'] }}</span>
            </div>
        </div>
    </div>
    @endif

    @php $canReplay = count($routePoints) >= 2; @endphp

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- MAP + PLAYBACK --}}
        <div class="lg:col-span-2 bg-white rounded-2xl shadow-xs border border-slate-200 p-5">
            <div class="relative">
                <div wire:ignore id="routeMap" style="height: 520px; border-radius: 12px; z-index: 1;"></div>
                <div id="geoEmpty" class="hidden absolute inset-0 z-[2] flex items-center justify-center bg-white/80 rounded-xl">
                    <p class="text-sm font-bold text-slate-400">Esta ruta no tiene puntos con coordenadas aún</p>
                </div>
            </div>

            @if($canReplay)
            <div class="mt-3 flex items-center gap-3 bg-slate-50 rounded-xl px-3 py-2 border border-slate-100">
                <button type="button" id="replayPlayBtn"
                    class="w-9 h-9 rounded-full bg-[#E72085] text-white flex items-center justify-center hover:bg-[#d01c73] transition focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#E72085]/40 shrink-0" title="Reproducir recorrido">
                    <svg id="replayPlayIcon" class="w-4 h-4 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <svg id="replayPauseIcon" class="w-4 h-4 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </button>
                <input id="replaySlider" type="range" min="0" max="1000" value="0" class="flex-1 accent-[#E72085]">
                <span id="replayLabel" class="font-mono text-[10px] text-slate-500 tabular-nums w-12 text-right shrink-0">1 / {{ count($routePoints) }}</span>
            </div>
            @endif

            @if(count($routePoints) > 0)
            <div class="flex items-center gap-4 mt-3 text-[10px] font-bold text-slate-500 flex-wrap">
                <span class="uppercase tracking-wider">Leyenda:</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full" style="background:#E72085"></span> Salida Planta</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full" style="background:#008FD3"></span> Llegada Cliente</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full" style="background:#F59E0B"></span> Inicio Instalación</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full" style="background:#10B981"></span> Fin Instalación</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full" style="background:#8B5CF6"></span> Carga Combustible</span>
                <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-full" style="background:#059669"></span> Regreso Planta</span>
            </div>
            @endif
        </div>

        {{-- TIMELINE --}}
        <div class="lg:col-span-1 bg-white rounded-2xl shadow-xs border border-slate-200 p-5">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider">Recorrido</h3>
                <span class="font-mono text-[10px] text-slate-400 font-bold tabular-nums">{{ count($routePoints) }} puntos</span>
            </div>
            <div class="space-y-1 max-h-[520px] overflow-y-auto pr-1">
                @forelse($routePoints as $i => $p)
                <button type="button" data-step="{{ $i }}"
                    class="w-full text-left flex items-start gap-3 rounded-lg px-2 py-2 transition hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-[#E72085]/30">
                    <span class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-black shrink-0 mt-0.5" style="background:{{ $p['color'] }}22; color:{{ $p['color'] }}">{{ $i + 1 }}</span>
                    <span class="flex-1 min-w-0">
                        <span class="block text-xs font-bold text-slate-700">{{ $p['label'] }}</span>
                        <span class="block font-mono text-[10px] text-slate-400 tabular-nums">{{ $p['timestamp'] }}{{ $p['subtitle'] ? ' · ' . $p['subtitle'] : '' }}</span>
                        @if($p['observations'])
                        <span class="block text-[10px] text-slate-500 italic mt-0.5">{{ $p['observations'] }}</span>
                        @endif
                    </span>
                </button>
                @empty
                <div class="text-center py-12 text-slate-400 text-sm font-bold">Sin puntos con coordenadas en esta ruta</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

@push('scripts')
<style>
    .g-pop-wrap { position: absolute; }
    .g-pop-wrap .g-pop-tail {
        border-color: #0F172A transparent transparent transparent;
        border-width: 9px 8px 0 8px;
    }
    .g-pop-wrap.g-pop-below .g-pop-tail {
        border-color: transparent transparent #0F172A transparent;
        border-width: 0 8px 9px 8px;
    }
    .g-pop-row { display: flex; align-items: baseline; gap: 0px; }
    .g-pop-key {
        font: 700 8px/1 'JetBrains Mono', monospace;
        color: #64748B;
        letter-spacing: .12em;
        text-transform: uppercase;
        flex: none;
        width: 34px;
    }
    .g-pop-val { color: #E2E8F0; }
    .g-pop-mono {
        font: 600 11px/1.5 'JetBrains Mono', monospace;
        color: #F1F5F9;
        letter-spacing: .01em;
    }
</style>
<script>
document.addEventListener('livewire:init', () => {
    let map = null;
    let markers = [];
    let activePolyline = null;
    let directionsRenderer = null;
    let routeToken = 0;
    let routeSegs = [];

    let points = [];
    let truck = null;
    let playing = false;
    let progress = 0;
    let raf = null;
    let lastFrame = 0;
    let popups = [];
    let lastPopupIdx = -1;
    let popupOverlay = null;
    const LEG_DUR = 4000;
    const logoUrl = @js(asset('img/LogoAzul.png'));
    const logoPinData = @js('data:image/png;base64,' . base64_encode(file_get_contents(public_path('img/logo-pin.png'))));

    const stepColors = {
        departurePlant: '#E72085',
        arrivalClient: '#008FD3',
        startInstallation: '#F59E0B',
        endInstallation: '#10B981',
        returnToPlant: '#8B5CF6',
        refuel: '#8B5CF6',
        arrivalPlant: '#059669',
    };

    const stepLabels = {
        departurePlant: 'Salida Planta',
        arrivalClient: 'Llegada Cliente',
        startInstallation: 'Inicio Instalación',
        endInstallation: 'Fin Instalación',
        returnToPlant: 'Regreso a Planta',
        refuel: 'Carga Combustible',
        arrivalPlant: 'Regreso Planta',
    };

    function createIcon(google, num, color) {
        const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22"><circle cx="11" cy="11" r="9" fill="${color}" stroke="white" stroke-width="2"/><text x="11" y="15" text-anchor="middle" font-size="10" font-weight="bold" fill="white">${num}</text></svg>`;
        return {
            url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg),
            scaledSize: new google.maps.Size(22, 22),
            anchor: new google.maps.Point(11, 11),
        };
    }

    function truckIcon(google) {
        const svg = `<svg xmlns="http://www.w3.org/2000/svg" width="32" height="40" viewBox="0 0 32 40">
            <defs>
                <filter id="psh" x="-30%" y="-30%" width="160%" height="160%"><feDropShadow dx="0" dy="1.2" stdDeviation="1.6" flood-color="rgba(15,23,42,.35)"/></filter>
                <clipPath id="pclip"><circle cx="16" cy="13" r="11"/></clipPath>
            </defs>
            <path d="M16 40 C16 40 31.5 25 31.5 16 C31.5 7.44 24.56 0.5 16 0.5 C7.44 0.5 0.5 7.44 0.5 16 C0.5 25 16 40 16 40 Z" fill="#E72085" stroke="#ffffff" stroke-width="2.2" filter="url(#psh)"/>
            <circle cx="16" cy="13" r="11" fill="#ffffff"/>
            <image href="${logoPinData}" x="4.5" y="1.5" width="23" height="23" preserveAspectRatio="xMidYMid slice" clip-path="url(#pclip)"/>
        </svg>`;
        return {
            url: 'data:image/svg+xml;charset=UTF-8,' + encodeURIComponent(svg),
            scaledSize: new google.maps.Size(32, 40),
            anchor: new google.maps.Point(16, 39),
        };
    }

    function getPosAt(frac) {
        const n = points.length;
        if (n < 2) return null;
        const pos = Math.max(0, Math.min(frac * (n - 1), n - 1));
        const i = Math.min(Math.floor(pos), n - 2);
        const f = pos - i;
        return {
            index: i,
            pos,
            lat: points[i].lat + (points[i + 1].lat - points[i].lat) * f,
            lng: points[i].lng + (points[i + 1].lng - points[i].lng) * f,
        };
    }

    function getRoutePos(frac) {
        const n = points.length;
        if (n < 2) return null;
        const pos = Math.max(0, Math.min(frac * (n - 1), n - 1));
        const i = Math.min(Math.floor(pos), n - 2);
        const f = pos - i;
        const seg = routeSegs[i];
        if (!seg || seg.length < 2) return null;
        const idx = f * (seg.length - 1);
        const a = Math.min(Math.floor(idx), seg.length - 2);
        const g = idx - a;
        const p1 = seg[a];
        const p2 = seg[a + 1];
        return {
            index: i,
            pos,
            lat: p1.lat + (p2.lat - p1.lat) * g,
            lng: p1.lng + (p2.lng - p1.lng) * g,
        };
    }

    function highlightStep(i) {
        document.querySelectorAll('[data-step]').forEach((el, idx) => {
            const active = idx === i;
            el.classList.toggle('bg-[#E72085]/[0.06]', active);
            el.classList.toggle('ring-1', active);
            el.classList.toggle('ring-[#E72085]/25', active);
        });
    }

    function buildPopupHtml(info) {
        const color = info.color || '#64748b';
        const num = (info.index ?? 0) + 1;
        const label = info.label || `Paso ${num}`;
        const ts = info.timestamp || '';
        const odo = info.subtitle || '';
        const obs = info.observations || '';
        const lat = Number(info.lat).toFixed(4);
        const lng = Number(info.lng).toFixed(4);
        const total = info.total || num;

        const row = (k, v) => v
            ? `<div class="g-pop-row"><span class="g-pop-key">${k}</span><span class="g-pop-val g-pop-mono">${v}</span></div>`
            : '';

        return `
            <div class="g-pop" style="background:#0F172A;color:#fff;border-radius:10px;width:252px;border-left:3px solid ${color};box-shadow:0 10px 30px rgba(2,6,23,.35),0 0 0 1px rgba(15,23,42,.45);overflow:hidden;">
                <div style="display:flex;align-items:center;gap:8px;padding:10px 12px 8px 12px;">
                    <span class="g-pop-badge" style="width:22px;height:22px;border-radius:50%;background:${color};color:#fff;font:800 11px/22px 'JetBrains Mono',monospace;text-align:center;flex:none;">${num}</span>
                    <span style="font:700 13px/1.2 'Instrument Sans',system-ui,sans-serif;letter-spacing:.01em;">${label}</span>
                    <img src="${logoUrl}" alt="Agua Inmaculada" style="height:16px;width:auto;margin-left:auto;opacity:.85;" loading="lazy"/>
                </div>
                <div style="height:1px;background:#334155;margin:0 12px;"></div>
                <div style="padding:8px 12px 6px 12px;font-size:11px;">
                    ${row('HORA', ts)}
                    ${row('ODOM', odo)}
                    <div class="g-pop-row"><span class="g-pop-key">LAT</span><span class="g-pop-val g-pop-mono">${lat}</span></div>
                    <div class="g-pop-row"><span class="g-pop-key">LNG</span><span class="g-pop-val g-pop-mono">${lng}</span></div>
                    ${obs ? `<div style="color:#CBD5E1;font-style:italic;margin-top:4px;padding-left:2px;">&ldquo;${obs}&rdquo;</div>` : ''}
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;padding:2px 10px 8px 12px;">
                    <span style="font:600 9px/1 'JetBrains Mono',monospace;color:#64748B;letter-spacing:.08em;text-transform:uppercase;">Paso ${num} de ${total}</span>
                    <button type="button" data-popup-close aria-label="Cerrar" style="border:0;background:transparent;cursor:pointer;color:#94A3B8;font:700 14px/1 'JetBrains Mono',monospace;padding:2px 4px;">&times;</button>
                </div>
            </div>
            <div class="g-pop-tail" style="position:absolute;left:50%;transform:translateX(-50%);width:0;height:0;border-style:solid;"></div>`;
    }

    function StepPopup(google, map, marker, info) {
        const self = this;
        this.map = map;
        this.marker = marker;
        this.info = info;
        this.div = null;

        this.setMap(map);

        this.onAdd = function () {
            const div = document.createElement('div');
            div.className = 'g-pop-wrap';
            div.setAttribute('role', 'dialog');
            div.setAttribute('aria-label', info.label || 'Paso');
            div.style.cssText = 'position:absolute;transform:translate(-50%,-100%);will-change:transform;';
            div.innerHTML = buildPopupHtml(info);
            div.querySelector('[data-popup-close]').addEventListener('click', (e) => {
                e.stopPropagation();
                self.close();
            });
            google.maps.event.addDomListener(div, 'click', (e) => e.stopPropagation());
            self.div = div;
            self.getPanes().overlayMouseTarget.appendChild(div);
        };

        this.draw = function () {
            if (!self.div) return;
            const proj = self.getProjection();
            const pos = self.marker.getPosition();
            if (!proj || !pos) return;
            const point = proj.fromLatLngToDivPixel(pos);
            const div = self.div;
            const w = div.offsetWidth;
            const h = div.offsetHeight;
            const cw = self.map.getDiv().clientWidth;
            const ch = self.map.getDiv().clientHeight;
            const pad = 8;
            let x = Math.min(Math.max(point.x, pad + w / 2), cw - pad - w / 2);
            const aboveOk = (point.y - 14 - h) >= pad;
            if (aboveOk) {
                div.style.transform = 'translate(-50%,-100%)';
                div.classList.remove('g-pop-below');
                div.style.top = (point.y - 14) + 'px';
            } else {
                div.style.transform = 'translate(-50%,0)';
                div.classList.add('g-pop-below');
                div.style.top = (point.y + 18) + 'px';
            }
            div.style.left = x + 'px';
        };

        this.close = function () {
            if (self.div && self.div.parentNode) self.div.parentNode.removeChild(self.div);
            self.div = null;
            self.setMap(null);
        };
    }

    function ensureStepPopupBase(google) {
        if (!(StepPopup.prototype instanceof google.maps.OverlayView)) {
            StepPopup.prototype = new google.maps.OverlayView();
        }
    }

    function showStepPopup(i) {
        const entry = popups[i];
        if (popupOverlay) { popupOverlay.close(); popupOverlay = null; }
        if (!entry) return;
        const info = Object.assign({}, entry.data, { index: i, total: points.length });
        popupOverlay = new StepPopup(google, map, entry.marker, info);
    }

    function setPlaybackUI(frac) {
        const slider = document.getElementById('replaySlider');
        const label = document.getElementById('replayLabel');
        if (slider) slider.value = Math.round(frac * 1000);
        const p = getPosAt(frac);
        const rp = getRoutePos(frac);
        const n = points.length;
        const idx = rp ? rp.index : (p ? Math.max(0, Math.min(Math.round(p.pos), n - 1)) : 0);
        if (rp && truck) truck.setPosition({ lat: rp.lat, lng: rp.lng });
        else if (p && truck) truck.setPosition({ lat: p.lat, lng: p.lng });
        if (label) label.textContent = `${idx + 1} / ${n}`;
        highlightStep(idx);
        if (idx !== lastPopupIdx) {
            lastPopupIdx = idx;
            showStepPopup(idx);
        }
    }

    function updatePlayBtn() {
        const playIcon = document.getElementById('replayPlayIcon');
        const pauseIcon = document.getElementById('replayPauseIcon');
        const playBtn = document.getElementById('replayPlayBtn');
        if (!playIcon || !pauseIcon) return;
        playIcon.classList.toggle('hidden', playing);
        pauseIcon.classList.toggle('hidden', !playing);
        if (playBtn) playBtn.title = playing ? 'Pausar' : 'Reproducir recorrido';
    }

    function frame(ts) {
        if (!lastFrame) lastFrame = ts;
        progress += (ts - lastFrame) / ((points.length - 1) * LEG_DUR);
        lastFrame = ts;
        if (progress >= 1) {
            progress = 1;
            setPlaybackUI(1);
            playing = false;
            updatePlayBtn();
            return;
        }
        setPlaybackUI(progress);
        raf = requestAnimationFrame(frame);
    }

    function startPlay() {
        playing = true;
        lastFrame = 0;
        updatePlayBtn();
        raf = requestAnimationFrame(frame);
    }

    function stopPlay() {
        playing = false;
        if (raf) cancelAnimationFrame(raf);
        raf = null;
        updatePlayBtn();
    }

    function togglePlay() {
        if (playing) { stopPlay(); return; }
        if (progress >= 1) progress = 0;
        startPlay();
    }

    function scrub(v) {
        if (playing) stopPlay();
        progress = v;
        setPlaybackUI(v);
    }

    function focusStep(i) {
        const p = points[i];
        if (!p) return;
        if (map) map.panTo({ lat: p.lat, lng: p.lng });
        if (truck) truck.setPosition({ lat: p.lat, lng: p.lng });
        progress = points.length > 1 ? i / (points.length - 1) : 0;
        setPlaybackUI(progress);
    }
    window.__geo__ = { togglePlay, scrub, focusStep };

    function bindReplayControls() {
        const playBtn = document.getElementById('replayPlayBtn');
        if (playBtn) playBtn.onclick = togglePlay;
        const slider = document.getElementById('replaySlider');
        if (slider) slider.oninput = (e) => scrub(Number(e.target.value) / 1000);
        document.querySelectorAll('[data-step]').forEach((el) => {
            el.onclick = () => focusStep(Number(el.dataset.step));
        });
    }

    function teardownReplay() {
        if (truck) { truck.setMap(null); truck = null; }
        routeSegs = [];
        if (popupOverlay) { popupOverlay.close(); popupOverlay = null; }
        lastPopupIdx = -1;
        popups = [];
        stopPlay();
        progress = 0;
        const slider = document.getElementById('replaySlider');
        if (slider) slider.value = 0;
        const label = document.getElementById('replayLabel');
        if (label) label.textContent = '';
        highlightStep(-1);
    }

    async function initMap(pointsArg, origin, destination) {
        if (typeof window.loadGoogleMaps !== 'function') return;

        const google = await window.loadGoogleMaps(@js($googleMapsApiKey));
        if (!google?.maps) return;

        ensureStepPopupBase(google);

        const mapContainer = document.getElementById('routeMap');
        if (!mapContainer) return;

        teardownReplay();
        points = Array.isArray(pointsArg) ? pointsArg : [];
        routeSegs = [];
        const token = ++routeToken;

        var emptyEl = document.getElementById('geoEmpty'); if (emptyEl) emptyEl.classList.add('hidden');

        if (!map) {
            map = new google.maps.Map(mapContainer, {
                center: { lat: 19.43, lng: -99.13 },
                zoom: 10,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
            });
            map.addListener('click', () => { if (popupOverlay) { popupOverlay.close(); popupOverlay = null; } });
        }

        markers.forEach(m => m.setMap(null));
        markers = [];
        if (activePolyline) { activePolyline.setMap(null); activePolyline = null; }
        if (directionsRenderer) { directionsRenderer.setMap(null); directionsRenderer = null; }

        const bounds = new google.maps.LatLngBounds();
        const latlngs = [];

        if (points.length > 0) {
            points.forEach((p, i) => {
                const pos = { lat: Number(p.lat), lng: Number(p.lng) };
                const color = p.color || stepColors[p.type] || '#64748b';
                const label = p.label || stepLabels[p.type] || `Paso ${i + 1}`;

                const marker = new google.maps.Marker({
                    position: pos,
                    map,
                    icon: createIcon(google, i + 1, color),
                    title: label,
                });

                popups[i] = { marker, data: p };

                marker.addListener('click', () => {
                    lastPopupIdx = i;
                    showStepPopup(i);
                });

                markers.push(marker);
                bounds.extend(pos);
                latlngs.push(pos);
            });

            if (latlngs.length > 1) {
                const maxPoints = 25;
                const truncated = latlngs.length > maxPoints;
                const stops = truncated ? latlngs.slice(0, maxPoints) : latlngs;
                const waypoints = stops.slice(1, -1).map(location => ({ location, stopover: true }));

                const drawLine = () => {
                    if (activePolyline) { activePolyline.setMap(null); activePolyline = null; }
                    activePolyline = new google.maps.Polyline({
                        path: latlngs,
                        geodesic: true,
                        strokeColor: '#E72085',
                        strokeOpacity: 0.7,
                        strokeWeight: 2.5,
                        icons: [{
                            icon: { path: 'M 0,-1 0,1', strokeOpacity: 1, scale: 2 },
                            offset: '0',
                            repeat: '12px',
                        }],
                        map,
                    });
                };

                new google.maps.DirectionsService().route({
                    origin: stops[0],
                    destination: stops[stops.length - 1],
                    waypoints,
                    travelMode: google.maps.TravelMode.DRIVING,
                    optimizeWaypoints: false,
                    provideRouteAlternatives: false,
                }, (result, status) => {
                    if (token !== routeToken) return;
                    if (status === google.maps.DirectionsStatus.OK && result?.routes?.[0]?.overview_path) {
                        routeSegs = (result.routes[0].legs || []).map(leg =>
                            (leg?.steps || []).flatMap(s => (s?.path || []).map(p => ({ lat: p.lat(), lng: p.lng() })))
                        );
                        if (activePolyline) { activePolyline.setMap(null); activePolyline = null; }
                        activePolyline = new google.maps.Polyline({
                            path: result.routes[0].overview_path,
                            strokeColor: '#E72085',
                            strokeOpacity: 0.7,
                            strokeWeight: 2.5,
                            icons: [{
                                icon: { path: 'M 0,-1 0,1', strokeOpacity: 1, scale: 2 },
                                offset: '0',
                                repeat: '12px',
                            }],
                            map,
                        });
                    } else {
                        drawLine();
                    }
                });

                truck = new google.maps.Marker({
                    position: latlngs[0],
                    map,
                    icon: truckIcon(google),
                    zIndex: 100,
                    title: 'Camión',
                });
                setPlaybackUI(0);
                bindReplayControls();
            }

            map.fitBounds(bounds, { top: 60, right: 60, bottom: 60, left: 60 });
        } else if (origin || destination) {
            const geocoder = new google.maps.Geocoder();
            const queries = [];
            if (origin) queries.push({ address: origin, label: 'Origen', color: '#E72085', text: origin });
            if (destination) queries.push({ address: destination, label: 'Destino', color: '#008FD3', text: destination });

            const results = await Promise.all(queries.map(q =>
                new Promise(resolve => {
                    geocoder.geocode({ address: q.address }, (res, status) => {
                        if (status === 'OK' && res && res[0]?.geometry?.location) {
                            resolve({ ...q, position: res[0].geometry.location });
                        } else {
                            resolve(null);
                        }
                    });
                })
            ));

            const valid = results.filter(Boolean);
            if (valid.length === 0) {
                if (document.getElementById('geoEmpty')) document.getElementById('geoEmpty').classList.remove('hidden');
                return;
            }

            valid.forEach((r, i) => {
                const marker = new google.maps.Marker({
                    position: r.position,
                    map,
                    icon: createIcon(google, i === 0 ? 'O' : 'D', r.color),
                    title: r.label,
                });
                marker.addListener('click', () => {
                    new google.maps.InfoWindow({
                        content: `<div style="font-size:12px;font-weight:700">${r.label}</div><div style="font-size:11px;color:#64748b">${r.text}</div>`,
                    }).open(map, marker);
                });
                markers.push(marker);
                bounds.extend(r.position);
            });

            if (valid.length >= 2) {
                directionsRenderer = new google.maps.DirectionsRenderer({
                    map,
                    suppressMarkers: true,
                    polylineOptions: { strokeColor: '#E72085', strokeWeight: 3, strokeOpacity: 0.8 },
                });
                new google.maps.DirectionsService().route({
                    origin: valid[0].position,
                    destination: valid[1].position,
                    travelMode: google.maps.TravelMode.DRIVING,
                }, (result, status) => {
                    if (status === google.maps.DirectionsStatus.OK) directionsRenderer.setDirections(result);
                });
            }

            map.fitBounds(bounds, { top: 60, right: 60, bottom: 60, left: 60 });
        } else {
            if (document.getElementById('geoEmpty')) document.getElementById('geoEmpty').classList.remove('hidden');
        }
    }

    Livewire.on('geolocation:update', (data) => {
        const payload = Array.isArray(data) ? data[0] : data;
        if (!payload) return;
        initMap(payload.points || [], payload.origin || '', payload.destination || '');
    });

    const initialOrigin = @js($routeSummary['origin'] ?? '');
    const initialDest = @js($routeSummary['destination'] ?? '');
    initMap(@js($routePoints), initialOrigin, initialDest);
});
</script>
@endpush

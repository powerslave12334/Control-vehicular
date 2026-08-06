<?php

namespace App\Livewire\Evidence;

use App\Domains\Route\Models\RouteStep;
use App\Domains\Fuel\Models\Refuel;
use App\Domains\Gate\Models\GateLog;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts::app')]
class EvidenceGallery extends Component
{
    public string $filter = 'all';
    public string $filterDateFrom = '';
    public string $filterDateTo = '';
    public int $page = 1;
    protected int $perPage = 24;

    public function previousPage()
    {
        if ($this->page > 1) {
            $this->page--;
        }
    }

    public function nextPage()
    {
        $this->page++;
    }

    public function render()
    {
        $steps = RouteStep::whereNotNull('photo')->where('photo', '!=', '')
            ->with('route')
            ->latest('timestamp')
            ->get()
            ->map(fn($s) => [
                'url' => \Illuminate\Support\Facades\Storage::url($s->photo),
                'label' => match($s->step_type) {
                    'departurePlant' => 'Salida Planta',
                    'arrivalClient' => 'Llegada Cliente',
                    'startInstallation' => 'Inicio Instalación',
                    'endInstallation' => 'Fin Instalación',
                    'returnToPlant' => 'Regreso a Planta',
                    'arrivalPlant' => 'Llegada a Planta',
                    default => $s->step_type,
                },
                'type' => 'trip',
                'date' => $s->timestamp?->format('d/m/Y H:i') ?? '',
                'route' => $s->route?->client_name ?? '—',
            ]);

        $refuels = Refuel::whereNotNull('ticket_photo')->where('ticket_photo', '!=', '')
            ->with('vehicle')
            ->latest('date')
            ->get()
            ->map(fn($r) => [
                'url' => \Illuminate\Support\Facades\Storage::url($r->ticket_photo),
                'label' => 'Ticket Combustible',
                'type' => 'fuel',
                'date' => $r->date?->format('d/m/Y') ?? '',
                'route' => $r->vehicle?->plate ?? '—',
            ]);

        $gates = GateLog::with('vehicle')
            ->latest('logged_at')
            ->get()
            ->map(fn($g) => [
                'url' => \Illuminate\Support\Facades\Storage::url($g->photo),
                'label' => $g->type->value === 'entry' ? 'Entrada Planta' : 'Salida Planta',
                'type' => 'gate',
                'date' => $g->logged_at?->format('d/m/Y H:i') ?? '',
                'route' => $g->vehicle?->plate ?? '—',
            ]);

        $allEvidences = collect()
            ->concat($steps)
            ->concat($refuels)
            ->concat($gates)
            ->sortByDesc('date');

        if ($this->filter !== 'all') {
            $allEvidences = $allEvidences->where('type', $this->filter);
        }

        if ($this->filterDateFrom) {
            $allEvidences = $allEvidences->filter(fn($e) => $e['date'] >= $this->filterDateFrom);
        }
        if ($this->filterDateTo) {
            $allEvidences = $allEvidences->filter(fn($e) => $e['date'] <= $this->filterDateTo . ' 23:59');
        }

        $stats = [
            'total' => $allEvidences->count(),
            'trip' => $allEvidences->where('type', 'trip')->count(),
            'fuel' => $allEvidences->where('type', 'fuel')->count(),
            'gate' => $allEvidences->where('type', 'gate')->count(),
        ];

        $total = $allEvidences->count();
        $lastPage = max(1, (int) ceil($total / $this->perPage));
        if ($this->page > $lastPage) {
            $this->page = $lastPage;
        }

        $paginated = $allEvidences->forPage($this->page, $this->perPage)->values();

        return view('livewire.evidence-gallery', [
            'evidences' => $paginated,
            'stats' => $stats,
            'total' => $total,
            'lastPage' => $lastPage,
        ]);
    }
}

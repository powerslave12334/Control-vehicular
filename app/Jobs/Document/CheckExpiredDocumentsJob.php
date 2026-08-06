<?php

namespace App\Jobs\Document;

use App\Domains\Document\Models\Document;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;

class CheckExpiredDocumentsJob implements ShouldQueue
{
    use Dispatchable, Queueable;

    public int $tries = 2;

    public function handle(): void
    {
        $expired = Document::where('expires_at', '<=', now())->whereNotNull('expires_at')->get();

        foreach ($expired as $document) {
            \App\Domains\Notification\Models\Notification::create([
                'user_id' => 1,
                'type' => 'critical',
                'priority' => 'critical',
                'title' => 'Documento vencido',
                'message' => "Documento #{$document->id} ha expirado",
                'read' => false,
                'related_id' => $document->id,
                'related_type' => Document::class,
            ]);
        }

        $expiringSoon = Document::where('expires_at', '>', now())
            ->where('expires_at', '<=', now()->addDays(30))
            ->whereNotNull('expires_at')
            ->get();

        foreach ($expiringSoon as $document) {
            \App\Domains\Notification\Models\Notification::create([
                'user_id' => 1,
                'type' => 'warning',
                'priority' => 'high',
                'title' => 'Documento por vencer',
                'message' => "Documento #{$document->id} vence el {$document->expires_at->format('d/m/Y')}",
                'read' => false,
                'related_id' => $document->id,
                'related_type' => Document::class,
            ]);
        }
    }
}

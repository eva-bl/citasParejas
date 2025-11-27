<?php

namespace App\Actions\Export;

use App\Models\Couple;
use App\Models\Plan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class ExportPlansToPdfAction
{
    /**
     * Exportar planes de una pareja a PDF
     * 
     * @param Couple $couple
     * @return string Ruta del archivo PDF generado
     */
    public function execute(Couple $couple): string
    {
        $plans = Plan::where('couple_id', $couple->id)
            ->with(['category', 'createdBy', 'ratings.user', 'photos'])
            ->orderBy('date', 'desc')
            ->get();

        $stats = [
            'total' => $plans->count(),
            'completed' => $plans->where('status', 'completed')->count(),
            'pending' => $plans->where('status', 'pending')->count(),
            'avg_rating' => $plans->whereNotNull('overall_avg')->avg('overall_avg'),
            'total_photos' => $plans->sum('photos_count'),
        ];

        $html = view('exports.plans-pdf', [
            'couple' => $couple,
            'plans' => $plans,
            'stats' => $stats,
            'generated_at' => now(),
        ])->render();

        $pdf = Pdf::loadHTML($html);
        $pdf->setPaper('a4', 'portrait');
        
        $filename = 'planes_' . $couple->id . '_' . now()->format('Y-m-d_His') . '.pdf';
        $path = 'exports/' . $filename;
        
        Storage::disk('public')->put($path, $pdf->output());
        
        return Storage::disk('public')->path($path);
    }
}


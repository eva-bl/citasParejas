<?php

namespace App\Actions\Export;

use App\Models\Couple;
use App\Models\Plan;
use Illuminate\Support\Facades\Storage;

class ExportPlansToCsvAction
{
    /**
     * Exportar planes de una pareja a CSV
     * 
     * @param Couple $couple
     * @return string Ruta del archivo CSV generado
     */
    public function execute(Couple $couple): string
    {
        $plans = Plan::where('couple_id', $couple->id)
            ->with(['category', 'createdBy'])
            ->orderBy('date', 'desc')
            ->get();

        $filename = 'planes_' . $couple->id . '_' . now()->format('Y-m-d_His') . '.csv';
        $path = 'exports/' . $filename;
        
        $file = fopen(Storage::disk('public')->path($path), 'w');
        
        // BOM para UTF-8
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Headers
        fputcsv($file, [
            'ID',
            'Título',
            'Fecha',
            'Categoría',
            'Ubicación',
            'Coste (€)',
            'Estado',
            'Valoración Media',
            'Nº Valoraciones',
            'Nº Fotos',
            'Creado Por',
            'Creado En',
        ], ';');
        
        // Data
        foreach ($plans as $plan) {
            fputcsv($file, [
                $plan->id,
                $plan->title,
                $plan->date->format('d/m/Y'),
                $plan->category->name ?? '',
                $plan->location ?? '',
                $plan->cost ? number_format($plan->cost, 2, ',', '.') : '',
                $plan->status === 'completed' ? 'Completado' : 'Pendiente',
                $plan->overall_avg ? number_format($plan->overall_avg, 2, ',', '.') : '',
                $plan->ratings_count ?? 0,
                $plan->photos_count ?? 0,
                $plan->createdBy->name ?? '',
                $plan->created_at->format('d/m/Y H:i'),
            ], ';');
        }
        
        fclose($file);
        
        return Storage::disk('public')->path($path);
    }
}





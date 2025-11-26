<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class AutoGitPush
{
    /**
     * Ejecuta git add, commit y push automáticamente
     * 
     * @param string $message Mensaje del commit
     * @return bool
     */
    public static function push(string $message = 'Auto-commit: Cambios realizados'): bool
    {
        try {
            $basePath = base_path();
            
            // Agregar todos los cambios
            exec("cd {$basePath} && git add -A 2>&1", $output, $returnCode);
            
            if ($returnCode !== 0) {
                Log::error('Error en git add', ['output' => $output]);
                return false;
            }
            
            // Verificar si hay cambios
            exec("cd {$basePath} && git status --porcelain 2>&1", $statusOutput, $statusCode);
            
            if (empty($statusOutput)) {
                // No hay cambios
                return true;
            }
            
            // Hacer commit
            $messageEscaped = escapeshellarg($message);
            exec("cd {$basePath} && git commit -m {$messageEscaped} 2>&1", $commitOutput, $commitCode);
            
            if ($commitCode !== 0) {
                Log::error('Error en git commit', ['output' => $commitOutput]);
                return false;
            }
            
            // Hacer push
            exec("cd {$basePath} && git push origin main 2>&1", $pushOutput, $pushCode);
            
            if ($pushCode !== 0) {
                Log::error('Error en git push', ['output' => $pushOutput]);
                return false;
            }
            
            Log::info('Cambios subidos exitosamente a GitHub', ['message' => $message]);
            return true;
            
        } catch (\Exception $e) {
            Log::error('Error en AutoGitPush', ['error' => $e->getMessage()]);
            return false;
        }
    }
}


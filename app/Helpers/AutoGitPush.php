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
    public static function push(string $message = 'Auto-commit: Changes made'): bool
    {
        try {
            $basePath = base_path();
            
            // Configurar UTF-8 para git
            putenv('LANG=en_US.UTF-8');
            putenv('LC_ALL=en_US.UTF-8');
            
            // Configurar git para UTF-8
            exec("cd {$basePath} && git config --local core.quotepath false 2>&1");
            exec("cd {$basePath} && git config --local i18n.commitencoding utf-8 2>&1");
            exec("cd {$basePath} && git config --local i18n.logoutputencoding utf-8 2>&1");
            
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
            
            // Hacer push con UTF-8
            exec("cd {$basePath} && git -c core.quotepath=false -c i18n.commitencoding=utf-8 -c i18n.logoutputencoding=utf-8 push origin main 2>&1", $pushOutput, $pushCode);
            
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


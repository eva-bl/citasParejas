# Script para hacer commit y push automático de cambios
# Uso: .\.git-auto-push.ps1 "Mensaje del commit"

param(
    [Parameter(Mandatory=$false)]
    [string]$Message = "Auto-commit: Cambios realizados"
)

Write-Host "🔄 Verificando cambios..." -ForegroundColor Cyan

# Agregar todos los cambios
git add -A

# Verificar si hay cambios para commitear
$status = git status --porcelain
if ($status) {
    Write-Host "📝 Cambios detectados, haciendo commit..." -ForegroundColor Yellow
    
    # Hacer commit
    git commit -m $Message
    
    if ($LASTEXITCODE -eq 0) {
        Write-Host "✅ Commit realizado exitosamente" -ForegroundColor Green
        
        # Hacer push
        Write-Host "🚀 Subiendo cambios a GitHub..." -ForegroundColor Cyan
        git push origin main
        
        if ($LASTEXITCODE -eq 0) {
            Write-Host "✅ Cambios subidos exitosamente a GitHub" -ForegroundColor Green
        } else {
            Write-Host "❌ Error al subir cambios" -ForegroundColor Red
            exit 1
        }
    } else {
        Write-Host "❌ Error al hacer commit" -ForegroundColor Red
        exit 1
    }
} else {
    Write-Host "ℹ️  No hay cambios para commitear" -ForegroundColor Gray
}


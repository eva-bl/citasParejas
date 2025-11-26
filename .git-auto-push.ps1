# Script para hacer commit y push automático de cambios
# Uso: .\.git-auto-push.ps1 "Mensaje del commit"
# Configurado para usar UTF-8

param(
    [Parameter(Mandatory=$false)]
    [string]$Message = "Auto-commit: Cambios realizados"
)

# Configurar UTF-8
[Console]::OutputEncoding = [System.Text.Encoding]::UTF8
$OutputEncoding = [System.Text.Encoding]::UTF8
$env:LANG = "en_US.UTF-8"
$env:LC_ALL = "en_US.UTF-8"

Write-Host "🔄 Verificando cambios..." -ForegroundColor Cyan

# Configurar git para UTF-8
git config --local core.quotepath false
git config --local i18n.commitencoding utf-8
git config --local i18n.logoutputencoding utf-8

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
        
        # Hacer push con UTF-8
        Write-Host "🚀 Subiendo cambios a GitHub..." -ForegroundColor Cyan
        git -c core.quotepath=false -c i18n.commitencoding=utf-8 -c i18n.logoutputencoding=utf-8 push origin main
        
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


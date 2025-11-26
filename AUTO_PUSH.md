# 🔄 Auto-Push a GitHub

Este proyecto está configurado para hacer commit y push automático de cambios a GitHub.

## ✅ Configuración Automática

**¡Auto-push está ACTIVADO!** 

Después de cada commit, los cambios se suben automáticamente a GitHub mediante un hook de git (`.git-hooks/post-commit`).

## 📋 Scripts Disponibles

### PowerShell (Windows) - Para uso manual
```powershell
.\.git-auto-push.ps1 "Mensaje del commit"
```

### Sin mensaje (usa mensaje por defecto)
```powershell
.\.git-auto-push.ps1
```

## 🤖 Uso Automático

El asistente ejecutará automáticamente:
1. `git add -A` - Agregar todos los cambios
2. `git commit -m "mensaje descriptivo"` - Commit con mensaje descriptivo
3. `git push origin main` - Push automático (vía hook)

Los commits incluirán mensajes descriptivos del trabajo realizado (ej: "Sprint 5: Sistema de Fotos completo").

## ⚙️ Configuración Manual

Si prefieres hacer commits manualmente:

```bash
git add -A
git commit -m "Tu mensaje"
git push origin main
```

## 📝 Notas

- El script verifica si hay cambios antes de hacer commit
- Solo hace push si el commit fue exitoso
- Los mensajes de commit son descriptivos del trabajo realizado


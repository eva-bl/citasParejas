# 🔄 Auto-Push a GitHub

Este proyecto está configurado para hacer commit y push automático de cambios a GitHub.

## 📋 Scripts Disponibles

### PowerShell (Windows)
```powershell
.\.git-auto-push.ps1 "Mensaje del commit"
```

### Sin mensaje (usa mensaje por defecto)
```powershell
.\.git-auto-push.ps1
```

## 🤖 Uso Automático

El asistente ejecutará automáticamente el script después de cambios importantes. Los commits incluirán mensajes descriptivos del trabajo realizado.

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


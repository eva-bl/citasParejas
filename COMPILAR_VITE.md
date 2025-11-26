# 🚀 Compilar Assets con Vite

## Pasos para Compilar

### 1. Instalar Dependencias (si no lo has hecho)

```bash
npm install
```

Esto instalará Alpine.js y todas las demás dependencias necesarias.

### 2. Compilar Assets para Producción

```bash
npm run build
```

Esto generará los archivos optimizados en `public/build/` y creará el `manifest.json` necesario.

### 3. Verificar

Después de compilar, deberías ver:
- `public/build/manifest.json` (archivo generado)
- `public/build/assets/` (carpeta con archivos CSS y JS compilados)

## Para Desarrollo con Hot Reload

Si quieres trabajar en modo desarrollo (con recarga automática):

```bash
npm run dev
```

Esto iniciará el servidor de Vite que recargará automáticamente cuando hagas cambios.

**Nota:** Deja esta terminal corriendo mientras desarrollas.

## Solución de Problemas

### Error: "npm no se reconoce"
- Asegúrate de tener Node.js instalado
- O usa el terminal de Herd si tiene Node.js configurado

### Error: "Cannot find module"
- Ejecuta `npm install` primero
- Verifica que `node_modules` existe

### Error: "Vite manifest not found"
- Ejecuta `npm run build` después de `npm install`
- Verifica que `public/build/manifest.json` existe

## Comandos Rápidos

```bash
# Instalar dependencias
npm install

# Compilar para producción
npm run build

# Modo desarrollo (hot reload)
npm run dev
```


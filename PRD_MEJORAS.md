# Mejoras y Optimizaciones al PRD - Aplicación "Valorar Planes en Pareja"

## 1. MEJORAS EN MODELO DE DATOS

### 1.1. Índices y Optimización de Consultas

**Problema identificado:**
- Faltan índices en campos frecuentemente consultados
- Las consultas de estadísticas pueden ser lentas sin índices adecuados

**Mejoras propuestas:**

```sql
-- Tabla plans
- Índice compuesto: (couple_id, date, status)
- Índice: (couple_id, category_id)
- Índice: (created_by, date)

-- Tabla ratings
- Índice único compuesto: (plan_id, user_id) -- Ya mencionado pero debe ser constraint único
- Índice: (user_id, created_at) para estadísticas individuales
- Índice: (plan_id) para cálculos de medias

-- Tabla photos
- Índice: (plan_id, created_at) para ordenamiento

-- Tabla user_badges
- Índice: (user_id, obtained_at)
- Índice: (badge_id) para estadísticas de insignias

-- Tabla user_plan_favorites
- Índice único compuesto: (user_id, plan_id)
- Índice: (user_id) para listado rápido
```

### 1.2. Campos Calculados Cacheados

**Problema:** Calcular medias en tiempo real puede ser costoso con muchos planes.

**Solución:** Agregar campos cacheados en `plans`:

```php
// Agregar a tabla plans:
- overall_avg (decimal 3,2) - Media de overall
- fun_avg (decimal 3,2)
- emotional_connection_avg (decimal 3,2)
- organization_avg (decimal 3,2)
- value_for_money_avg (decimal 3,2)
- ratings_count (int) - Número de valoraciones
- photos_count (int) - Número de fotos (para evitar COUNT en listados)
- last_rated_at (timestamp) - Última vez que se valoró
```

**Ventajas:**
- Consultas más rápidas en listados
- Menos carga en base de datos
- Actualización mediante Observers/Events

### 1.3. Tabla de Historial de Cambios (Auditoría)

**Mejora:** Agregar tabla `plan_activity_log` para tracking:

```php
// Tabla plan_activity_log
- id
- plan_id (FK)
- user_id (FK) - Quien hizo el cambio
- action (enum: 'created', 'updated', 'status_changed', 'rated', 'photo_added')
- old_values (json, nullable)
- new_values (json, nullable)
- created_at
```

**Beneficios:**
- Historial completo de cambios
- Debugging más fácil
- Posible feature futura: "Timeline de actividad del plan"

### 1.4. Mejora en Sistema de Insignias

**Problema:** Los criterios son muy rígidos con `criteria_type` y `criteria_value`.

**Mejora:** Usar JSON para criterios más flexibles:

```php
// Tabla badges
- criteria (json) // En lugar de criteria_type y criteria_value
// Ejemplo:
{
  "type": "total_completed_plans",
  "value": 10,
  "timeframe": "all_time" // o "month", "year"
}
// O múltiples criterios:
{
  "operator": "AND",
  "conditions": [
    {"type": "total_completed_plans", "value": 5},
    {"type": "avg_rating", "value": 4.5}
  ]
}
```

### 1.5. Soft Deletes en Fotos

**Mejora:** Agregar `deleted_at` a `photos` para recuperación de fotos eliminadas accidentalmente.

---

## 2. MEJORAS EN ARQUITECTURA Y PERFORMANCE

### 2.1. Sistema de Caché para Estadísticas

**Problema:** Las estadísticas se calculan en cada request.

**Solución:**
- Cachear estadísticas de pareja con TTL de 1 hora
- Invalidar cache cuando:
  - Se crea/edita/elimina un plan
  - Se crea/edita una valoración
  - Se completa un plan

```php
// Ejemplo de estructura de cache:
Cache::tags(['couple_stats', "couple_{$coupleId}"])
    ->remember("couple_{$coupleId}_stats", 3600, function() {
        // Calcular estadísticas
    });
```

### 2.2. Optimización de Imágenes

**Mejora:** Procesar imágenes al subirlas:

- Generar thumbnails automáticamente (150x150, 300x300, 800x800)
- Convertir a WebP automáticamente (mantener original como backup)
- Usar Intervention Image o Spatie Image
- Guardar múltiples tamaños en storage

**Estructura propuesta:**
```
storage/app/public/couples/{couple_id}/plans/{plan_id}/
  - original/
    - photo1.jpg
  - thumbnails/
    - photo1_150x150.webp
    - photo1_300x300.webp
    - photo1_800x800.webp
```

### 2.3. Eager Loading Estratégico

**Mejora:** Definir relaciones siempre con eager loading en consultas frecuentes:

```php
// En Plan model
public static function forCouple($coupleId) {
    return static::where('couple_id', $coupleId)
        ->with(['category', 'createdBy', 'ratings.user', 'photos'])
        ->withCount(['ratings', 'photos']);
}
```

### 2.4. Jobs Asíncronos para Tareas Pesadas

**Tareas que deberían ser Jobs:**
- Procesamiento de imágenes (thumbnails, conversión WebP)
- Cálculo de estadísticas complejas
- Asignación de insignias (verificar múltiples criterios)
- Exportación a PDF/CSV
- Limpieza de archivos huérfanos

---

## 3. MEJORAS EN UX/UI

### 3.1. Sistema de Notificaciones In-App

**Mejora:** Agregar notificaciones cuando:
- Tu pareja crea un nuevo plan
- Tu pareja valora un plan que tú creaste
- Tu pareja completa un plan
- Obtienes una nueva insignia
- Tu pareja sube fotos a un plan

**Implementación:**
- Tabla `notifications` (Laravel ya tiene sistema de notificaciones)
- Bell icon en navbar con contador
- Mark as read/unread

### 3.2. Búsqueda Avanzada

**Mejora:** Agregar búsqueda full-text en:
- Título de planes
- Descripción de planes
- Comentarios de valoraciones
- Ubicación

**Implementación:**
- Usar Laravel Scout con MySQL full-text search
- O implementar búsqueda simple con LIKE optimizado

### 3.3. Filtros Mejorados

**Mejoras adicionales:**
- Filtro por rango de valoración (ej: planes con overall >= 4)
- Filtro por rango de coste
- Filtro por "planes sin valorar"
- Filtro por "planes sin fotos"
- Filtro por "planes favoritos"

### 3.4. Vista de Comparación de Valoraciones

**Nueva feature:** Mostrar comparación lado a lado:
- Cómo valoraste tú vs cómo valoró tu pareja
- Gráfico de barras comparativo
- Identificar discrepancias (ej: tú 5, pareja 2)

### 3.5. Recordatorios de Planes

**Mejora:** Sistema de recordatorios:
- Notificación 1 día antes del plan
- Notificación el día del plan
- Configurable por usuario

---

## 4. MEJORAS EN SEGURIDAD

### 4.1. Validación de Join Code

**Mejora:**
- Join codes con expiración (ej: 7 días)
- Join codes de un solo uso (después de que alguien se una, invalidar)
- Rate limiting en intentos de unirse a pareja

### 4.2. Políticas de Autorización Más Granulares

**Mejora:** Crear Policies específicas:
- `PlanPolicy` - Ver, crear, editar, eliminar planes
- `RatingPolicy` - Crear, editar valoraciones
- `PhotoPolicy` - Subir, eliminar fotos
- `CouplePolicy` - Abandonar pareja, ver estadísticas

### 4.3. Validación de Archivos Más Estricta

**Mejora:**
- Validar tipo MIME real (no solo extensión)
- Escanear imágenes con antivirus (opcional, para producción)
- Límite de tamaño por usuario/couple (no solo por plan)

### 4.4. Rate Limiting Específico

**Mejora:** Agregar rate limiting en:
- Creación de planes (ej: max 10 por día)
- Subida de fotos (ej: max 50 por día)
- Valoraciones (ej: max 20 por día)

---

## 5. MEJORAS EN GAMIFICACIÓN

### 5.1. Sistema de Logros por Pareja

**Mejora:** Además de insignias individuales, agregar logros de pareja:
- "Primera cita" - Completar el primer plan juntos
- "Exploradores" - Visitar 10 ubicaciones diferentes
- "Gourmets" - Completar 5 planes de categoría "Cena"
- "Aventureros" - Completar 3 planes de categoría "Aventura"
- "Consistencia" - Completar al menos 1 plan por mes durante 6 meses

### 5.2. Puntos y Ranking

**Mejora opcional:** Sistema de puntos:
- Puntos por completar plan: 10
- Puntos por valorar: 5
- Puntos por subir fotos: 2 por foto
- Bonus por alta valoración (overall >= 4.5): +5 puntos

### 5.3. Insignias Temporales

**Mejora:** Insignias que se renuevan:
- "Plan del mes" - Mejor plan del mes actual
- "Más activos este mes" - Mayor número de planes en el mes

---

## 6. MEJORAS EN ESTADÍSTICAS

### 6.1. Estadísticas Comparativas

**Mejora:** Comparar períodos:
- Este mes vs mes pasado
- Este año vs año pasado
- Evolución de valoraciones por trimestre

### 6.2. Insights Automáticos

**Mejora:** Generar insights automáticos:
- "Tu categoría favorita este mes es..."
- "Tus planes mejor valorados son los de..."
- "Has mejorado X% respecto al mes pasado"
- "Tu pareja valora más los planes de tipo..."

### 6.3. Heatmap de Actividad

**Mejora:** Calendario tipo GitHub contributions:
- Mostrar días con más actividad
- Días con planes completados
- Patrones de actividad (ej: más activos los fines de semana)

### 6.4. Análisis de Coste-Beneficio

**Mejora:** Estadísticas de relación coste/valoración:
- Planes más "rentables" (alta valoración, bajo coste)
- Planes menos "rentables" (baja valoración, alto coste)
- Media de gasto por categoría

---

## 7. MEJORAS TÉCNICAS ADICIONALES

### 7.1. API REST (Opcional para Futuro)

**Preparación:** Estructurar código para posible API:
- Usar Resources/Transformers desde el inicio
- Separar lógica de negocio en Services/Actions
- Considerar API versioning

### 7.2. Sistema de Etiquetas (Tags)

**Mejora:** Además de categorías, permitir tags libres:
- Tabla `tags` y `plan_tag` (pivot)
- Ejemplos: "romántico", "divertido", "lluvioso", "sorpresa"
- Filtrado por tags
- Estadísticas por tags

### 7.3. Planes Recurrentes

**Mejora:** Permitir crear planes recurrentes:
- "Cena cada viernes"
- "Cine cada primer sábado del mes"
- Generar planes automáticamente

### 7.4. Compartir Planes (Futuro)

**Preparación:** Estructura para compartir:
- Campo `is_public` en plans (default: false)
- Código de compartir único por plan
- Vista pública de plan (solo lectura, sin datos sensibles)

### 7.5. Integración con Calendarios Externos

**Mejora futura:** Exportar a Google Calendar, iCal:
- Generar archivo .ics
- Sincronización bidireccional (futuro)

---

## 8. MEJORAS EN MANTENIBILIDAD

### 8.1. Estructura de Código

**Recomendación:**
```
app/
  Actions/
    Plan/
      CreatePlanAction.php
      UpdatePlanAction.php
      CompletePlanAction.php
    Rating/
      CreateRatingAction.php
      UpdateRatingAction.php
    Badge/
      CheckAndAssignBadgesAction.php
  Services/
    StatisticsService.php
    ImageProcessingService.php
    BadgeEvaluationService.php
  DTOs/
    PlanData.php
    RatingData.php
  Policies/
    PlanPolicy.php
    RatingPolicy.php
```

### 8.2. Tests Estratégicos

**Prioridades:**
1. Tests de lógica de negocio (Actions/Services)
2. Tests de Policies (autorización)
3. Tests de cálculo de estadísticas
4. Tests de asignación de insignias
5. Tests E2E de flujos principales (crear plan, valorar, ver estadísticas)

### 8.3. Logging Estructurado

**Mejora:** Logging de acciones importantes:
- Creación/edición de planes
- Valoraciones
- Cambios de estado
- Errores en procesamiento de imágenes
- Intentos de acceso no autorizado

---

## 9. MEJORAS EN ESCALABILIDAD

### 9.1. Particionamiento de Fotos

**Mejora:** Para parejas con muchas fotos:
- Considerar almacenamiento en S3/Cloud Storage (futuro)
- Implementar lazy loading de imágenes
- Paginación en galería de fotos

### 9.2. Archivo de Planes Antiguos

**Mejora:** Para parejas muy activas:
- Opción de "archivar" planes de más de X años
- Mantener en BD pero ocultos por defecto
- Reducir carga en consultas principales

### 9.3. Optimización de Consultas de Estadísticas

**Mejora:** Para parejas con muchos planes:
- Usar materialized views (si PostgreSQL)
- O tablas de agregación actualizadas periódicamente
- Jobs programados para recalcular estadísticas pesadas

---

## 10. MEJORAS EN FUNCIONALIDADES

### 10.1. Modo "Sorpresa"

**Mejora:** Planes donde un usuario no puede ver detalles hasta el día:
- Campo `is_surprise` en plans
- Ocultar detalles hasta fecha del plan
- Notificación especial cuando se revela

### 10.2. Wishlist de Planes

**Mejora:** Lista de deseos de planes:
- Planes que quieres hacer pero aún no están programados
- Tu pareja puede ver tu wishlist
- Convertir wishlist a plan real

### 10.3. Plantillas de Planes

**Mejora:** Guardar planes como plantillas:
- "Cena romántica" (con categoría, descripción predefinida)
- Reutilizar para crear planes similares rápidamente

### 10.4. Notas Privadas

**Mejora:** Notas que solo tú puedes ver:
- Campo `private_notes` en plans (solo visible para creador)
- Útil para recordatorios personales

---

## RESUMEN DE PRIORIDADES

### MVP (Debe incluirse):
1. ✅ Índices en BD
2. ✅ Campos cacheados para medias
3. ✅ Procesamiento básico de imágenes (thumbnails)
4. ✅ Eager loading en consultas principales
5. ✅ Policies de autorización

### Iteración 1 (Alta prioridad):
1. Sistema de notificaciones in-app
2. Búsqueda avanzada
3. Caché de estadísticas
4. Sistema de logros por pareja
5. Estadísticas comparativas

### Iteración 2 (Media prioridad):
1. Recordatorios de planes
2. Vista de comparación de valoraciones
3. Heatmap de actividad
4. Sistema de tags
5. Modo sorpresa

### Futuro (Baja prioridad):
1. API REST
2. Compartir planes públicamente
3. Integración con calendarios externos
4. Planes recurrentes
5. Wishlist

---

## NOTAS FINALES

- Todas las mejoras están pensadas para mantener la simplicidad del MVP
- Las mejoras de performance son críticas para buena UX
- El sistema de caché y campos calculados evitará problemas de escalabilidad
- Las mejoras de UX aumentarán el engagement de los usuarios
- La estructura de código propuesta facilitará mantenimiento y testing





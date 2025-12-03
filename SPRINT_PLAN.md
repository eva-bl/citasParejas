# Plan de Sprints - Aplicación "Valorar Planes en Pareja"

## 📋 Resumen Ejecutivo

**Total de Sprints:** 10 sprints
**Duración estimada por sprint:** 1-2 semanas
**Tecnologías:** Laravel 12, FilamentPHP, Livewire, Alpine.js, MySQL

---

## 🚀 SPRINT 1: Setup y Modelo de Datos Base
**Duración:** 3-5 días  
**Objetivo:** Estructura base de BD, modelos y relaciones

### Tareas:
- [ ] **1.1** Crear migración `couples` (id, join_code, timestamps)
- [ ] **1.2** Crear migración `categories` (id, name, icon, color, timestamps)
- [ ] **1.3** Crear migración `plans` con campos cacheados:
  - Campos base: couple_id, title, date, category_id, location, cost, description, created_by, status
  - Campos cacheados: overall_avg, fun_avg, emotional_connection_avg, organization_avg, value_for_money_avg, ratings_count, photos_count
  - Soft deletes: deleted_at
- [ ] **1.4** Crear migración `ratings` (plan_id, user_id, fun, emotional_connection, organization, value_for_money, overall, comment)
  - Constraint único: (plan_id, user_id)
- [ ] **1.5** Crear migración `photos` (plan_id, path, timestamps, deleted_at)
- [ ] **1.6** Crear migración `badges` (name, description, icon, criteria JSON, timestamps)
- [ ] **1.7** Crear migración `user_badges` (user_id, badge_id, obtained_at)
- [ ] **1.8** Crear migración `user_plan_favorites` (user_id, plan_id, timestamps)
- [ ] **1.9** Crear migración `plan_activity_log` (plan_id, user_id, action, old_values JSON, new_values JSON)
- [ ] **1.10** Agregar índices optimizados en todas las tablas
- [ ] **1.11** Actualizar migración `users` para agregar `couple_id` y `avatar_path`
- [ ] **1.12** Crear modelos: Couple, Category, Plan, Rating, Photo, Badge, UserBadge, PlanFavorite, PlanActivityLog
- [ ] **1.13** Definir relaciones Eloquent en todos los modelos
- [ ] **1.14** Configurar soft deletes donde corresponda
- [ ] **1.15** Crear factories para: Category, Plan, Rating, Photo, Badge
- [ ] **1.16** Crear seeder con categorías iniciales y datos de prueba

**Entregables:**
- ✅ Migraciones completas con índices
- ✅ Modelos con relaciones
- ✅ Seeders funcionales

---

## 🔐 SPRINT 2: Autenticación y Emparejamiento
**Duración:** 3-5 días  
**Objetivo:** Sistema de registro, login y emparejamiento de usuarios

### Tareas:
- [ ] **2.1** Configurar Fortify para registro y login
- [ ] **2.2** Crear formulario de registro (name, email, password)
- [ ] **2.3** Validaciones de registro (email único, password mínimo 8 caracteres)
- [ ] **2.4** Crear formulario de login
- [ ] **2.5** Implementar Action `CreateCoupleAction`:
  - Generar join_code único
  - Asignar couple_id al usuario creador
- [ ] **2.6** Implementar Action `JoinCoupleAction`:
  - Validar join_code
  - Asignar couple_id al usuario
- [ ] **2.7** Crear Livewire component para "Crear Pareja"
- [ ] **2.8** Crear Livewire component para "Unirse a Pareja"
- [ ] **2.9** Crear Policy `CouplePolicy` (solo usuarios de la pareja pueden ver/editar)
- [ ] **2.10** Middleware para verificar que usuario tiene couple_id (excepto en registro/emparejamiento)
- [ ] **2.11** Vista dashboard inicial con estado de emparejamiento
- [ ] **2.12** Tests: registro, login, crear pareja, unirse a pareja

**Entregables:**
- ✅ Usuarios pueden registrarse e iniciar sesión
- ✅ Sistema de emparejamiento funcional
- ✅ Protección de rutas según couple_id

---

## 📅 SPRINT 3: CRUD de Planes
**Duración:** 5-7 días  
**Objetivo:** Crear, editar, eliminar y listar planes con filtros

### Tareas:
- [ ] **3.1** Crear Action `CreatePlanAction`:
  - Validar que usuario tiene couple_id
  - Asignar couple_id automáticamente
  - Status por defecto: "pending"
- [ ] **3.2** Crear Action `UpdatePlanAction`
- [ ] **3.3** Crear Action `DeletePlanAction` (soft delete)
- [ ] **3.4** Crear Policy `PlanPolicy`:
  - Ver: usuarios de la pareja
  - Crear: usuarios con couple_id
  - Editar/Eliminar: usuarios de la pareja
- [ ] **3.5** Crear Livewire component `CreatePlanForm`:
  - Campos: title, date, category_id, location, cost, description
  - Validaciones
  - Select de categorías
- [ ] **3.6** Crear Livewire component `EditPlanForm`
- [ ] **3.7** Crear Livewire component `PlansList`:
  - Listado paginado
  - Filtros: categoría, rango fechas, estado, creador
  - Ordenación: fecha, valoración, coste
  - Búsqueda básica
- [ ] **3.8** Crear Livewire component `PlanDetail`:
  - Mostrar datos completos
  - Botones editar/eliminar
  - Sección de valoraciones (placeholder)
  - Sección de fotos (placeholder)
- [ ] **3.9** Implementar Observer `PlanObserver`:
  - Log de actividad en `plan_activity_log`
  - Invalidar caché de estadísticas al crear/editar/eliminar
- [ ] **3.10** Vista de listado de planes con filtros
- [ ] **3.11** Vista de detalle de plan
- [ ] **3.12** Tests: crear, editar, eliminar, listar planes

**Entregables:**
- ✅ CRUD completo de planes
- ✅ Filtros y búsqueda funcionales
- ✅ Políticas de autorización implementadas

---

## ⭐ SPRINT 4: Sistema de Valoraciones
**Duración:** 4-6 días  
**Objetivo:** Valorar planes y calcular medias automáticamente

### Tareas:
- [ ] **4.1** Crear Action `CreateOrUpdateRatingAction`:
  - Validar que plan pertenece a pareja del usuario
  - Crear o actualizar valoración
  - Calcular y actualizar medias en plan
- [ ] **4.2** Crear Action `CalculatePlanAveragesAction`:
  - Calcular medias de todos los criterios
  - Actualizar campos cacheados en plan
- [ ] **4.3** Crear Policy `RatingPolicy`:
  - Solo usuarios de la pareja pueden valorar
  - Solo pueden valorar una vez (editable)
- [ ] **4.4** Crear Livewire component `RatingForm`:
  - 5 sliders/inputs (1-5): fun, emotional_connection, organization, value_for_money, overall
  - Campo comment (textarea)
  - Mostrar valoración existente si existe
- [ ] **4.5** Crear Livewire component `PlanRatingsDisplay`:
  - Mostrar valoración del usuario actual (editable)
  - Mostrar valoración de la pareja (solo lectura)
  - Mostrar medias del plan
- [ ] **4.6** Integrar formulario de valoración en vista detalle de plan
- [ ] **4.7** Implementar Observer `RatingObserver`:
  - Recalcular medias al crear/actualizar/eliminar
  - Invalidar caché de estadísticas
- [ ] **4.8** Agregar validación: solo un rating por usuario por plan
- [ ] **4.9** Mostrar indicador visual de planes sin valorar en listado
- [ ] **4.10** Tests: crear, editar valoraciones, cálculo de medias

**Entregables:**
- ✅ Sistema de valoración completo
- ✅ Cálculo automático de medias
- ✅ Visualización de valoraciones

---

## 📸 SPRINT 5: Sistema de Fotos
**Duración:** 5-7 días  
**Objetivo:** Subir, procesar y visualizar fotos de planes

### Tareas:
- [ ] **5.1** Instalar paquete de procesamiento de imágenes (Intervention Image o Spatie Image)
- [ ] **5.2** Crear Service `ImageProcessingService`:
  - Generar thumbnails (150x150, 300x300, 800x800)
  - Convertir a WebP
  - Guardar estructura organizada: `couples/{couple_id}/plans/{plan_id}/`
- [ ] **5.3** Crear Action `UploadPlanPhotosAction`:
  - Validar formato (JPG, PNG, WEBP)
  - Validar tamaño máximo (configurable)
  - Validar número máximo de fotos por plan
  - Procesar imágenes
  - Guardar registros en BD
- [ ] **5.4** Crear Action `DeletePlanPhotoAction` (soft delete)
- [ ] **5.5** Crear Policy `PhotoPolicy`
- [ ] **5.6** Crear Livewire component `PhotoUpload`:
  - Upload múltiple
  - Preview de imágenes antes de subir
  - Barra de progreso
- [ ] **5.7** Crear Livewire component `PhotoGallery`:
  - Grid de miniaturas
  - Lightbox con Alpine.js para ver fotos completas
  - Botón eliminar por foto
- [ ] **5.8** Integrar galería en vista detalle de plan
- [ ] **5.9** Crear Job `ProcessPlanPhotosJob` (asíncrono):
  - Procesar imágenes en background
  - Notificar cuando termine
- [ ] **5.10** Configurar storage link público
- [ ] **5.11** Agregar validación MIME real (no solo extensión)
- [ ] **5.12** Tests: subir, eliminar fotos, procesamiento

**Entregables:**
- ✅ Sistema de fotos completo
- ✅ Procesamiento automático de imágenes
- ✅ Galería interactiva

---

## 🎛️ SPRINT 6: Panel Administrativo Filament
**Duración:** 4-6 días  
**Objetivo:** Panel admin completo para gestionar todas las entidades

### Tareas:
- [ ] **6.1** Instalar y configurar Filament
- [ ] **6.2** Crear Filament Resource `UserResource`:
  - Listado con filtros
  - Formulario crear/editar
  - Relación con couple
- [ ] **6.3** Crear Filament Resource `CoupleResource`:
  - Listado con usuarios de la pareja
  - Ver join_code
  - Acción para regenerar join_code
- [ ] **6.4** Crear Filament Resource `CategoryResource`:
  - CRUD completo
  - Selector de icono (emoji o icono)
  - Selector de color
- [ ] **6.5** Crear Filament Resource `PlanResource`:
  - Listado con relaciones (category, createdBy, couple)
  - Filtros avanzados
  - Formulario completo
  - Ver valoraciones y fotos relacionadas
- [ ] **6.6** Crear Filament Resource `RatingResource`
- [ ] **6.7** Crear Filament Resource `PhotoResource`
- [ ] **6.8** Crear Filament Resource `BadgeResource`:
  - Editor JSON para criteria
- [ ] **6.9** Crear Filament Resource `UserBadgeResource`
- [ ] **6.10** Crear Widget `AdminStatsWidget`:
  - Total usuarios, parejas, planes, valoraciones
- [ ] **6.11** Configurar roles: admin vs user
- [ ] **6.12** Middleware para acceso solo a admins
- [ ] **6.13** Tests: acceso admin, CRUD en panel

**Entregables:**
- ✅ Panel admin completo
- ✅ Gestión de todas las entidades
- ✅ Control de acceso por roles

---

## 📊 SPRINT 7: Estadísticas Básicas
**Duración:** 5-7 días  
**Objetivo:** Dashboard de estadísticas con caché

### Tareas:
- [ ] **7.1** Crear Service `StatisticsService`:
  - Métodos para calcular estadísticas de pareja
  - Métodos para calcular estadísticas individuales
- [ ] **7.2** Implementar caché de estadísticas:
  - Tags: `couple_stats`, `user_stats`
  - TTL: 1 hora
  - Invalidación automática
- [ ] **7.3** Crear Action `GetCoupleStatisticsAction`:
  - Nota media global
  - Categoría mejor/peor valorada
  - Total planes, planes completados
  - Distribución por meses
- [ ] **7.4** Crear Action `GetUserStatisticsAction`:
  - Media de valoraciones del usuario
  - Planes creados
  - Planes valorados
- [ ] **7.5** Crear Livewire component `CoupleStatsDashboard`:
  - Cards con KPIs principales
  - Gráfico de evolución de notas (Chart.js o similar)
  - Tabla de categorías con medias
- [ ] **7.6** Crear Livewire component `UserStatsDashboard`
- [ ] **7.7** Crear Filament Widget `StatsOverviewWidget`
- [ ] **7.8** Crear Filament Widget `RatingsChartWidget`
- [ ] **7.9** Vista de dashboard de estadísticas
- [ ] **7.10** Implementar invalidación de caché en Observers
- [ ] **7.11** Tests: cálculo de estadísticas, caché

**Entregables:**
- ✅ Dashboard de estadísticas funcional
- ✅ Caché implementado
- ✅ Gráficos y visualizaciones

---

## 🏆 SPRINT 8: Gamificación - Insignias
**Duración:** 5-7 días  
**Objetivo:** Sistema de insignias y asignación automática

### Tareas:
- [ ] **8.1** Crear Service `BadgeEvaluationService`:
  - Evaluar criterios de insignias
  - Métodos para cada tipo de criterio
- [ ] **8.2** Crear Action `CheckAndAssignBadgesAction`:
  - Evaluar todas las insignias para un usuario
  - Asignar nuevas insignias
- [ ] **8.3** Crear Job `EvaluateUserBadgesJob` (asíncrono)
- [ ] **8.4** Disparar evaluación de insignias:
  - Al completar un plan
  - Al crear una valoración
  - Job programado diario
- [ ] **8.5** Crear seeder con insignias iniciales:
  - "Primera cita" (completar 1 plan)
  - "Exploradores" (10 planes completados)
  - "Gourmets" (5 planes categoría "Cena")
  - "Alta valoración" (plan con overall >= 4.5)
  - "Consistencia" (1 plan/mes durante 6 meses)
- [ ] **8.6** Crear Livewire component `UserBadgesDisplay`:
  - Grid de insignias conseguidas
  - Insignias bloqueadas (opcional)
- [ ] **8.7** Crear Livewire component `CoupleBadgesDisplay`:
  - Insignias de ambos usuarios
- [ ] **8.8** Vista de insignias del usuario
- [ ] **8.9** Mostrar insignias recientes en dashboard
- [ ] **8.10** Notificación cuando se obtiene una insignia
- [ ] **8.11** Tests: evaluación de criterios, asignación de insignias

**Entregables:**
- ✅ Sistema de insignias completo
- ✅ Asignación automática
- ✅ Visualización de logros

---

## 🎨 SPRINT 9: Mejoras UI/UX
**Duración:** 5-7 días  
**Objetivo:** Calendario, favoritos, búsqueda avanzada

### Tareas:
- [ ] **9.1** Crear Livewire component `PlansCalendar`:
  - Vista mensual
  - Mostrar planes por día
  - Colores por categoría
  - Indicador de estado (pending/completed)
- [ ] **9.2** Navegación entre meses
- [ ] **9.3** Modal con detalles al hacer clic en plan
- [ ] **9.4** Crear Action `TogglePlanFavoriteAction`
- [ ] **9.5** Crear Livewire component `FavoritePlansList`
- [ ] **9.6** Botón "Marcar como favorito" en detalle de plan
- [ ] **9.7** Implementar búsqueda full-text:
  - En título, descripción, ubicación, comentarios
  - Usar Laravel Scout o LIKE optimizado
- [ ] **9.8** Mejorar filtros en listado de planes:
  - Rango de valoración
  - Rango de coste
  - Planes sin valorar
  - Planes sin fotos
  - Solo favoritos
- [ ] **9.9** Crear Action `GetPlanOfTheYearAction`:
  - Identificar mejor plan por año
- [ ] **9.10** Mostrar "Plan estrella del año" en dashboard
- [ ] **9.11** Mejorar responsive design
- [ ] **9.12** Tests: calendario, favoritos, búsqueda

**Entregables:**
- ✅ Calendario funcional
- ✅ Sistema de favoritos
- ✅ Búsqueda avanzada

---

## ✨ SPRINT 10: Pulido Final
**Duración:** 5-7 días  
**Objetivo:** Notificaciones, exportación, tests completos, optimizaciones

### Tareas:
- [ ] **10.1** Implementar sistema de notificaciones in-app:
  - Tabla notifications (Laravel)
  - Notificar: nuevo plan, valoración, insignia, fotos subidas
- [ ] **10.2** Crear Livewire component `NotificationsBell`:
  - Contador de no leídas
  - Dropdown con notificaciones
  - Mark as read
- [ ] **10.3** Crear Action `ExportPlansToPdfAction`:
  - Usar DomPDF o similar
  - Incluir planes, valoraciones, miniatura de foto destacada
  - Estadísticas resumen
- [ ] **10.4** Crear Action `ExportPlansToCsvAction`
- [ ] **10.5** Botones de exportación en dashboard
- [ ] **10.6** Crear Jobs para exportación (asíncrono)
- [ ] **10.7** Tests unitarios completos:
  - Actions
  - Services
  - Cálculo de estadísticas
  - Evaluación de insignias
- [ ] **10.8** Tests de feature completos:
  - Flujo completo: registro → emparejamiento → crear plan → valorar → ver estadísticas
  - CRUD de planes
  - Subida de fotos
- [ ] **10.9** Tests de políticas de autorización
- [ ] **10.10** Optimizaciones finales:
  - Revisar N+1 queries
  - Optimizar consultas lentas
  - Comprimir assets
- [ ] **10.11** Documentación de API/endpoints (si aplica)
- [ ] **10.12** README completo con instrucciones de instalación
- [ ] **10.13** Revisión de seguridad:
  - Validaciones de entrada
  - Protección CSRF
  - Rate limiting
- [ ] **10.14** Preparar para producción:
  - Variables de entorno
  - Configuración de storage
  - Optimización de caché

**Entregables:**
- ✅ Sistema de notificaciones
- ✅ Exportación PDF/CSV
- ✅ Suite de tests completa
- ✅ Aplicación lista para producción

---

## 📈 Métricas de Éxito por Sprint

### Sprint 1-2:
- ✅ Base de datos estructurada
- ✅ Usuarios pueden registrarse y emparejarse

### Sprint 3-4:
- ✅ Planes completamente funcionales
- ✅ Valoraciones operativas

### Sprint 5-6:
- ✅ Fotos subidas y procesadas
- ✅ Panel admin funcional

### Sprint 7-8:
- ✅ Estadísticas visibles
- ✅ Insignias asignándose automáticamente

### Sprint 9-10:
- ✅ UX pulida
- ✅ Aplicación completa y testeada

---

## 🔄 Flujo de Trabajo Recomendado

1. **Cada sprint:**
   - Daily standup (si trabajas en equipo)
   - Code review antes de merge
   - Tests antes de marcar tarea como completa

2. **Al final de cada sprint:**
   - Demo de funcionalidades
   - Retrospectiva
   - Planificación del siguiente sprint

3. **Git workflow:**
   - Branch por feature: `feature/sprint-X-task-Y`
   - Merge a `develop` al completar tarea
   - Merge a `main` al completar sprint

---

## 📝 Notas Importantes

- **Prioridad MVP:** Sprints 1-7 son críticos para MVP
- **Mejoras opcionales:** Sprints 8-10 pueden ajustarse según tiempo disponible
- **Testing continuo:** Escribir tests mientras desarrollas, no al final
- **Documentación:** Documentar decisiones importantes en código






# 🧪 Guía de Pruebas Completa - Valorar Planes en Pareja

## 📋 Índice de Pruebas

1. [Autenticación y Registro](#1-autenticación-y-registro)
2. [Emparejamiento](#2-emparejamiento)
3. [CRUD de Planes](#3-crud-de-planes)
4. [Sistema de Valoraciones](#4-sistema-de-valoraciones)
5. [Sistema de Fotos](#5-sistema-de-fotos)
6. [Estadísticas](#6-estadísticas)
7. [Insignias (Gamificación)](#7-insignias-gamificación)
8. [Calendario](#8-calendario)
9. [Favoritos](#9-favoritos)
10. [Búsqueda y Filtros](#10-búsqueda-y-filtros)
11. [Notificaciones](#11-notificaciones)
12. [Exportación](#12-exportación)
13. [Panel Administrativo](#13-panel-administrativo)

---

## 1. Autenticación y Registro

### 1.1 Registro de Usuario
- [ ] Ir a `/register`
- [ ] Completar formulario con:
  - Nombre
  - Email
  - Contraseña
  - Confirmar contraseña
- [ ] Verificar que se crea el usuario correctamente
- [ ] Verificar redirección a `/couple/setup`

### 1.2 Login
- [ ] Ir a `/login`
- [ ] Ingresar credenciales
- [ ] Verificar login exitoso
- [ ] Verificar redirección según estado de pareja

### 1.3 Logout
- [ ] Hacer clic en menú de usuario
- [ ] Seleccionar "Log Out"
- [ ] Verificar logout exitoso

---

## 2. Emparejamiento

### 2.1 Crear Pareja
- [ ] Como usuario nuevo, ir a `/couple/setup`
- [ ] Hacer clic en "Crear Pareja"
- [ ] Verificar que se crea la pareja
- [ ] Verificar que se muestra el código de unión
- [ ] Copiar el código de unión

### 2.2 Unirse a Pareja
- [ ] Como segundo usuario, ir a `/couple/setup`
- [ ] Hacer clic en "Unirse a Pareja"
- [ ] Ingresar el código de unión copiado
- [ ] Verificar que se une correctamente
- [ ] Verificar redirección al dashboard

### 2.3 Verificar Emparejamiento
- [ ] En el dashboard, verificar que aparece la información de la pareja
- [ ] Verificar que se muestra el código de unión
- [ ] Verificar que se muestra el nombre de la pareja

---

## 3. CRUD de Planes

### 3.1 Crear Plan
- [ ] Ir a `/plans/create` o hacer clic en "Nuevo Plan" en el dashboard
- [ ] Completar formulario:
  - Título
  - Fecha
  - Categoría
  - Ubicación (opcional)
  - Coste (opcional)
  - Descripción (opcional)
- [ ] Guardar plan
- [ ] Verificar que aparece en la lista de planes
- [ ] Verificar que se envía notificación a la pareja

### 3.2 Ver Lista de Planes
- [ ] Ir a `/plans`
- [ ] Verificar que se muestran todos los planes de la pareja
- [ ] Verificar que se muestran: título, fecha, categoría, estado
- [ ] Verificar paginación si hay muchos planes

### 3.3 Ver Detalle de Plan
- [ ] Hacer clic en un plan de la lista
- [ ] Verificar que se muestra toda la información:
  - Título, fecha, categoría
  - Ubicación, coste
  - Descripción
  - Estado
  - Valoraciones
  - Fotos

### 3.4 Editar Plan
- [ ] Desde el detalle del plan, hacer clic en "Editar"
- [ ] Modificar algún campo
- [ ] Guardar cambios
- [ ] Verificar que los cambios se reflejan

### 3.5 Eliminar Plan
- [ ] Desde el detalle del plan, hacer clic en "Eliminar"
- [ ] Confirmar eliminación
- [ ] Verificar que el plan desaparece de la lista
- [ ] Verificar que es soft delete (no se elimina permanentemente)

---

## 4. Sistema de Valoraciones

### 4.1 Crear Valoración
- [ ] Ir al detalle de un plan completado
- [ ] Buscar el formulario de valoración
- [ ] Completar los 5 criterios:
  - Diversión (1-5)
  - Conexión Emocional (1-5)
  - Organización (1-5)
  - Calidad-Precio (1-5)
  - Valoración General (1-5)
- [ ] Agregar comentario (opcional)
- [ ] Guardar valoración
- [ ] Verificar que aparece en el plan
- [ ] Verificar que se calcula la media automáticamente
- [ ] Verificar que se envía notificación a la pareja

### 4.2 Editar Valoración
- [ ] Desde el detalle del plan, encontrar tu valoración
- [ ] Editar algún criterio
- [ ] Guardar cambios
- [ ] Verificar que se actualiza la media

### 4.3 Ver Valoraciones de Pareja
- [ ] Verificar que se muestran las valoraciones de ambos miembros
- [ ] Verificar que se identifica quién valoró cada plan

---

## 5. Sistema de Fotos

### 5.1 Subir Fotos
- [ ] Ir al detalle de un plan
- [ ] Buscar el componente de subida de fotos
- [ ] Seleccionar una o varias fotos (máximo 20 por plan)
- [ ] Verificar validación de formato (JPG, PNG, WebP)
- [ ] Verificar validación de tamaño (máximo 10MB)
- [ ] Subir fotos
- [ ] Verificar que aparecen en la galería
- [ ] Verificar que se generan thumbnails
- [ ] Verificar que se envía notificación a la pareja

### 5.2 Ver Galería
- [ ] Verificar que las fotos se muestran en grid
- [ ] Hacer clic en una foto
- [ ] Verificar que se abre el lightbox
- [ ] Navegar entre fotos en el lightbox
- [ ] Cerrar el lightbox

### 5.3 Eliminar Foto
- [ ] Desde la galería, eliminar una foto
- [ ] Verificar que desaparece de la galería
- [ ] Verificar que se actualiza el contador

---

## 6. Estadísticas

### 6.1 Estadísticas de Pareja
- [ ] Ir a `/statistics`
- [ ] Verificar que se muestran:
  - Total de planes
  - Planes completados
  - Planes pendientes
  - Valoración media general
  - Valoración media por criterio
  - Categorías mejor/peor valoradas
  - Distribución mensual de planes

### 6.2 Estadísticas Individuales
- [ ] Verificar estadísticas personales:
  - Planes creados
  - Valoraciones realizadas
  - Fotos subidas

---

## 7. Insignias (Gamificación)

### 7.1 Ver Insignias
- [ ] Ir a `/badges`
- [ ] Verificar que se muestran las insignias disponibles
- [ ] Verificar que se identifican las insignias obtenidas
- [ ] Verificar que se muestra el progreso para las no obtenidas

### 7.2 Obtener Insignia
- [ ] Realizar acciones que desbloqueen insignias:
  - Crear 5 planes
  - Completar 10 planes
  - Valorar 20 planes
  - Subir 50 fotos
- [ ] Verificar que se recibe notificación al obtener insignia
- [ ] Verificar que aparece en la lista de insignias obtenidas

### 7.3 Insignias de Pareja
- [ ] Ir a `/badges/couple`
- [ ] Verificar que se muestran las insignias de ambos miembros

---

## 8. Calendario

### 8.1 Ver Calendario
- [ ] Ir a `/plans/calendar`
- [ ] Verificar que se muestra el calendario mensual
- [ ] Verificar que los planes aparecen en sus fechas correspondientes
- [ ] Verificar colores según categoría
- [ ] Verificar indicadores de estado (completado/pendiente)

### 8.2 Navegación del Calendario
- [ ] Hacer clic en "Mes Anterior"
- [ ] Verificar que cambia el mes
- [ ] Hacer clic en "Mes Siguiente"
- [ ] Verificar que cambia el mes
- [ ] Hacer clic en "Hoy"
- [ ] Verificar que vuelve al mes actual

### 8.3 Ver Detalle desde Calendario
- [ ] Hacer clic en un plan del calendario
- [ ] Verificar que se abre el modal con detalles
- [ ] Hacer clic en "Ver Detalles Completos"
- [ ] Verificar que redirige al detalle del plan

---

## 9. Favoritos

### 9.1 Marcar como Favorito
- [ ] Ir al detalle de un plan
- [ ] Hacer clic en el botón de estrella (favorito)
- [ ] Verificar que se marca como favorito
- [ ] Verificar que el icono cambia

### 9.2 Ver Planes Favoritos
- [ ] Ir a `/plans/favorites`
- [ ] Verificar que se muestran solo los planes favoritos
- [ ] Verificar que tienen el badge de estrella

### 9.3 Desmarcar Favorito
- [ ] Desde el detalle del plan, hacer clic nuevamente en el botón de estrella
- [ ] Verificar que se desmarca
- [ ] Verificar que desaparece de la lista de favoritos

---

## 10. Búsqueda y Filtros

### 10.1 Búsqueda Básica
- [ ] Ir a `/plans`
- [ ] En el campo de búsqueda, escribir parte del título de un plan
- [ ] Verificar que se filtran los resultados
- [ ] Probar búsqueda por descripción
- [ ] Probar búsqueda por ubicación
- [ ] Probar búsqueda en comentarios de valoraciones

### 10.2 Filtros Básicos
- [ ] Filtrar por categoría
- [ ] Filtrar por estado (pendiente/completado)
- [ ] Filtrar por rango de fechas
- [ ] Filtrar por creador (yo/pareja)

### 10.3 Filtros Avanzados
- [ ] Filtrar por rango de valoración (mínima/máxima)
- [ ] Filtrar por rango de coste (mínimo/máximo)
- [ ] Filtrar solo favoritos
- [ ] Filtrar planes sin valorar
- [ ] Filtrar planes sin fotos
- [ ] Combinar múltiples filtros

### 10.4 Ordenación
- [ ] Ordenar por fecha (ascendente/descendente)
- [ ] Ordenar por valoración
- [ ] Ordenar por coste
- [ ] Ordenar por título

### 10.5 Limpiar Filtros
- [ ] Aplicar varios filtros
- [ ] Hacer clic en "Limpiar Filtros"
- [ ] Verificar que se resetean todos los filtros

---

## 11. Notificaciones

### 11.1 Ver Notificaciones
- [ ] Hacer clic en el icono de campana en el sidebar
- [ ] Verificar que se abre el dropdown
- [ ] Verificar que se muestra el contador de no leídas

### 11.2 Tipos de Notificaciones
- [ ] **Plan Creado**: Crear un plan y verificar que la pareja recibe notificación
- [ ] **Plan Valorado**: Valorar un plan y verificar que el creador recibe notificación
- [ ] **Insignia Obtenida**: Obtener una insignia y verificar notificación
- [ ] **Fotos Subidas**: Subir fotos y verificar que la pareja recibe notificación

### 11.3 Marcar como Leída
- [ ] Hacer clic en una notificación
- [ ] Verificar que se marca como leída
- [ ] Verificar que desaparece el indicador de no leída

### 11.4 Marcar Todas como Leídas
- [ ] Hacer clic en "Marcar todas como leídas"
- [ ] Verificar que todas se marcan como leídas
- [ ] Verificar que el contador vuelve a 0

---

## 12. Exportación

### 12.1 Exportar a PDF
- [ ] Ir al dashboard
- [ ] Hacer clic en "Exportar PDF"
- [ ] Verificar que se descarga el archivo PDF
- [ ] Abrir el PDF y verificar que contiene:
  - Información de la pareja
  - Estadísticas resumen
  - Lista completa de planes con detalles
  - Fechas y valoraciones

### 12.2 Exportar a CSV
- [ ] Ir al dashboard
- [ ] Hacer clic en "Exportar CSV"
- [ ] Verificar que se descarga el archivo CSV
- [ ] Abrir el CSV y verificar que contiene todos los datos:
  - ID, Título, Fecha, Categoría, Ubicación, Coste
  - Estado, Valoración Media, Nº Valoraciones, Nº Fotos
  - Creado Por, Creado En

---

## 13. Panel Administrativo

### 13.1 Acceso al Panel
- [ ] Ir a `/admin`
- [ ] Ingresar con credenciales de administrador
- [ ] Verificar que se accede al panel

### 13.2 Gestión de Usuarios
- [ ] Ver lista de usuarios
- [ ] Verificar que se muestra el campo `is_admin`
- [ ] Filtrar por usuarios admin
- [ ] Ver detalles de un usuario

### 13.3 Gestión de Parejas
- [ ] Ver lista de parejas
- [ ] Verificar que se muestra el código de unión
- [ ] Copiar código de unión
- [ ] Ver detalles de una pareja
- [ ] Regenerar código de unión

### 13.4 Gestión de Categorías
- [ ] Ver lista de categorías
- [ ] Crear nueva categoría con icono y color
- [ ] Editar categoría existente
- [ ] Verificar que se muestra en el formulario de planes

### 13.5 Gestión de Planes
- [ ] Ver lista de todos los planes
- [ ] Filtrar por estado
- [ ] Filtrar por categoría
- [ ] Ver detalles de un plan
- [ ] Ver estadísticas en el dashboard del admin

### 13.6 Gestión de Valoraciones
- [ ] Ver lista de valoraciones
- [ ] Ver detalles de una valoración

### 13.7 Gestión de Fotos
- [ ] Ver lista de fotos
- [ ] Ver detalles de una foto

### 13.8 Gestión de Insignias
- [ ] Ver lista de insignias
- [ ] Ver criterios de cada insignia
- [ ] Ver usuarios que tienen cada insignia

---

## ✅ Checklist Final

- [ ] Todas las funcionalidades básicas funcionan
- [ ] No hay errores en la consola del navegador
- [ ] Las notificaciones se envían correctamente
- [ ] Las exportaciones funcionan
- [ ] El diseño es responsive (móvil y desktop)
- [ ] Los filtros y búsquedas funcionan correctamente
- [ ] El calendario muestra los planes correctamente
- [ ] El sistema de favoritos funciona
- [ ] Las estadísticas se calculan correctamente
- [ ] Las insignias se asignan automáticamente

---

## 🐛 Reporte de Errores

Si encuentras algún error durante las pruebas, documenta:
1. **Descripción del error**
2. **Pasos para reproducirlo**
3. **Mensaje de error (si aparece)**
4. **Navegador y versión**
5. **Screenshot (si es posible)**

---

¡Buena suerte con las pruebas! 🚀





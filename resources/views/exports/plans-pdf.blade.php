<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exportación de Planes - {{ $couple->join_code }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }
        .header {
            background: linear-gradient(135deg, #ec4899 0%, #a855f7 50%, #3b82f6 100%);
            color: white;
            padding: 30px;
            text-align: center;
            margin-bottom: 30px;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .stats {
            display: flex;
            justify-content: space-around;
            margin-bottom: 30px;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 8px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-value {
            font-size: 24px;
            font-weight: bold;
            color: #ec4899;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
            margin-top: 5px;
        }
        .plan {
            margin-bottom: 25px;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            page-break-inside: avoid;
        }
        .plan-header {
            display: flex;
            justify-content: space-between;
            align-items: start;
            margin-bottom: 10px;
        }
        .plan-title {
            font-size: 16px;
            font-weight: bold;
            color: #333;
        }
        .plan-date {
            color: #666;
            font-size: 11px;
        }
        .plan-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
            margin-top: 10px;
            font-size: 11px;
        }
        .plan-info-item {
            color: #555;
        }
        .plan-info-label {
            font-weight: bold;
            color: #333;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>💑 Planes en Pareja</h1>
        <p>Código de Pareja: {{ $couple->join_code }}</p>
        <p>Generado el {{ $generated_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="stats">
        <div class="stat-item">
            <div class="stat-value">{{ $stats['total'] }}</div>
            <div class="stat-label">Total Planes</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $stats['completed'] }}</div>
            <div class="stat-label">Completados</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $stats['pending'] }}</div>
            <div class="stat-label">Pendientes</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $stats['avg_rating'] ? number_format($stats['avg_rating'], 1) : 'N/A' }}</div>
            <div class="stat-label">Valoración Media</div>
        </div>
        <div class="stat-item">
            <div class="stat-value">{{ $stats['total_photos'] }}</div>
            <div class="stat-label">Total Fotos</div>
        </div>
    </div>

    @foreach($plans as $plan)
        <div class="plan">
            <div class="plan-header">
                <div>
                    <div class="plan-title">{{ $plan->category->icon ?? '📅' }} {{ $plan->title }}</div>
                    <div class="plan-date">{{ $plan->date->format('d/m/Y') }}</div>
                </div>
                <div>
                    <span style="padding: 4px 8px; border-radius: 4px; font-size: 10px; 
                        {{ $plan->status === 'completed' ? 'background: #10b981; color: white;' : 'background: #f59e0b; color: white;' }}">
                        {{ $plan->status === 'completed' ? 'Completado' : 'Pendiente' }}
                    </span>
                </div>
            </div>
            
            <div class="plan-info">
                @if($plan->location)
                    <div class="plan-info-item">
                        <span class="plan-info-label">Ubicación:</span> {{ $plan->location }}
                    </div>
                @endif
                @if($plan->cost)
                    <div class="plan-info-item">
                        <span class="plan-info-label">Coste:</span> {{ number_format($plan->cost, 2) }} €
                    </div>
                @endif
                <div class="plan-info-item">
                    <span class="plan-info-label">Categoría:</span> {{ $plan->category->name ?? 'Sin categoría' }}
                </div>
                @if($plan->overall_avg)
                    <div class="plan-info-item">
                        <span class="plan-info-label">Valoración:</span> {{ number_format($plan->overall_avg, 1) }}/5 ({{ $plan->ratings_count }} valoraciones)
                    </div>
                @endif
                <div class="plan-info-item">
                    <span class="plan-info-label">Fotos:</span> {{ $plan->photos_count }}
                </div>
                <div class="plan-info-item">
                    <span class="plan-info-label">Creado por:</span> {{ $plan->createdBy->name }}
                </div>
            </div>
            
            @if($plan->description)
                <div style="margin-top: 10px; padding: 10px; background: #f9f9f9; border-radius: 4px; font-size: 11px;">
                    {{ $plan->description }}
                </div>
            @endif
        </div>
    @endforeach

    <div class="footer">
        <p>Documento generado automáticamente por la aplicación "Valorar Planes en Pareja"</p>
        <p>© {{ date('Y') }} - Todos los derechos reservados</p>
    </div>
</body>
</html>


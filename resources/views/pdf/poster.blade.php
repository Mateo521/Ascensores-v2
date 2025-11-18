<!-- resources/views/pdf/poster.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>QR - {{ $elevator->designation }}</title>
    <style>
        @page {
            /* A6: 105 x 148 mm */
            size: A6 portrait;
            margin: 6mm;
            /* márgenes pequeños */
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        /* Evitar saltos dentro de la tarjeta */
        .card {
            page-break-inside: avoid;
            page-break-before: avoid;
            page-break-after: avoid;
        }

        /* Maquetación compacta pensada para A6 */
        .wrap {
            font-family: DejaVu Sans, Arial, sans-serif;
            color: #111;
        }

        .header {
            text-align: center;
            margin-bottom: 6px;
        }

        .logo {
            height: 48px;
            /* controlado para A6 */
            width: auto;
            display: block;
            margin: 0 auto 4px;
        }

        .company-name {
            font-size: 12px;
            font-weight: bold;
            color: #2563eb;
            margin-bottom: 2px;
        }

        .sub {
            font-size: 9px;
            color: #333;
            margin-bottom: 4px;
        }

        .elevator-info {
            font-size: 11px;
            font-weight: bold;
            text-align: center;
            margin: 4px 0;
        }

        .address {
            font-size: 9px;
            color: #555;
            text-align: center;
            margin-bottom: 6px;
        }

        .qr-container {
            text-align: center;
            margin: 6px 0;
        }

        .qr-code {
            display: block;
            width: 75%;
            max-width: 200px;
            /* límite para A6 */
            height: auto;
            margin: 0 auto;
            border: 2px solid #2563eb;
            padding: 6px;
            background: #fff;
        }

        .instructions {
            font-size: 8.5px;
            color: #444;
            text-align: center;
            margin-top: 6px;
            line-height: 1.35;
        }

        .emergency {
            margin-top: 6px;
            padding: 6px;
            background: #fee2e2;
            border: 1px solid #ef4444;
            border-radius: 3px;
            text-align: center;
        }

        .emergency-title {
            font-size: 9px;
            font-weight: bold;
            color: #991b1b;
            margin-bottom: 2px;
        }

        .emergency-phone {
            font-size: 13px;
            font-weight: bold;
            color: #dc2626;
        }

        .footer {
            margin-top: 6px;
            font-size: 8px;
            color: #777;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="wrap card">
        <div class="header">
            @if(!empty($logoBase64))
            <img src="{{ $logoBase64 }}" alt="Logo" style="width:120px; height:auto;">
            @endif
            <div class="company-name">{{ $settings->company_name ?? 'Ascensores Nuevo Cuyo' }}</div>
            <div class="sub">
                @if(!empty($settings->start_of_activity))
                Inicio de actividad:
                {{ \Carbon\Carbon::parse($settings->start_of_activity)->timezone($tz)->format('d/m/Y') }}
                @endif
                @if(!empty($settings->cuit))
                • CUIT: {{ $settings->cuit }}
                @endif
            </div>
        </div>

        <div class="elevator-info">{{ $elevator->designation }}</div>

        @if(!empty($elevator->address))
        <div class="address">{{ $elevator->address }}</div>
        @endif

        <!-- Capacidad (solo si tiene valor) -->
        @if(!empty($elevator->max_capacity_kg))
        <div class="capacity" style="font-size: 9px; color: #333; text-align: center; margin: 2px 0;">
            <strong>Capacidad:</strong> {{ $elevator->max_capacity_kg }} kg
        </div>
        @endif

        <!-- Comentarios (solo si tiene valor) -->
        @if(!empty($elevator->comments))
        <div class="comments" style="font-size: 8px; color: #555; text-align: center; margin: 4px 0; padding: 4px; background: #f9fafb; border-radius: 3px; line-height: 1.3;">
            <strong>Comentarios:</strong> {{ \Illuminate\Support\Str::limit($elevator->comments, 120) }}
        </div>
        @endif

        <div class="qr-container">
            <img src="{{ $qrBase64 }}" alt="QR Code" class="qr-code">
        </div>

        <!-- ⚠️ ADVERTENCIA DE INCENDIO (texto fijo, siempre visible) -->
        <div class="fire-warning" style="margin: 6px 0; padding: 6px; background: #fef3c7; border: 2px solid #f59e0b; border-radius: 3px; text-align: center;">
            <div style="font-size: 8px; font-weight: bold; color: #92400e; margin-bottom: 2px;">
                 ADVERTENCIA
            </div>
            <div style="font-size: 9px; font-weight: bold; color: #b45309;">
                NO UTILIZAR EN CASO DE INCENDIO
            </div>
        </div>

        <div class="emergency">
            <div class="emergency-title">EN CASO DE EMERGENCIA</div>
            <div class="emergency-phone">{{ $settings->emergency_phone ?? '266 455 5572' }}</div>
        </div>

        <div class="footer">
            Generado el {{ $generatedAt }} —
            {{ $settings->company_name ?? 'Ascensores Nuevo Cuyo' }}
        </div>




    </div>
</body>

</html>
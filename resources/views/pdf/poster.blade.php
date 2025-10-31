<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>QR - {{ $elevator->designation }}</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; text-align: center; }
        .logo { max-width: 120px; height: auto; margin-bottom: 10px; }
        .qr-container { margin: 20px auto; text-align: center; }
        .qr-code { max-width: 280px; height: auto; display: block; margin: 0 auto; border: 3px solid #2563eb; padding: 10px; background: #fff; }
        .company-name { font-size: 20px; font-weight: bold; color: #2563eb; margin-bottom: 5px; }
        .elevator-info { font-size: 16px; font-weight: bold; margin: 15px 0; color: #1f2937; }
        .address { font-size: 12px; color: #6b7280; margin-bottom: 20px; }
        .instructions { font-size: 11px; color: #4b5563; margin-top: 15px; line-height: 1.5; }
        .emergency { margin-top: 20px; padding: 10px; background: #fee2e2; border: 2px solid #ef4444; border-radius: 5px; }
        .emergency-title { font-size: 12px; font-weight: bold; color: #991b1b; margin-bottom: 5px; }
        .emergency-phone { font-size: 18px; font-weight: bold; color: #dc2626; }
        .footer { margin-top: 20px; font-size: 9px; color: #9ca3af; }
    </style>
</head>
<body>
    <div class="header">
        @if($settings && $settings->logo_path && file_exists(public_path('storage/' . $settings->logo_path)))
            <img src="{{ public_path('storage/' . $settings->logo_path) }}" alt="Logo" class="logo">
        @endif
        <div class="company-name">{{ $settings->company_name ?? 'Ascensores Nuevo Cuyo' }}</div>
    </div>

    <div class="elevator-info">{{ $elevator->designation }}</div>
    @if($elevator->address)
        <div class="address">📍 {{ $elevator->address }}</div>
    @endif

    <div class="qr-container">
        <img src="data:image/png;base64,{{ $qrBase64 }}" alt="QR Code" class="qr-code">
    </div>

    <div class="instructions">
        <strong>Escanee este código QR para ver el estado de las revisiones mensuales</strong><br>
        Las revisiones se actualizan en tiempo real
    </div>

    <div class="emergency">
        <div class="emergency-title">EN CASO DE EMERGENCIA</div>
        <div class="emergency-phone">📞 {{ $settings->emergency_phone ?? '266 455 5572' }}</div>
    </div>

    <div class="footer">
        {{ $settings->company_name ?? 'Ascensores Nuevo Cuyo' }}
        @if($settings && $settings->cuit)
            | CUIT: {{ $settings->cuit }}
        @endif
        | Generado el {{ now()->format('d/m/Y H:i') }}
    </div>
</body>
</html>

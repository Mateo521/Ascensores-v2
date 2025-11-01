<?php

namespace App\Http\Controllers;

// app/Http/Controllers/PdfController.php
namespace App\Http\Controllers;

use App\Models\Elevator;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use Illuminate\Support\Str;

class PdfController extends Controller
{
    public function poster(Elevator $elevator)
    {
        $settings = Setting::first();

        // Usa APP_URL para QR (útil si lo escaneás con el móvil en tu red)
        $publicUrl = rtrim(config('app.url'), '/') . "/p/{$elevator->public_token}";

        // QR PNG base64 (sin Imagick)
        $options = new QROptions([
            'outputType'    => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'      => QRCode::ECC_H,
            'scale'         => 9,
            'quietzoneSize' => 4,
            'imageBase64'   => false,
        ]);
        $pngData  = (new QRCode($options))->render($publicUrl);
        $qrBase64 = base64_encode($pngData);

        // Resolver logo local (storage o public)
        $logoPath = null;
        if(!empty($settings?->logo_path) && file_exists(public_path('storage/'.$settings->logo_path))){
            $logoPath = public_path('storage/'.$settings->logo_path);
        } elseif (file_exists(public_path('logo.png'))) {
            $logoPath = public_path('logo.png');
        } elseif (file_exists(public_path('logo.svg'))) {
            // dompdf soporta SVG simple, pero PNG es más seguro. Si usas SVG, déjalo; si no, conviértelo a PNG.
            $logoPath = public_path('logo.svg');
        }

        // Zona horaria (ver siguiente sección): pásala a la vista
        $tz = config('app.timezone', 'UTC');
        $generatedAt = now()->timezone($tz)->format('d/m/Y H:i');

        $pdf = Pdf::loadView('pdf.poster', [
            'settings'    => $settings,
            'elevator'    => $elevator,
            'qrBase64'    => $qrBase64,
            'publicUrl'   => $publicUrl,
            'logoPath'    => $logoPath,
            'generatedAt' => $generatedAt,
            'tz'          => $tz,
        ])->setPaper('a6', 'portrait');

        return $pdf->download("ascensor-{$elevator->id}-qr.pdf");
    }
}

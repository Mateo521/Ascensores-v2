<?php

namespace App\Http\Controllers;

use App\Models\Elevator;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class PdfController extends Controller
{
    public function poster(Elevator $elevator)
    {
        $settings  = Setting::first();
        $publicUrl = rtrim(config('app.url'), '/') . "/p/{$elevator->public_token}";

        // QR en base64 con prefijo data:
        $options  = new QROptions([
            'outputType'    => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'      => QRCode::ECC_H,
            'scale'         => 9,
            'quietzoneSize' => 4,
            'imageBase64'   => false,
        ]);
        $pngData  = (new QRCode($options))->render($publicUrl);
        $qrBase64 = 'data:image/png;base64,' . base64_encode($pngData);

        // Logo como base64 (buscando en storage real, public/storage y public/)
        $logoBase64 = $this->resolveLogoBase64($settings?->logo_path);

        $tz          = config('app.timezone', 'UTC');
        $generatedAt = now()->timezone($tz)->format('d/m/Y H:i');

        return Pdf::loadView('pdf.poster', [
            'settings'    => $settings,
            'elevator'    => $elevator,
            'qrBase64'    => $qrBase64,    // con prefijo data:
            'publicUrl'   => $publicUrl,
            'logoBase64'  => $logoBase64,  // con prefijo data:
            'generatedAt' => $generatedAt,
            'tz'          => $tz,
        ])->setPaper('a6', 'portrait')
          ->download("ascensor-{$elevator->id}-qr.pdf");
    }

    private function resolveLogoBase64(?string $settingsLogoPath): ?string
    {
        $candidates = [];

        if (!empty($settingsLogoPath)) {
            // Archivo real en storage (si no tienes symlink o storage:link)
            $candidates[] = storage_path('app/public/' . ltrim($settingsLogoPath, '/'));
            // Ruta pública si existe el symlink public/storage
            $candidates[] = public_path('storage/' . ltrim($settingsLogoPath, '/'));
        }

        // Fallbacks en public/
        $candidates[] = public_path('logo.png');
        $candidates[] = public_path('logo.svg');
        $candidates[] = public_path('images/logo.png');
        $candidates[] = public_path('images/logo.svg');

        foreach ($candidates as $path) {
            if ($path && is_file($path)) {
                $ext  = strtolower(pathinfo($path, PATHINFO_EXTENSION));
                $mime = $ext === 'svg' ? 'image/svg+xml' : 'image/png';
                $data = @file_get_contents($path);
                if ($data !== false) {
                    return 'data:' . $mime . ';base64,' . base64_encode($data);
                }
            }
        }

        return null;
    }
}

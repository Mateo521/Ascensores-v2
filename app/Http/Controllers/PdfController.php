<?php

namespace App\Http\Controllers;

use App\Models\Elevator;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;

// chillerlan QR
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;

class PdfController extends Controller
{
    public function poster(Elevator $elevator)
    {
        $settings = Setting::first();

        // Usa APP_URL para que el QR funcione desde el celular en la red local
        // Ejemplo .env: APP_URL=http://192.168.0.15:8000
        $publicUrl = rtrim(config('app.url'), '/') . "/p/{$elevator->public_token}";
        // Si pruebas solo en la misma PC, también vale:
        // $publicUrl = url("/p/{$elevator->public_token}");

        // Configurar QR: PNG, corrección alta, tamaño para impresión nítida
        $options = new QROptions([
            'outputType'    => QRCode::OUTPUT_IMAGE_PNG,
            'eccLevel'      => QRCode::ECC_H,  // alto
            'scale'         => 9,               // px por módulo (ajusta 8-12)
            'quietzoneSize' => 4,               // margen alrededor
            'imageBase64'   => false,
        ]);

        // Generar PNG binario
        $pngData = (new QRCode($options))->render($publicUrl);
        $qrBase64 = base64_encode($pngData);

        $pdf = Pdf::loadView('pdf.poster', [
            'settings'  => $settings,
            'elevator'  => $elevator,
            'qrBase64'  => $qrBase64,
            'publicUrl' => $publicUrl,
        ])->setPaper('a6', 'portrait');

        return $pdf->download("ascensor-{$elevator->id}-qr.pdf");
    }
}

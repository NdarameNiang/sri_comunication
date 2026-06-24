<?php

namespace App\Http\Controllers\Secretaire;

use App\Http\Controllers\Controller;
use App\Models\EventConfig;
use App\Models\Questionnaire;
use App\Models\Registration;
use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use SimpleSoftwareIO\QrCode\Facades\QrCode as QrCodeSvg;

class DashboardController extends Controller
{
    private function generatePngQr(string $url, int $size = 300): string
    {
        $qr = QrCode::create($url)
            ->setEncoding(new Encoding('UTF-8'))
            ->setErrorCorrectionLevel(ErrorCorrectionLevel::High)
            ->setSize($size)
            ->setMargin(10)
            ->setForegroundColor(new Color(0, 0, 0))
            ->setBackgroundColor(new Color(255, 255, 255));

        $writer = new PngWriter();
        $result = $writer->write($qr);
        return $result->getString();
    }

    public function index()
    {
        $event = EventConfig::active();

        $stats = [
            'inscriptions'   => $event ? Registration::where('event_config_id', $event->id)->count() : 0,
            'presences'      => $event ? Registration::where('event_config_id', $event->id)->where('presence_confirmee', true)->count() : 0,
            'questionnaires' => $event ? Questionnaire::where('event_config_id', $event->id)->count() : 0,
        ];

        $recentRegistrations = $event
            ? Registration::where('event_config_id', $event->id)->latest()->take(5)->get()
            : collect();

        $qrCodes = [];
        if ($event) {
            foreach ([
                'inscription'   => route('public.registration.show', $event->event_slug),
                'questionnaire' => route('public.questionnaire.show', $event->event_slug),
            ] as $key => $url) {
                $qrCodes[$key] = 'data:image/png;base64,' . base64_encode(
                    $this->generatePngQr($url, 300)
                );
            }
        }

        return view('secretaire.dashboard', compact('event', 'stats', 'recentRegistrations', 'qrCodes'));
    }

    public function downloadQr(string $type)
    {
        $event = EventConfig::active();
        if (!$event) abort(404);

        $routes = [
            'inscription'   => route('public.registration.show', $event->event_slug),
            'questionnaire' => route('public.questionnaire.show', $event->event_slug),
        ];

        if (!isset($routes[$type])) abort(404);

        $png = $this->generatePngQr($routes[$type], 800);

        return response($png, 200, [
            'Content-Type'        => 'image/png',
            'Content-Disposition' => 'attachment; filename="qr-' . $type . '.png"',
        ]);
    }
}

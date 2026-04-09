<?php

declare(strict_types=1);

namespace Proovit\Billing\Support;

use Barryvdh\DomPDF\Facade\Pdf;
use Proovit\Billing\Contracts\PdfRendererInterface;
use Symfony\Component\HttpFoundation\Response;

final class ConfigPdfRenderer implements PdfRendererInterface
{
    public function render(string $view, array $data = [], array $options = []): string
    {
        return $this->makePdf($view, $data, $options)->output();
    }

    public function download(string $filename, string $view, array $data = [], array $options = []): Response
    {
        return $this->makePdf($view, $data, $options)->download($filename);
    }

    public function stream(string $filename, string $view, array $data = [], array $options = []): Response
    {
        return $this->makePdf($view, $data, $options)->stream($filename);
    }

    private function makePdf(string $view, array $data = [], array $options = []): \Barryvdh\DomPDF\PDF
    {
        $locale = $options['locale'] ?? app()->getLocale();
        $previousLocale = app()->getLocale();

        app()->setLocale((string) $locale);

        try {
            $pdf = Pdf::loadView($view, $data);
            $pdf->setPaper(
                $options['paper'] ?? config('billing.pdf.paper', 'a4'),
                $options['orientation'] ?? config('billing.pdf.orientation', 'portrait')
            );

            if (isset($options['dompdf']) && is_array($options['dompdf'])) {
                $pdf->setOptions($options['dompdf']);
            }

            return $pdf;
        } finally {
            app()->setLocale($previousLocale);
        }
    }
}

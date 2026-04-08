<?php

declare(strict_types=1);

namespace Proovit\Billing\Support;

use Proovit\Billing\Contracts\PdfRendererInterface;
use Symfony\Component\HttpFoundation\Response;

final class ConfigPdfRenderer implements PdfRendererInterface
{
    public function render(string $view, array $data = [], array $options = []): string
    {
        $locale = $options['locale'] ?? app()->getLocale();
        $previousLocale = app()->getLocale();

        app()->setLocale((string) $locale);

        try {
            $html = view($view, $data)->render();
        } finally {
            app()->setLocale($previousLocale);
        }

        return sprintf(
            'PDF:%s:%s',
            $view,
            json_encode([
                'data' => array_keys($data),
                'options' => $options,
                'html' => $html,
            ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE)
        );
    }

    public function download(string $filename, string $view, array $data = [], array $options = []): Response
    {
        $content = $this->render($view, $data, $options);

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
        ]);
    }

    public function stream(string $filename, string $view, array $data = [], array $options = []): Response
    {
        $content = $this->render($view, $data, $options);

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => sprintf('inline; filename="%s"', $filename),
        ]);
    }
}

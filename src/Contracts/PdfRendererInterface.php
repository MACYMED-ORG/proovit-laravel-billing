<?php

declare(strict_types=1);

namespace Proovit\Billing\Contracts;

use Symfony\Component\HttpFoundation\Response;

interface PdfRendererInterface
{
    public function render(string $view, array $data = [], array $options = []): string;

    public function download(string $filename, string $view, array $data = [], array $options = []): Response;

    public function stream(string $filename, string $view, array $data = [], array $options = []): Response;
}

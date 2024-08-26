<?php

declare(strict_types=1);

namespace cyberthai\Linebot\Provider;

interface LineProviderInterface {
    public function request(
        string $apiKind,
        string $urlPath,
        string $httpMethod,
        array $header,
        array $data
    ): array;
};
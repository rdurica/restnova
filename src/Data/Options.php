<?php declare(strict_types=1);

namespace Restnova\Data;

/**
 * Request.
 *
 * @copyright Copyright (c) 2024, Robert Durica
 * @since     2024-04-12
 */
final class Options
{
    /** @var string[] Header parameters. */
    public array $header = [
        'Accept'       => 'application/json',
        'Content-Type' => 'application/json'
    ];

    /** @var int Timeout. */
    public int $timeout = 5;

    /** @var bool Follow redirects. */
    public bool $followRedirects = true;
}

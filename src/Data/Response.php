<?php declare(strict_types=1);

namespace Restnova\Data;

/**
 * Response.
 *
 * @copyright Copyright (c) 2024, Robert Durica
 * @since     2024-04-12
 */
final class Response
{
    /** @var string Url. */
    public string $url;

    /** @var string Content type. */
    public string $contentType;

    /** @var int Http status code. */
    public int $httpCode;

    /** @var mixed Response body. */
    public mixed $body;

    /** @var int Total size of all headers received. */
    public int $headerSize;
}

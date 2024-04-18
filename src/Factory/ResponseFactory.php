<?php declare(strict_types=1);

namespace Restnova\Factory;

use Restnova\Data\Response;

/**
 * ResponseFactory.
 *
 * @copyright Copyright (c) 2024, Robert Durica
 * @since     2024-04-18
 */
final class ResponseFactory
{
    /**
     * Factory method for {@see Response}.
     *
     * @param string $url
     * @param string $contentType
     * @param int    $httpCode
     * @param mixed $responseBody
     * @param int    $headerSize
     *
     * @return Response
     */
    public static function create(
        string $url,
        string $contentType,
        int $httpCode,
        mixed $responseBody,
        int $headerSize
    ): Response
    {
        $response = new Response();

        $response->url         = $url;
        $response->contentType = $contentType;
        $response->httpCode    = $httpCode;
        $response->body        = $responseBody;
        $response->headerSize  = $headerSize;

        return $response;
    }
}

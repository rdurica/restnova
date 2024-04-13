<?php declare(strict_types=1);

namespace Restnova;

use Restnova\Data\Options;
use Restnova\Data\Response;
use Restnova\Exception\RestnovaException;

/**
 * Requet.
 *
 * @copyright Copyright (c) 2024, Robert Durica
 * @since     2024-04-13
 */
final class Request
{
    /**
     * Constructor.
     *
     * @param Options $options
     */
    public function __construct(private Options $options)
    {
    }

    /**
     * HTTP-DEL.
     *
     * @param string $url
     *
     * @return Response
     * @throws RestnovaException
     */
    public function delete(string $url): Response
    {
        throw new RestnovaException('Not implemented.');
    }

    /**
     * HTTP-GET.
     *
     * @param string $url
     *
     * @return Response
     * @throws RestnovaException
     */
    public function get(string $url): Response
    {
        throw new RestnovaException('Not implemented.');
    }

    /**
     * HTTP-HEAD.
     *
     * @param string $url
     *
     * @return Response
     * @throws RestnovaException
     */
    public function head(string $url): Response
    {
        throw new RestnovaException('Not implemented.');
    }

    /**
     * HTTP-PATCH.
     *
     * @param string $url
     *
     * @return Response
     * @throws RestnovaException
     */
    public function patch(string $url): Response
    {
        throw new RestnovaException('Not implemented.');
    }

    /**
     * HTTP-POST.
     *
     * @param string $url
     *
     * @return Response
     * @throws RestnovaException
     */
    public function post(string $url): Response
    {
        throw new RestnovaException('Not implemented.');
    }

    /**
     * HTTP-PUT.
     *
     * @param string $url
     *
     * @return Response
     * @throws RestnovaException
     */
    public function put(string $url): Response
    {
        throw new RestnovaException('Not implemented.');
    }
}

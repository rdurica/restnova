<?php declare(strict_types=1);

namespace Restnova\Exception;

/**
 * UnreachableException.
 *
 * @copyright Copyright (c) 2024, Robert Durica
 * @since     2024-04-13
 */
final class UnreachableException extends RestnovaException
{
    /**
     * Constructor.
     *
     * @param string  $message
     * @param mixed[] $requestInfo
     */
    public function __construct(string $message, private array $requestInfo)
    {
        parent::__construct($message);
    }

    /**
     * Request details {@see curl_getinfo()}.
     *
     * @return mixed[]
     */
    public function getRequestInfo(): array
    {
        return $this->requestInfo;
    }
}

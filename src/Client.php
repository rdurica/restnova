<?php declare(strict_types=1);

namespace Restnova;

use Restnova\Data\Options;

/**
 * Restnova.
 *
 * @copyright Copyright (c) 2024, Robert Durica
 * @since     2024-04-12
 */
final class Client
{
    /** @var Options Request data. */
    private Options $options;

    /**
     * Constructor.
     */
    private function __construct()
    {
        $this->options = new Options();
    }

    /**
     * Factory method.
     *
     * @return self
     */
    public static function create(): self
    {
        return new self();
    }

    /**
     * Add one header item into request header.
     *
     * @param string $key
     * @param string $value
     *
     * @return self
     */
    public function addHeaderItem(string $key, string $value): Client
    {
        $this->options->header[$key] = $value;

        return $this;
    }

    /**
     * Set timeott for requests.
     *
     * @param int $timeout
     *
     * @return $this
     */
    public function setTimeout(int $timeout): Client
    {
        $this->options->timeout = $timeout;

        return $this;
    }

    /**
     * When execute call follow redirects?
     *
     * @param bool $followRedirects
     *
     * @return $this
     */
    public function setFollowRedirects(bool $followRedirects): Client
    {
        $this->options->followRedirects = $followRedirects;

        return $this;
    }

    /**
     * Build client with setted request data.
     *
     * @return Request
     */
    public function build(): Request
    {
        return new Request($this->options);
    }
}

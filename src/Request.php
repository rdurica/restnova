<?php declare(strict_types=1);

namespace Restnova;

use CurlHandle;
use Restnova\Data\Options;
use Restnova\Data\Response;
use Restnova\Enum\Method;
use Restnova\Exception\RestnovaException;
use Restnova\Exception\UnreachableException;
use Restnova\Factory\ResponseFactory;
use Throwable;

use function strlen;

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
     * @param non-empty-string $url
     *
     * @return Response
     * @throws RestnovaException
     */
    public function delete(string $url): Response
    {
        return $this->executeMethod($url, Method::DELETE);
    }

    /**
     * HTTP-GET.
     *
     * @param non-empty-string $url
     *
     * @return Response
     * @throws UnreachableException
     * @throws RestnovaException
     */
    public function get(string $url): Response
    {
        return $this->executeMethod($url, Method::GET);
    }

    /**
     * HTTP-HEAD.
     *
     * @param non-empty-string $url
     *
     * @return Response
     * @throws RestnovaException
     */
    public function head(string $url): Response
    {
        return $this->executeMethod($url, Method::HEAD);
    }

    /**
     * HTTP-PATCH.
     *
     * @param non-empty-string       $url
     * @param non-empty-array<mixed> $data
     *
     * @return Response
     * @throws RestnovaException
     * @throws UnreachableException
     */
    public function patch(string $url, array $data): Response
    {
        return $this->executeMethod($url, Method::PATCH, $data);
    }

    /**
     * HTTP-POST.
     *
     * @param non-empty-string       $url
     * @param non-empty-array<mixed> $data
     *
     * @return Response
     * @throws RestnovaException
     * @throws UnreachableException
     */
    public function post(string $url, array $data): Response
    {
        return $this->executeMethod($url, Method::POST, $data);
    }

    /**
     * HTTP-PUT.
     *
     * @param non-empty-string $url
     *
     * @return Response
     * @throws RestnovaException
     */
    public function put(string $url): Response
    {
        return $this->executeMethod($url, Method::PUT);
    }

    /**
     * Execute method called from outside. Handle exceptions for all methods.
     *
     * @param non-empty-string $url
     * @param Method           $method
     * @param mixed[]          $data
     *
     * @return Response
     * @throws RestnovaException
     * @throws UnreachableException
     */
    private function executeMethod(string $url, Method $method, array $data = []): Response
    {
        try {

            $json = empty($data) ? null : json_encode($data, JSON_THROW_ON_ERROR);

            return $this->exec($url, $method, $json);
        }
        catch (UnreachableException $e) {
            throw $e;
        }
        catch (Throwable $e) {
            throw new RestnovaException($e->getMessage(), 0, $e);
        }
    }

    /**
     * Main execution.
     *
     * @param string      $url
     * @param Method      $method
     * @param String|null $json
     *
     * @return Response
     * @throws UnreachableException
     * @throws RestnovaException
     */
    private function exec(string $url, Method $method, ?string $json): Response
    {
        $curl = curl_init();
        if (!$curl instanceof CurlHandle) {
            throw new RestnovaException('Cannot initialize curl.');
        }

        if ($json !== null) {
            $this->options->header['Content-Length'] = strlen($json);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $json);
        }

        match ($method) {
            Method::DELETE => $this->setCurlOptionsDelete($curl),
            Method::GET    => null,
            Method::HEAD   => $this->setCurlOptionsHead($curl),
            Method::PATCH  => $this->setCurlOptionsPatch($curl),
            Method::POST   => $this->setCurlOptionsPost($curl),
            Method::PUT    => $this->setCurlOptionsPut($curl)
        };

        $this->setCurlOptionsGlobal($curl, $url);

        $response = curl_exec($curl);
        $httpData = curl_getinfo($curl);

        curl_close($curl);

        if ($response === false) {
            $message = curl_error($curl);

            throw new UnreachableException($message, $httpData);
        }

        $headerSize = curl_getinfo($curl, CURLINFO_HEADER_SIZE);

        return ResponseFactory::create(
            $httpData['url'],
            $httpData['content_type'],
            $httpData['http_code'],
            $response,
            $headerSize
        );
    }

    /**
     * Set request options for all requests.
     *
     * @param CurlHandle $curl
     * @param string     $url
     *
     * @return void
     */
    private function setCurlOptionsGlobal(CurlHandle $curl, string $url): void
    {
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->options->timeout);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, $this->options->followRedirects);

        $this->setHttpHeader($curl);
    }

    /**
     * Set http header to request.
     *
     * @param CurlHandle $curl
     *
     * @return void
     */
    private function setHttpHeader(CurlHandle $curl): void
    {
        $header = [];
        foreach ($this->options->header as $key => $value) {
            $header[] = sprintf('%s: %s', $key, $value);
        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    }

    /**
     * Set request options {@see self::delete()}.
     *
     * @param CurlHandle $curl
     *
     * @return void
     */
    private function setCurlOptionsDelete(CurlHandle $curl): void
    {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
    }

    /**
     * Set request options {@see self::head()}.
     *
     * @param CurlHandle $curl
     *
     * @return void
     */
    private function setCurlOptionsHead(CurlHandle $curl): void
    {
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'HEAD');
    }

    /**
     * Set request options {@see self::patch()}.
     *
     * @param CurlHandle $curl
     *
     * @return void
     */
    private function setCurlOptionsPatch(CurlHandle $curl): void
    {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PATCH');
    }

    /**
     * Set request options {@see self::post()}.
     *
     * @param CurlHandle $curl
     *
     * @return void
     */
    private function setCurlOptionsPost(CurlHandle $curl): void
    {
        curl_setopt($curl, CURLOPT_POST, true);
    }

    /**
     * Set request options {@see self::put()}.
     *
     * @param CurlHandle $curl
     *
     * @return void
     */
    private function setCurlOptionsPut(CurlHandle $curl): void
    {
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'PUT');
    }
}

<?php declare(strict_types=1);

namespace Manual;

use Exception;
use Iterator;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Restnova\Client;
use Restnova\Exception\RestnovaException;
use Restnova\Exception\UnreachableException;
use Restnova\Request;

/**
 * RequestTest.
 *
 * @copyright Copyright (c) 2024, Robert Durica
 * @since     2024-04-22
 */
final class GetTest extends TestCase
{
    /** @var Request Restnova request client. */
    private Request $request;

    /** @inheritDoc */
    protected function setUp(): void
    {
        parent::setUp();

        $this->request = Client::create()
            ->build();
    }

    /**
     * Data provider for {@see testHappyFlow()}.
     *
     * @return Iterator
     */
    public static function prepareHappyFlowData(): Iterator
    {
        yield 'Test http 200 (with body)' => [
            'url'              => 'https://httpbin.org/get',
            'expectedResponse' => [
                'contentType' => 'application/json',
                'httpCode'    => 200,
                'haveBody'    => true,
            ]
        ];

        yield 'Test http 200' => [
            'url'              => 'https://httpbin.org/status/200',
            'expectedResponse' => [
                'contentType' => 'text/html; charset=utf-8',
                'httpCode'    => 200,
                'haveBody'    => false,
            ]
        ];

        yield 'Test http 300' => [
            'url'              => 'https://httpbin.org/status/300',
            'expectedResponse' => [
                'contentType' => 'text/html; charset=utf-8',
                'httpCode'    => 300,
                'haveBody'    => false,
            ]
        ];

        yield 'Test http 400' => [
            'url'              => 'https://httpbin.org/status/400',
            'expectedResponse' => [
                'contentType' => 'text/html; charset=utf-8',
                'httpCode'    => 400,
                'haveBody'    => false,
            ]
        ];

        yield 'Test http 500' => [
            'url'              => 'https://httpbin.org/status/500',
            'expectedResponse' => [
                'contentType' => 'text/html; charset=utf-8',
                'httpCode'    => 500,
                'haveBody'    => false,
            ]
        ];

        yield 'Test http 401' => [
            'url'              => 'https://httpbin.org/bearer',
            'expectedResponse' => [
                'contentType' => 'text/html; charset=utf-8',
                'httpCode'    => 401,
                'haveBody'    => false,
            ]
        ];
    }

    /**
     * Test happy flow with multiple responses.
     *
     * @param non-empty-string                                                $url
     * @param array{'contentType': string, 'httpCode': int, 'haveBody': bool} $expectedResponse
     *
     * @throws RestnovaException
     * @throws UnreachableException
     */
    #[DataProvider('prepareHappyFlowData')]
    public function testHappyFlow(string $url, array $expectedResponse): void
    {
        $response = $this->request->get($url);

        self::assertEquals($url, $response->url);
        self::assertEquals($expectedResponse['contentType'], $response->contentType);
        self::assertEquals($expectedResponse['httpCode'], $response->httpCode);

        if ($expectedResponse['haveBody'] === true) {
            self::assertNotEmpty($response->body);
        } else {
            self::assertEmpty($response->body);
        }
    }

    /**
     * Data provider for {@see testExceptions()}.
     *
     * @return Iterator
     */
    public static function prepareExceptionsData(): Iterator
    {
        yield 'UnreachableException' => [
            'url'               => 'https://unknown-host',
            'expectedException' => new UnreachableException('Could not resolve host: unknown-host', [])
        ];
    }

    /**
     * Test exceptions.
     *
     * @param non-empty-string $url
     * @param Exception        $expectedException
     *
     * @throws RestnovaException
     * @throws UnreachableException
     */
    #[DataProvider('prepareExceptionsData')]
    public function testExceptions(string $url, Exception $expectedException): void
    {
        $this->expectExceptionObject($expectedException);

        $this->request->get($url);
    }
}

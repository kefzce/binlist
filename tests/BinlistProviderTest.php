<?php

declare(strict_types=1);

namespace App\Test;

use App\Country;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversNothing
 */
final class BinlistProviderTest extends TestCase
{
    public static function dataProvider(): \Generator
    {
        yield [
            [
                'url' => 'https://lookup.binlist.net/45717360',
                'code' => 200,
                'response' => self::successResponse(),
            ],
        ];
    }

    #[DataProvider('dataProvider')]
    public function testProvider(array $suite): void
    {
        ['url' => $url, 'code' => $code, 'response' => $response] = $suite;

        $mock = new MockHandler([
            new Response($code, [], $response),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $response = $client->get($url);
        $content = $response->getBody()->getContents();

        self::assertSame(200, $code);
        self::assertNotEmpty($content);
        $expectedStructure = [
            'number' => [],
            'scheme' => null,
            'type' => null,
            'brand' => null,
            'country' => [
                'numeric' => null,
                'alpha2' => null,
                'name' => null,
                'emoji' => null,
                'currency' => null,
                'latitude' => null,
                'longitude' => null,
            ],
            'bank' => [
                'name' => null,
            ],
        ];

        $json = json_decode($content, true);
        $this->assertArrayHasStructure($expectedStructure, $json);
        self::assertSame('DK', $json['country']['alpha2']);
        self::assertTrue(Country::isEu($json['country']['alpha2']));
    }

    public function assertArrayHasStructure(array $structure, array $array): void
    {
        foreach ($structure as $key => $value) {
            self::assertArrayHasKey($key, $array);
            if (\is_array($value)) {
                $this->assertArrayHasStructure($value, $array[$key]);
            }
        }
    }

    private static function successResponse(): string
    {
        return <<<'JSON'
            {"number":{},"scheme":"visa","type":"debit","brand":"Visa Classic","country":{"numeric":"208","alpha2":"DK","name":"Denmark","emoji":"🇩🇰","currency":"DKK","latitude":56,"longitude":10},"bank":{"name":"Jyske Bank A/S"}}
            JSON;
    }
}

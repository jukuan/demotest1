<?php

namespace App\Tests\Util;

use App\Service\ReferrerCoefficientMappingService;
use PHPUnit\Framework\TestCase;

class TestPriceCalculatorService extends TestCase
{
    /**
     * @dataProvider provideCoefficientData
     */
    public function testCoefficient(string $domain, float $coefficient)
    {
        $coefficientService = new ReferrerCoefficientMappingService();
        $result = $coefficientService->setUtmSource($domain)->getCoefficient();
        $this->assertEquals($coefficient, $result);
    }

    public function provideCoefficientData()
    {
        // Google
        yield ['google.com', 1.2];
        yield ['GooGlE.com', 1.2];
        yield ['plus.google.com', 1.2];
        yield ['http://google.com', 1.2];
        yield ['https://google.com', 1.2];
        yield ['https://www.google.com', 1.2];
        yield ['https://www.google.by', 1.2];
        yield ['https://www.google.by/link/example', 1.2];
        yield ['https://www.google.by/link/example?path=utm_suurce', 1.2];
        yield ['www.google.com', 1.2];

        // Bing
        yield ['https://bing.com/', 1.1];
        yield ['http://bing.com/', 1.1];
        yield ['http://bIng.com', 1.1];
        yield ['photo.bing.com', 1.1];
        yield ['bing.com', 1.1];
        yield ['BING.COM', 1.1];

        // Unexisted
        yield ['googleitformeplease.com', 1];
        yield ['bingplaces.com', 1];
        yield ['jopa.today', 1];
    }
}

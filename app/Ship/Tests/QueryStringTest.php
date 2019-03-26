<?php

namespace App\Ship\Tests;

use TestCase;

class QueryStringTest extends TestCase
{
    protected $url;

    public function setUp()
    {
        parent::setUp();
        $this->url = 'https://jojotoo-static.oss-cn-shanghai.aliyuncs.com/subject/image/bhDa3dRihtUUV3yUpGYNBjDJRYRXMN2XUOcwTXPs.png?x-oss-process=style/w768&tp=webp';
    }

    public function test_parse_url()
    {
        $queryString = parse_url($this->url, PHP_URL_QUERY);
        parse_str($queryString, $out);
        $this->assertArraySubset([
            "x-oss-process" => "style/w768",
            "tp" => "webp"
        ], $out);
    }

    public function test_remove_query_string()
    {
        $this->assertEquals(
            $removedParams = 'https://jojotoo-static.oss-cn-shanghai.aliyuncs.com/subject/image/bhDa3dRihtUUV3yUpGYNBjDJRYRXMN2XUOcwTXPs.png',
            strtok($this->url, '?')
        );

        $this->assertEquals(
            $removedParams,
            strtok($removedParams, '?')
        );
    }
}
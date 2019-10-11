<?php

namespace App\Support\Tests;

use App\Support\Url;
use TestCase;

class UrlTest extends TestCase
{
    public function test_build_url()
    {
        $this->assertEquals(
            'https://www.example.com?foo=a&bar=b',
            Url::build('https://www.example.com?foo=a', ['bar' => 'b'])
        );

        $this->assertEquals(
            'http://www.example.com?foo=a',
            Url::build('http://www.example.com', ['foo' => 'a'])
        );
    }

    public function test_get_basename()
    {
        $this->assertEquals(
            '26d3cbc2027c3f1ef7f1d9346ae0eed3',
            Url::getBasenameFromPath('https://jojotoo-static.oss-cn-shanghai.aliyuncs.com/subject/l5TOr4oRe6p17jDl/26d3cbc2027c3f1ef7f1d9346ae0eed3.jpg')
        );
    }

    public function test_set_image_style()
    {
        $this->assertEquals(
            'https://jojotoo-static.oss-cn-shanghai.aliyuncs.com/subject/l5TOr4oRe6p17jDl/26d3cbc2027c3f1ef7f1d9346ae0eed3.jpg?x-oss-process=style/w768',
            Url::setImageStyle(
                'https://jojotoo-static.oss-cn-shanghai.aliyuncs.com/subject/l5TOr4oRe6p17jDl/26d3cbc2027c3f1ef7f1d9346ae0eed3.jpg',
                'w768'
            )
        );

        $this->assertEquals(
            'https://jojotoo-static.oss-cn-shanghai.aliyuncs.com/subject/l5TOr4oRe6p17jDl/26d3cbc2027c3f1ef7f1d9346ae0eed3.jpg?x-oss-process=style/w768',
            Url::setImageStyle(
                'https://jojotoo-static.oss-cn-shanghai.aliyuncs.com/subject/l5TOr4oRe6p17jDl/26d3cbc2027c3f1ef7f1d9346ae0eed3.jpg?x-oss-process=style/abc',
                'w768'
            )
        );
    }

    public function test_set_image_style_null()
    {
        $this->assertEquals(
            'https://jojotoo-static.oss-cn-shanghai.aliyuncs.com/subject/l5TOr4oRe6p17jDl/26d3cbc2027c3f1ef7f1d9346ae0eed3.jpg',
            Url::setImageStyle(
                'https://jojotoo-static.oss-cn-shanghai.aliyuncs.com/subject/l5TOr4oRe6p17jDl/26d3cbc2027c3f1ef7f1d9346ae0eed3.jpg?x-oss-process=style/w768',
                null
            )
        );
    }

    public function test_get_extension()
    {
        $this->assertEquals(
            'test',
            Url::getExtension('abc.test')
        );

        $this->assertEquals(
            'svg',
            Url::getExtension('https://oss.jojotoo.com/resources/jojotoo.svg')
        );
    }
}
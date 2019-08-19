<?php

namespace App\Tests\Helper;

use App\Helper\CrudHelper;
use PHPUnit\Framework\TestCase;

class CrudHelperTest extends TestCase
{
    /**
     * @dataProvider provideNamesAndPathSlugs
     * @param $inputName
     * @param $outputPathSLug
     */
    public function testGeneratePathSlug($inputName, $outputPathSLug)
    {
        $this->assertEquals($outputPathSLug, CrudHelper::generatePathSlug($inputName));
    }

    public function provideNamesAndPathSlugs()
    {
        return [
            [' ', '-'],
            ['a b', 'a-b'],
            ['a-b', 'a-b'],
            ['≠²³¢€½§·«»πœę©ß←↓→óþąśðæŋ’ə…łżźć„”ńµ≤≥', ''],
            ['QWERTYUIOPASDFGHJKLZXCVBNM', 'qwertyuiopasdfghjklzxcvbnm'],
            ['1234567890', '1234567890'],
            ['y!#$%&\'*+-=?^_`{|}~.[]"', 'y!#$%&\'*+-=?^_`{|}~.[]"'],
        ];
    }
}
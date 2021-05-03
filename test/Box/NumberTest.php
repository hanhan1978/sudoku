<?php
declare(strict_types=1);

use Hanhan1978\Sudoku\Box\Number;

class NumberTest extends \PHPUnit\Framework\TestCase
{


    /**
     * @test
     */
    public function isOriginal_true()
    {
        $number = new Number([1], false, true);
        $this->assertTrue($number->isOriginal());
    }

    /**
     * @test
     */
    public function isOriginal_false()
    {
        $number = new Number(null);
        $this->assertFalse($number->isOriginal());
    }

    /**
     * @test
     */
    public function decided_true()
    {
        $number = new Number([1]);
        $this->assertTrue($number->decided());
    }

    /**
     * @test
     */
    public function decided_false()
    {
        $number = new Number(null);
        $this->assertFalse($number->decided());
    }

}
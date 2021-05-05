<?php
declare(strict_types=1);

use Hanhan1978\Sudoku\Box\Box;
use Hanhan1978\Sudoku\Box\ProblemParser;

class BoxTest extends \PHPUnit\Framework\TestCase
{

    private Box $box;

    protected function setUp(): void
    {
        parent::setUp();
        $problem =
            <<<EOL
        006900800
        004060290
        300000000
        040000706
        700030000
        060020085
        200000600
        009500000
        005340000
        EOL;

        $this->box = ProblemParser::parse($problem);
    }

    /**
     * @test
     */
    public function valid_false_by_parcel()
    {
        $problem =
            <<<EOL
        006900800
        004060290
        300000000
        040200706
        700030000
        060020085
        200000600
        009500000
        005340000
        EOL;

        $box = ProblemParser::parse($problem);
        $this->assertFalse($box->valid());
    }

    /**
     * @test
     */
    public function valid_false_by_column()
    {
        $problem =
            <<<EOL
        006900800
        004060290
        300000000
        040000706
        700030000
        060020085
        200000600
        009500000
        005340200
        EOL;
        $box = ProblemParser::parse($problem);
        $this->assertFalse($box->valid());
    }

    /**
     * @test
     */
    public function valid_false_by_row()
    {
        $problem =
            <<<EOL
        006900800
        004069290
        300000000
        040000706
        700030000
        060020085
        200000600
        009500000
        005340000
        EOL;
        $box = ProblemParser::parse($problem);
        $this->assertFalse($box->valid());
    }

    /**
     * @test
     */
    public function valid_true()
    {
        $problem =
            <<<EOL
        006900800
        004060290
        300000000
        040000706
        700030000
        060020085
        200000600
        009500000
        005340000
        EOL;
        $box = ProblemParser::parse($problem);
        $this->assertTrue($box->valid());
    }


    /**
     * @test
     */
    public function getParcel_00()
    {
        $expected = [0,0,6,0,0,4,3,0,0];
        $this->assertSame($expected, $this->box->getParcel(0, 0)->flatten());
        $numberList = $this->box->getParcel(0, 0);
        foreach($numberList as $i => $num){
            if($i===0){
                list($x, $y) = $num->getXY();
                $this->assertSame(0, $x);
                $this->assertSame(0, $y);
            }
            if($i===8){
                list($x, $y) = $num->getXY();
                $this->assertSame(2, $x);
                $this->assertSame(2, $y);
            }
        }
    }
    /**
     * @test
     */
    public function getParcel_88()
    {
        $expected = [6,0,0,0,0,0,0,0,0];
        $this->assertSame($expected, $this->box->getParcel(8, 8)->flatten());
    }

    /**
     * @test
     */
    public function getColumn_first()
    {
        $expected = [0,0,3,0,7,0,2,0,0];
        $this->assertSame($expected, $this->box->getColumn(0)->flatten());
    }

    /**
     * @test
     */
    public function getColumn_last()
    {
        $expected = [0,0,0,6,0,5,0,0,0];
        $this->assertSame($expected, $this->box->getColumn(8)->flatten());
    }

    /**
     * @test
     */
    public function getRow_first()
    {
        $expected = [0,0,6,9,0,0,8,0,0];
        $this->assertSame($expected, $this->box->getRow(0)->flatten());
    }

    /**
     * @test
     */
    public function getRow_last()
    {
        $expected = [0,0,5,3,4,0,0,0,0];
        $this->assertSame($expected, $this->box->getRow(8)->flatten());
    }

    /**
     * @test
     */
    public function next_first()
    {
        list($x, $y, $number) = $this->box->next();

        $this->assertSame(0, $x);
        $this->assertSame(0, $y);
        $this->assertSame(0, $number->digit());
    }

    /**
     * @test
     */
    public function next_3rd()
    {
        $this->box->next();
        $this->box->next();
        list($x, $y, $number) = $this->box->next();

        $this->assertSame(2, $x);
        $this->assertSame(0, $y);
        $this->assertSame(6,$number->digit());
    }

    /**
     * @test
     */
    public function next_last()
    {
        for($i=0 ; $i < 80; $i++){
            $this->box->next();
        }
        list($x, $y, $number) = $this->box->next();

        $this->assertSame(8, $x);
        $this->assertSame(8, $y);
        $this->assertSame(0,$number->digit());
    }
    /**
     * @test
     */
    public function next_null()
    {
        for($i=0 ; $i < 81; $i++){
            $this->box->next();
        }
        $this->assertNull($this->box->next());

        list($x, $y, $number) = $this->box->next();
        $this->assertSame(0, $x);
        $this->assertSame(0, $y);
        $this->assertSame(0, $number->digit());
    }
}
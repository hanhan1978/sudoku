<?php

use Hanhan1978\Sudoku\Box\Number;

class OrphanTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var Unique
     */
    private \Hanhan1978\Sudoku\Solver\SolverInterface $solver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->solver = new \Hanhan1978\Sudoku\Solver\Orphan();
    }

    /**
     * @test
     */
    public function solve_hard(){
        $problem =
            <<<EOL
        500040000
        004007360
        620580400
        800000020
        007031000
        950004803
        000000086
        000010047
        000050000
        EOL;

        $box = \Hanhan1978\Sudoku\Box\ProblemParser::parse($problem);
        $box = (new \Hanhan1978\Sudoku\Solver\Unique())->solve($box);
        $box = $this->solver->solve($box);
        //Parcel Orphan
        $this->assertSame(8, $box->getNumber(3, 4)->digit());
        //Row Orphan
        $this->assertSame(2, $box->getNumber(0, 4)->digit());
        //Column Orphan
        $this->assertSame(1, $box->getNumber(7, 5)->digit());

        $this->assertTrue($box->valid());
    }
}
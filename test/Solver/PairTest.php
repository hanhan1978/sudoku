<?php


class PairTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var Unique
     */
    private \Hanhan1978\Sudoku\Solver\SolverInterface $solver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->solver = new \Hanhan1978\Sudoku\Solver\Pair();
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
        $box = (new \Hanhan1978\Sudoku\Solver\Unique())->solve($box);
        $this->assertSame(8, $box->getNumber(1,1)->digit());
    }

}
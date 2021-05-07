<?php


class GuessTest extends \PHPUnit\Framework\TestCase
{


    /**
     * @var Unique
     */
    private \Hanhan1978\Sudoku\Solver\SolverInterface $solver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->solver = new \Hanhan1978\Sudoku\Solver\Guess();
    }

    /**
     * @test
     */
    public function solve_flash()
    {
        $problem =
            <<<EOL
        072900306
        059806721
        681020054
        538492600
        200310040
        107008003
        810639570
        000100409
        005274030
        EOL;

        $box = \Hanhan1978\Sudoku\Box\ProblemParser::parse($problem);
        $this->assertTrue($this->solver->solve($box)->solved());
    }

    /**
     * @test
     */
    public function solve_hard()
    {
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
        $this->assertTrue($this->solver->solve($box)->solved());
    }

    /**
     * @test
     * ネット上で拾える、最も難しいといわれる問題
     */
    public function solve_worst()
    {
        $problem =
            <<<EOL
        800000000
        003600000
        070090200
        050007000
        000045700
        000100030
        001000068
        008500010
        090000400
        EOL;

        $box = \Hanhan1978\Sudoku\Box\ProblemParser::parse($problem);
        $this->assertTrue($this->solver->solve($box)->solved());
    }
}
<?php
use \Hanhan1978\Sudoku\Solver\SolverInterface;
use \Hanhan1978\Sudoku\Solver\Unique;
use Hanhan1978\Sudoku\Box\ProblemParser;

class UniqueTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var Unique
     */
    private SolverInterface $solver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->solver = new Unique();
    }

    /**
     * @test
     */
    public function solve_easy(){
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

        $box = ProblemParser::parse($problem);
        $box = $this->solver->solve($box);
        $box = $this->solver->solve($box);
        $this->assertTrue($box->solved());
        $this->assertTrue($box->valid());
    }

}
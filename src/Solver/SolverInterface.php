<?php
declare(strict_types=1);

namespace Hanhan1978\Sudoku\Solver;

use Hanhan1978\Sudoku\Box\Box;

interface SolverInterface
{

    public function solve(Box $box) :Box;

}
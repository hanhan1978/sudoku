<?php
declare(strict_types=1);

namespace Hanhan1978\Sudoku\Solver;

use Hanhan1978\Sudoku\Box\Box;

/**
 * 解法クラスのインターフェース
 *
 * Boxを引数で受け取って、BOXを返す solve メソッドを約束として持つ
 *
 * Interface SolverInterface
 * @package Hanhan1978\Sudoku\Solver
 */
interface SolverInterface
{

    public function solve(Box $box) :Box;

}
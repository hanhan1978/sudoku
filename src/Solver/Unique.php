<?php


namespace Hanhan1978\Sudoku\Solver;


use Hanhan1978\Sudoku\Box\Box;
use Hanhan1978\Sudoku\Box\Number;

/**
 * Class Unique
 *
 * もっとも単純な解法。横軸、縦軸、自分がいる９マス正方形内で候補の消し込みを行う
 *
 * @package Hanhan1978\Sudoku\Solver
 */
class Unique implements SolverInterface
{

    public function solve(Box $box): Box
    {
        /**
         * @var int $x
         * @var int $y
         * @var Number $number
         */
        while(list($x, $y, $number) = $box->next()){
            if(!$number->decided()) {
                $cand = array_diff([0, 1, 2, 3, 4, 5, 6, 7, 8, 9], array_unique(array_merge($box->getRow($y)->flatten(), $box->getColumn($x)->flatten(), $box->getParcel($x, $y)->flatten())));
                $box->append($x, $y, new Number($cand, count($cand) < $number->candidateCount()));
            }else{
                $box->append($x, $y, new Number([$number->digit()], false, $number->isOriginal()));
            }
        }
        return $box;
    }

}
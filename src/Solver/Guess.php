<?php


namespace Hanhan1978\Sudoku\Solver;


use Hanhan1978\Sudoku\Box\Box;
use Hanhan1978\Sudoku\Box\Number;

/**
 * 仮置きを使って、解く。最強の解法。
 *
 * Class Guess
 * @package Hanhan1978\Sudoku\Solver
 */
class Guess implements SolverInterface
{
    /**
     * @var SolverInterface[]
     */
    private array $solvers;

    public function __construct()
    {
        $this->solvers[] = new Unique();
        $this->solvers[] = new Pair();
        $this->solvers[] = new Orphan();
    }




    public function solve(Box $box): Box
    {
        //どんどん破壊的変更で更新するのでcloneしとく
        $newBox = clone $box;
        /**
         * @var int $x
         * @var int $y
         * @var Number $number
         */
        while(list($x, $y, $number) = $newBox->next()) {
            if (!$number->decided()) break;
        }
        if($number == null){
            return $newBox;
        }

        //仮おき数字を取得
        foreach($number->getCandidates() as $digit){
            $tempBox = clone $newBox;
            $tempBox->append($x, $y, new Number([$digit], true));
            //仮置きした状態で論理ソルバーで解いてみる
            foreach($this->solvers as $solver){
                $tempBox = $solver->solve($tempBox);
            }
            if(!$tempBox->valid()){
                //矛盾がでたらやめる
                continue;
            }

            //矛盾がなければ仮置きを縦に続行する
            $tempBox = $this->solve($tempBox);
            if($tempBox->solved()){
                return $tempBox;
            }
        }
        return $newBox;
    }

}
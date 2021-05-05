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
#        $this->solvers[] = new Pair();
        $this->solvers[] = new Orphan();
    }

    public function solve(Box $box): Box
    {
        //効率的に仮置きするために、候補数がすくないセルをターゲットにして仮置きをトライしていく
        $minCandidateCount = $this->getMinimumCandidateCount($box);
        $newBox = clone $box;

        //無限ループブレーカー
        $breaker = 0;
        $histories = [];
        while($breaker < 200 && !$newBox->solved()){
            $breaker++;
            $histories[] = $newBox;
            $tempBox = clone $newBox;
            //仮置き数字を探す
            list($x, $y, $guessDigit) = $this->guess($tempBox, $minCandidateCount);
            //推測にミスがある場合は、最初からやり直し
            if(is_null($x)){
                $histories = [];
                $newBox = clone $box;
                continue;
            }
            //仮置きする
            $tempBox->append($x, $y, new Number([$guessDigit], true));

            //仮置きを元にして、ロジカルに解く
            foreach($this->solvers as $solver){
                $tempBox = $solver->solve($tempBox);
            }

            //推測が間違っている場合、Boxが壊れる
            if(!$tempBox->valid()){
                //試行回数が多すぎる場合は、最初の方の推測が間違っているので0からやり直し
                if(is_int($breaker/20)){
                    $histories = [];
                    $newBox = clone $box;
                    continue;
                }

                //試行回数が少ないうちは、一個前の推測状態に戻して、次の推測をやり直す
                if(count($histories) > 0){
                    $newBox = array_pop($histories);
                }
                continue;
            }
            $newBox = $tempBox;
        }
        return $newBox;
    }

    private function guess(Box $box, int $minCandidateCount){
        /**
         * @var int $x
         * @var int $y
         * @var \Hanhan1978\Sudoku\Box\Number $number
         */
        while(list($x, $y, $number) = $box->next()) {
            if($number->decided() || $number->candidateCount() > $minCandidateCount) continue;

            //誤った推測で候補が0個になる現象への対処
            if(empty($number->getCandidates())){
                return null;
            }
            //候補の中から、ランダムに数字を選ぶ
            //誤った推測を連発する可能性もあるが、既出の推測を状態管理するのが面倒なのでランダムにしている
            $guessDigit = $number->getCandidates()[array_rand($number->getCandidates())];
            return [$x, $y, $guessDigit];
        }
        return null;
    }

    /**
     * @param Box $box
     * @return int
     */
    private function getMinimumCandidateCount(Box $box):int
    {
        $min = 9;
        while(list($x, $y, $number) = $box->next()) {
            if($number->decided()) continue;
            $min = min($min, $number->candidateCount());
        }
        return $min;
    }
}
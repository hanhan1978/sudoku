<?php


namespace Hanhan1978\Sudoku\Solver;


use Hanhan1978\Sudoku\Box\Box;
use Hanhan1978\Sudoku\Box\Number;

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
        $minCandidateCount = $this->getMinimumCandidateCount($box);
        $newBox = clone $box;

        $breaker = 0;
        $histories = [];
        while($breaker < 200 && !$newBox->solved()){
            $breaker++;
            $histories[] = $newBox;
            $tempBox = clone $newBox;
            list($x, $y, $guessDigit) = $this->guess($tempBox, $minCandidateCount);
            //推測にミスがある場合は、最初からやり直し
            if(is_null($x)){
                $histories = [];
                $newBox = clone $box;
                continue;
            }
            //仮おきする
            $tempBox->append($x, $y, new Number([$guessDigit], true));

            //仮おきを元にして、ロジカルに解く
            foreach($this->solvers as $solver){
                $tempBox = $solver->solve($tempBox);
            }

            //仮おきが間違っている場合、Boxが壊れる
            if(!$tempBox->valid()){
                //試行回数が多すぎる場合は、最初の推測が間違っているのでやり直し
                if(is_int($breaker/20)){
                    $histories = [];
                    $newBox = clone $box;
                    continue;
                }

                //一個前のBoxに戻して、推測をやり直す
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

            if(empty($number->getCandidates())){
                return null;
            }
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
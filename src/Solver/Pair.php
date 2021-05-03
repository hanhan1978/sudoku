<?php


namespace Hanhan1978\Sudoku\Solver;


use Hanhan1978\Sudoku\Box\Box;
use Hanhan1978\Sudoku\Box\Number;

class Pair implements SolverInterface
{

    public function solve(Box $box): Box
    {
        $newBox = clone $box;

        $pairs = [];
        /**
         * @var int $x
         * @var int $y
         * @var Number $number
         */
        while(list($x, $y, $number) = $newBox->next()){
            if($number->candidateCount() === 2){
                $pairs[$y][$x] = $number->getCandidates();
            }
        }
        foreach($pairs as $y => $row){
            foreach($row as $x => $pair){
                //fix y
                if($this->haveXPairFriends($x, $y, $pair, $pairs)){
                    foreach($newBox->getRow($y) as $tx => $num){
                        if($num->decided()) continue;
                        $cands = array_diff($newBox->getNumber($tx, $y)->getCandidates(), $pair);
                        $newBox->append($tx, $y, new Number($cands, count($cands) <$newBox->getNumber($tx, $y)->candidateCount()));
                    }
                }
                //fix x
                if($this->haveYPairFriends($x, $y, $pair, $pairs)){
                    foreach($newBox->getColumn($x) as $ty => $num){
                        if($num->decided()) continue;
                        $cands = array_diff($newBox->getNumber($x, $ty)->getCandidates(), $pair);
                        $newBox->append($tx, $y, new Number($cands, count($cands) <$newBox->getNumber($tx, $y)->candidateCount()));
                    }
                }
            }
        }
        return $newBox;
    }

    private function haveXPairFriends(int $tx, int $ty, $pair, $pairs)
    {
        foreach($pairs as $y => $row){
            if($y !== $ty) { continue;
            }
            foreach($row as $x => $v){
                if($x === $tx) { continue;
                }
                if(count(array_diff($pair, $v)) === 0) {
                    return true;
                }
            }
        }
        return false;
    }

    private function haveYPairFriends(int $tx, int $ty, $pair, $pairs)
    {
        foreach($pairs as $y => $row){
            foreach($row as $x => $v){
                if($x !== $tx) { continue;
                }
                if($y === $ty) { continue;
                }
                if(count(array_diff($pair, $v)) === 0) {
                    return true;
                }
            }
        }
        return false;

    }
}
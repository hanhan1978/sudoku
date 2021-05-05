<?php


namespace Hanhan1978\Sudoku\Solver;


use Hanhan1978\Sudoku\Box\Box;
use Hanhan1978\Sudoku\Box\Number;

/**
 * 縦軸、横軸、９マス正方形において、a, b の２つの数字のみを候補とするセルが２つ存在する場合、それ以外のマスから
 * a,b を候補から消せる
 *
 * Class Pair
 * @package Hanhan1978\Sudoku\Solver
 */
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
                $pairs[] = $number;
            }
        }

        foreach($pairs as $pair){
            list($x, $y) = $pair->getXY();

            //fix y
            if($this->haveXPairFriends($pair, $pairs)){
                foreach($newBox->getRow($y) as $tNum){
                    if($tNum->decided()) continue;
                    $cands = array_diff($tNum->getCandidates(), $pair->getCandidates());
                    if(count($cands) === 0) continue;
                    list($tx, $ty) = $tNum->getXY();
                    $newBox->append($tx, $ty, new Number($cands, count($cands) <$tNum->candidateCount()));
                }
            }
            //fix x
            if($this->haveYPairFriends($pair, $pairs)){
                foreach($newBox->getColumn($x) as $tNum){
                    if($tNum->decided()) continue;
                    $cands = array_diff($tNum->getCandidates(), $pair->getCandidates());
                    if(count($cands) === 0) continue;
                    list($tx, $ty) = $tNum->getXY();
                    $newBox->append($tx, $ty, new Number($cands, count($cands) <$tNum->candidateCount()));
                }
            }
        }
        return $newBox;
    }

    /**
     * @param Number $number
     * @param Number[] $pairs
     * @return bool
     */
    private function haveXPairFriends(Number $number, array $pairs)
    {
        list($x, $y) = $number->getXY();
        foreach($pairs as $pair){
            list($tx, $ty) = $pair->getXY();
            if($y !== $ty) {
                continue;
            }
            if($tx == $x) {
                continue;
            }
            if(count(array_diff($pair->getCandidates(), $number->getCandidates())) === 0){
                return true;
            }
        }
        return false;
    }

    /**
     * @param Number $number
     * @param Number[] $pairs
     * @return bool
     */
    private function haveYPairFriends(Number $number, array $pairs)
    {
        list($x, $y) = $number->getXY();
        foreach($pairs as $pair){
            list($tx, $ty) = $pair->getXY();
            if($x !== $tx) {
                continue;
            }
            if($y === $ty) {
                continue;
            }
            if(count(array_diff($pair->getCandidates(), $number->getCandidates())) === 0) {
                return true;
            }
        }
        return false;
    }
}
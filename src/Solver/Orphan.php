<?php

namespace Hanhan1978\Sudoku\Solver;

use Hanhan1978\Sudoku\Box\Box;
use Hanhan1978\Sudoku\Box\Number;
use Hanhan1978\Sudoku\Box\NumberList;

/**
 * 縦軸、横軸、９マス正方形内で、あるひとつの数字がそのマス以外には入らないことを見つける。
 * Uniqueに似ているが、Uniqueは候補を絞り込むのに対して、Orphan は候補が複数あるなかで、そのマスのみにしか存在しえない数字を見つける。
 *
 * Class Orphan
 * @package Hanhan1978\Sudoku\Solver
 */
class Orphan implements SolverInterface
{

    public function solve(Box $box): Box
    {
        /**
         * @var int $x
         * @var int $y
         * @var Number $number
         */
        while(list($x, $y, $number) = $box->next()){
            if($number->decided()){
                $box->append($x, $y, new Number([$number->digit()], $number->updated() , $number->isOriginal()));
                continue;
            }

            //findColumn
            foreach($this->findOrphan($box->getColumn($x)) as $number){
                list($ox, $oy) = $number->getXY();
                $box->append($ox, $oy, new Number($number->getCandidates(), true));
            }

            //findRow
            foreach($this->findOrphan($box->getRow($y)) as $number){
                list($ox, $oy) = $number->getXY();
                $box->append($ox, $oy, new Number($number->getCandidates(), true));
            }

            //findParcel
            foreach($this->findOrphan($box->getParcel($x, $y)) as $number){
                list($ox, $oy) = $number->getXY();
                $box->append($ox, $oy, new Number($number->getCandidates(), true));
            }

        }
        return $box;
    }

    /**
     * @param NumberList $list
     * @return NumberList[]
     */
    private function findOrphan(NumberList $list) :array
    {
        $count = [];
        foreach($list as $number){
            foreach($number->getCandidates() as $n){
                $count[$n][] = $number;
            }
        }
        $orphans = [];
        foreach($count as $k=>$v){
            if(count($v) === 1){
                $v[0]->setCandidates([$k]);
                $orphans[] = $v[0];
            }
        }
        return $orphans;
    }


}
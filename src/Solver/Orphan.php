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
        $newBox = clone $box;
        /**
         * @var int $x
         * @var int $y
         * @var Number $number
         */
        while(list($x, $y, $number) = $newBox->next()){
            if($number->decided()){
                $newBox->append($x, $y, new Number([$number->digit()], $number->updated() , $number->isOriginal()));
                continue;
            }

            //findColumn
            foreach($this->findOrphan($newBox->getColumn($x)) as $number){
                list($ox, $oy) = $number->getXY();
                $newBox->append($ox, $oy, new Number($number->getCandidates(), true));
            }

            //findRow
            foreach($this->findOrphan($newBox->getRow($y)) as $number){
                list($ox, $oy) = $number->getXY();
                $newBox->append($ox, $oy, new Number($number->getCandidates(), true));
            }

            //findParcel
            foreach($this->findOrphan($newBox->getParcel($x, $y)) as $number){
                list($ox, $oy) = $number->getXY();
                $newBox->append($ox, $oy, new Number($number->getCandidates(), true));
            }

        }
        return $newBox;
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
                if(isset($count[$n])){
                    $count[$n]++;
                }else{
                    $count[$n]=1;
                }
            }
        }
        $on = [];
        foreach($count as $k=>$v){
            if($v === 1){
                $on[] = $k;
            }
        }

        $orphans=[];
        foreach($list as $number){
            foreach($on as $o){
                if($key = array_search($o, $number->getCandidates())){
                    $number->setCandidates([$o]);
                    $orphans[] = $number;
                }
            }
        }
        return $orphans;
    }


}
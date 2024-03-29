<?php
declare(strict_types=1);

namespace Hanhan1978\Sudoku\Box;

/**
 * 数独全体の箱を表す
 * 数字は $grid に二次元配列で保持する
 *
 *
 *
 * Class Box
 * @package Hanhan1978\Sudoku\Box
 */
class Box
{
    /**
     * @var Number[][]
     */
    private array $grid;


    public function __construct()
    {
        $this->grid = [];
    }

    public function append(int $x, int $y, Number $number){
        $number->setXY($x, $y);
        $this->grid[$y][$x] = $number;
    }

    public function dump(int $x, int $y)
    {
        var_dump($this->grid[$y][$x]);
    }

    /**
     * @param int $x
     * @param int $y
     * @return Number
     */
    public function getNumber(int $x, int $y) :Number{
        return $this->grid[$y][$x];
    }


    public function display()
    {
        foreach($this->grid as $y => $row){
            foreach($row as $x => $v){
                $space = " ";
                if($v->isOriginal()) {
                    $space = '.';
                }else if($v->updated()) {
                    $space = '*';
                }
                echo $space.$v->digit();
            }
            echo "\n";
        }
    }

    public function getColumn(int $x) :NumberList
    {
        $column = new NumberList();
        foreach($this->grid as $y => $row){
            $column->append($row[$x]);
        }
        return $column;
    }

    public function getRow(int $y) :NumberList
    {
        $row = new NumberList();
        foreach($this->grid[$y] as $x => $r){
            $row->append($r);
        };
        return $row;
    }

    public function getParcel(int $x, int $y) :NumberList
    {
        $tx = (int)(floor($x / 3) * 3);
        $ty = (int)(floor($y / 3) * 3);

        $parcel = new NumberList();
        for($y=$ty; $y < $ty+3; $y++){
            $row = $this->grid[$y];
            for($x=$tx; $x < $tx+3; $x++){
                $parcel->append($row[$x]);
            }
        }
        return $parcel;
    }

    public function valid() :bool
    {
        for($i=0; $i<9; $i++){
            if(!$this->getColumn($i)->valid()) {
                return false;
            }
            if(!$this->getRow($i)->valid()) {
                return false;
            }
        }
        foreach([0,3,6] as $y){
            foreach([0,3,6] as $x){
                if(!$this->getParcel($x, $y)->valid()) {
                    return false;
                }
            }
        }
        return true;
    }

    private int $x=0;
    private int $y=0;

    public function next() :?array
    {
        //initialize when overflow
        if($this->y > 8){
            $this->y = 0;
            return null;
        }
        $result = [$this->x, $this->y, $this->grid[$this->y][$this->x]];
        if($this->x === 8) {
            $this->x = 0;
            $this->y++;
        }else{
            $this->x++;
        }
        return $result;
    }

    public function solved() :bool
    {
        /**
         * @var int $x
         * @var int $y
         * @var Number $number
         */
        while(list($x, $y, $number) = $this->next()) {
            if(!$number->decided()){
                $this->x = 0;
                $this->y = 0;
                return false;
            }
        }
        $this->x = 0;
        $this->y = 0;
        return true;
    }

    function __clone() {
        /**
         * @var int $x
         * @var int $y
         * @var Number $number
         */
        while(list($x, $y, $number) = $this->next()) {
            $clone = new Number($number->getCandidates(), $number->updated(), $number->isOriginal());
            $clone->setXY($x, $y);
            $this->grid[$y][$x] = $clone;
        }
    }
}
<?php
require dirname(__FILE__).'/vendor/autoload.php';
use Colors\Color;
/**
 2 |  1|95 
  9|8  |   
   |  4| 63
-----------
6  | 82|  1
29 |5 7|834
 73|14 |526
-----------
5 4|2 6| 7 
91 | 7 |   
 3 |  8| 1 
**/
$ls[] = [3,0,0,0,0,0,0,1,4];
$ls[] = [0,9,8,0,5,0,3,6,2];
$ls[] = [0,0,1,0,0,3,9,0,7];
$ls[] = [0,0,0,0,6,5,0,0,0];
$ls[] = [0,0,4,0,9,7,0,0,8];
$ls[] = [0,7,0,8,0,1,2,0,0];
$ls[] = [5,4,0,0,1,6,0,2,0];
$ls[] = [6,3,2,5,8,9,7,0,0];
$ls[] = [1,0,0,4,0,2,0,0,0];


$con = new Container($ls);

$con->display();

$tat = new Tactics($con);
$tat->solve();
$tat->solve();
$tat->solve();
$tat->solve();
$tat->solve();

echo PHP_EOL;
$con->display();


class Tactics {
    private $con;
    public function __construct(Container $con){
        $this->con = $con;
    }

    public function solve()
    {
        for($i=0;$i<(9*9);$i++){
            $cell = $this->con->getCell($i);
            if($cell->num != 0){
                continue;
            }
            $box = $this->con->getBox($i)->getResidue();
            $col = $this->con->getCol($i)->getPresent();
            $line = $this->con->getLine($i)->getPresent();
            
            $cand = array_diff($box, $col, $line);
            if(is_array($cand) && count($cand) == 1){
                $cell->num = array_shift($cand);
            }
        }
    }
}
class Sudoku {
    public $cells = [];

    public function __construct(array $cells)
    {
        $this->cells = $cells;
    }

    public function getPresent(){

        $nums=[];
        foreach($this->cells as $cell){
            if($cell->num !=0){
                $nums[]=$cell->num;
            }
        }
        return $nums;
    }
    public function getResidue(){

        $nums=[];
        foreach($this->cells as $cell){
            if($cell->num !=0){
                $nums[]=$cell->num;
            }
        }
        $base = [1,2,3,4,5,6,7,8,9];
        return array_diff($base, $nums);
    }
}


class Line extends Sudoku{

    public function display()
    {
        foreach($this->cells as $cell){
            $cell->display();
        }
        print(PHP_EOL);
    }
}

class Col extends Sudoku{

    public function display()
    {
        foreach($this->cells as $cell){
            $cell->display();
            print(PHP_EOL);
        }
    }
}

class Box extends Sudoku{
    public function display()
    {
        $i=0;
        foreach($this->cells as $cell){
            $cell->display();
            if($i%3 == 2){
                print(PHP_EOL);
            }
            $i++;
        }
    }
}

class Container {

    private $cells = [];
    private $lines = [];
    private $boxes = [];
    private $cols = [];

    const BOX_SIZE = 9;

    public function __construct(array $lines)
    {
        foreach($lines as $line){
            foreach($line as $num){
                $preset = $num != 0;
                $this->cells[] = new Cell($num, $preset);
            }
        }
        $this->setLines();
        $this->setCols();
        $this->setBoxes();
    }

    private function setBoxes()
    {
        for($i=0; $i<self::BOX_SIZE; $i++){
            $boxes = [];
            $index = floor($i/3) * 27 + ($i%3)*3;
            for($j=0; $j<self::BOX_SIZE; $j++){
                $id = $index + floor($j/3) * 9 + ($j%3);
                $boxes[] = $this->cells[$id];
            }
            $this->boxes[] = new Box($boxes);
        }
    }

    public function getCell($id){
        return $this->cells[$id];
    }

    private function setLines()
    {
        $lines = [];
        foreach($this->cells as $i => $cell){
            $lines[] = $cell;
            if($i % self::BOX_SIZE == 8){
                $this->lines[] = new Line($lines);
                $lines = [];
            }
        }
    }

    private function setCols()
    {
        for($i=0; $i< self::BOX_SIZE; $i++){
            $cols = [];
            for($j=0; $j< self::BOX_SIZE; $j++){
                $index = $i + $j * self::BOX_SIZE;
                $cols[] = $this->cells[$index];
            }
            $this->cols[] = new Col($cols);
        }
    }

    public function getBox(int $index)
    {
        $row = floor($index / 27);
        $col = floor($index % 9 / 3);
        return $this->boxes[($row *3 + $col)];
    }

    public function getLine(int $index)
    {
        return $this->lines[floor($index/9)];
    }

    public function getCol(int $index)
    {
        return $this->cols[$index%9];
    }

    public function display()
    {
        $i=0;
        foreach($this->cells as $cell){
            $cell->display();
            if($i%3 == 2){
                echo '|';
            }
            if($i%9 == 8){
                echo PHP_EOL;
            }
            if($i%27 == 26){
                echo "------------\n";
            }
            $i++;
        }
    }

}
class Cell {

    public $num;
    private $preset;

    public function __construct(int $num, bool $preset)
    {
        $this->num = $num;
        $this->preset = $preset;
    }

    public function display()
    {
        $c = new Color();
        if($this->preset){
            print $c($this->num)->red;
        }else{
            if($this->num == 0){
                print $this->num;
            }else{
                print $c($this->num)->cyan;
            }
        }
    }

}
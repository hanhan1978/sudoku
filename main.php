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
$ls[] = [0,2,0,0,0,1,9,5,0];
$ls[] = [0,0,9,8,0,0,0,0,0];
$ls[] = [0,0,0,0,0,4,0,6,3];
$ls[] = [6,0,0,0,8,2,0,0,1];
$ls[] = [2,9,0,5,0,7,8,3,4];
$ls[] = [0,7,3,1,4,0,5,2,6];
$ls[] = [5,0,4,2,0,6,0,7,0];
$ls[] = [9,1,0,0,7,0,0,0,0];
$ls[] = [0,3,0,0,0,8,0,1,0];


$box = new Container($ls);

$box->display();

class Cell {

    private $num;
    private $preset;

    public function __construct(int $num, bool $preset)
    {
        $this->num = $num;
        $this->preset = $preset;
    }

    public function display()
    {
        if($this->preset){
            $c = new Color();
            print $c($this->num)->red;
        }else{
            print $this->num;
        }
    }

}

class Line {

    public $cells = [];

    public function __construct(array $cells)
    {
        $this->cells = $cells;
    }

    public function display()
    {
        foreach($this->cells as $cell){
            $cell->display();
        }
        print(PHP_EOL);
    }
}

class Col {

    public $cells = [];

    public function __construct(array $cells)
    {
        $this->cells = $cells;
    }

    public function display()
    {
        foreach($this->cells as $cell){
            $cell->display();
            print(PHP_EOL);
        }
    }
}

class Box {
    public $cells = [];

    public function __construct(array $cells)
    {
        $this->cells = $cells;
    }

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
        return $this->boxes[$index];
    }

    public function getLine(int $index)
    {
        return $this->lines[$index];
    }

    public function getCol(int $index)
    {
        return $this->cols[$index];
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
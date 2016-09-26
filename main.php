<?php
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

$lines = [];
foreach($ls as $l){
  $lines[] = new Line($l);
}

$box = new Box($lines);

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
        echo $this->num;
    }

}

class Line {

    public $cells = [];

    public function __construct(array $nums)
    {
        foreach($nums as $num){
            $preset = $num == 0 ? false : true;
            $this->cells[] = new Cell($num, $preset);
        }
    }
}

class Box {

    private $lines = [];

    public function __construct(array $lines)
    {
        $this->lines = $lines;
    }

    public function display()
    {
        $i=0;
        foreach($this->lines as $line){
            foreach($line->cells as $cell){
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
}
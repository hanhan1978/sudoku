<?php

$c = new Cell([0,2,0,0,0,9,0,0,0]);

$c->print_cell();

print_r($c->candidates());

class Cell {

    private $cells;

    public function __construct(array $cells)
    {
        $this->cells = $cells;
    }

    public function candidates()
    {
        $arr1 = [1,2,3,4,5,6,7,8,9];
        return array_diff($arr1, $this->cells);
        //return array_filter($this->cells, function($val){ return $val !== 0;});
    }


    public function print_cell()
    {
        for($i=0; $i < count($this->cells); $i++)
        {
            print ($this->cells[$i] === 0) ? ' ' : $this->cells[$i];
            if($i%3 == 2){
                print(PHP_EOL);
            }
        }
    }
}
<?php
declare(strict_types=1);

namespace Hanhan1978\Sudoku\Box;


class NumberList implements \IteratorAggregate
{
    /**
     * @var Number[]
     */
    private array $list;

    public function append(Number $number)
    {
        $this->list[] = $number;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->list);
    }

    public function valid()
    {
        $arr = [];
        foreach($this->flatten() as $t){
            if($t !== 0){
                if(isset($arr[$t])){
                    return false;
                }
                $arr[$t] = 1;
            }
        }
        return true;
    }

    public function flatten() :array
    {
        return array_map(fn($n) => $n->digit(), $this->list);
    }
}
<?php
declare(strict_types=1);

namespace Hanhan1978\Sudoku\Box;

/**
 * 9個の数字の集まりを表すリストクラス
 *
 * Class NumberList
 * @package Hanhan1978\Sudoku\Box
 */
class NumberList implements \IteratorAggregate
{
    /**
     * @var Number[]
     */
    private array $list;

    /**
     * @var int[]
     */
    private array $flattend;

    public function append(Number $number)
    {
        $this->list[] = $number;
        $this->flattend[] = $number->digit();
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->list);
    }

    /**
     * 9個の数字の集まりに矛盾がないかを判断する
     * @return bool
     */
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
        return $this->flattend;
    }
}
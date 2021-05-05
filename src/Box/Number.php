<?php
declare(strict_types=1);

namespace Hanhan1978\Sudoku\Box;

/**
 * 数字を表すクラス
 * 候補を表す $candidates が1つだけになったら、数字が決定しているとみなす
 *
 * Class Number
 * @package Hanhan1978\Sudoku\Box
 */
class Number
{
    /**
     * @var int[]
     */
    private array $candidates;

    /**
     * @var bool
     */
    private bool $updated;

    /**
     * @var bool
     */
    private bool $original;

    private int $x;

    private int $y;

    /**
     * Number constructor.
     * @param int[]|null $candidates
     */
    public function __construct(?array $candidates, bool $updated = false, bool $original = false)
    {
        if(is_null($candidates)){
            $this->candidates = [1,2,3,4,5,6,7,8,9];
        }else{
            $this->candidates = $candidates;
        }
        $this->updated = $updated;
        $this->original = $original;
    }

    public function isOriginal() :bool
    {
        return $this->original;

    }

    public function updated() :bool
    {
        return $this->updated;
    }

    public function decided() :bool
    {
        return count($this->candidates) === 1;
    }

    public function digit() :int
    {
        return $this->decided() ? array_slice($this->candidates, 0, 1)[0] : 0;
    }

    public function candidateCount() :int
    {
        return count($this->candidates);
    }

    /**
     * @return int[]
     */
    public function getCandidates(): array
    {
        return $this->candidates;
    }

    public function setCandidates(array $candidates): void
    {
        $this->candidates = $candidates;
    }

    /**
     * 数字に、後付でXYをもたせるために作った関数。クラスの設計し直しで消したい。
     *
     * @param int $x
     * @param int $y
     */
    public function setXY(int $x, int $y) :void
    {
        $this->x = $x;
        $this->y = $y;
    }

    public function getXY() :array
    {
        return [$this->x, $this->y];
    }
}



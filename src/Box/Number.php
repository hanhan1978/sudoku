<?php
declare(strict_types=1);

namespace Hanhan1978\Sudoku\Box;

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

}



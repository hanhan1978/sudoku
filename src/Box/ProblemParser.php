<?php


namespace Hanhan1978\Sudoku\Box;


class ProblemParser
{
    /**
     * Box Parser.
     *
     *    x
     *  y 006900800
     *    004060290
     *    300000000
     *    040000706
     *    700030000
     *    060020085
     *    200000600
     *    009500000
     *    005340000
     *
     * @param string $problem
     * @return Box
     */
    public static function parse(string $problem) :Box
    {
        $box = new Box();
        $pp = preg_split("/\n/", $problem);
        foreach($pp as $y => $x){
            for($i=0; $i < strlen($x); $i++){
                $num = (int)$x[$i];
                if($num === 0){
                    $box->append($i, $y, new Number(null, false, false));
                }else{
                    $box->append($i, $y, new Number([$num], false, true));
                }
            }
        }
        return $box;
    }
}
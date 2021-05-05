<?php
declare(strict_types=1);

require_once dirname(__FILE__)."/../vendor/autoload.php";

/*
          Flash     Easy      Medium    Hard      Expert
000000000 072900306 380040000 050970802 500040000 006900800
000000000 059806721 560009020 070603450 004007360 004060290
000000000 681020054 020083000 040000300 620580400 300000000
000000000 538492600 010300400 100006070 800000020 040000706
000000000 200310040 070064000 020000900 007031000 700030000
000000000 107008003 050102000 005801000 950004803 060020085
000000000 810639570 092531704 008000000 000000086 200000600
000000000 000100409 807026951 060732080 000010047 009500000
000000000 005274030 100897060 910000206 000050000 005340000

*/

$problem = <<<EOL
000000000
050900320
000000006
800070461
300060007
900080000
003001004
060200730
000000000
EOL;

$box = \Hanhan1978\Sudoku\Box\ProblemParser::parse($problem);

//解けるところまでは理詰めで解く
$unique = new \Hanhan1978\Sudoku\Solver\Unique();
$orphan = new \Hanhan1978\Sudoku\Solver\Orphan();
$pair = new \Hanhan1978\Sudoku\Solver\Pair();

for($i=0; $i<20; $i++){
    if($box->solved()) break;
    $box = $unique->solve($box);
    $box = $pair->solve($box);
    $box = $orphan->solve($box);
}

if(!$box->solved()){
    //仮置
    $guess = new \Hanhan1978\Sudoku\Solver\Guess();
    $box = $guess->solve($box);
}

if($box->valid() && $box->solved()){
    echo "\n------SOLVED------\n";
}else{
    echo "\n------FAILED------\n";
}

$box->display();

class Kari{

    public function getKari(\Hanhan1978\Sudoku\Box\Box $box){

        /**
         * @var int $x
         * @var int $y
         * @var \Hanhan1978\Sudoku\Box\Number $number
         */
        while(list($x, $y, $number) = $box->next()) {
            if($number->decided() || $number->candidateCount() > 2) continue;

            if(empty($number->getCandidates())){
                return null;
            }
            $guessDigit = $number->getCandidates()[array_rand($number->getCandidates())];
            echo "kari for cand count => ".$number->candidateCount()."\n";
            return [$x, $y, $guessDigit];
        }
        return null;
    }
}


class History{

    public function __construct(private int $x, private int $y, private int $digit)
    {
    }
}
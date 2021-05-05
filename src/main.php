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
007860200
081030000
000007000
000980600
960000080
003000000
000008904
000170000
610400030
EOL;

$box = \Hanhan1978\Sudoku\Box\ProblemParser::parse($problem);

$history = [];

$counter = 0;
while(true){

    if($box->solved()) break;

    $history[] = $box;

    $unique = new \Hanhan1978\Sudoku\Solver\Unique();
    $orphan = new \Hanhan1978\Sudoku\Solver\Orphan();
    $pair = new \Hanhan1978\Sudoku\Solver\Pair();
    $box = $unique->solve($box);
    $box = $pair->solve($box);
    $box = $orphan->solve($box);

    if(++$counter > 15) break;
}

if($box->solved()){
    echo "\n---------SOLVED-------[Counter [{$counter}]---\n";
}else{
    echo "\n---------FAILED----------\n";
}

$box->display();

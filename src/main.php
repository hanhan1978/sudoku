<?php


$problem0 = <<<EOL
000000000
000000000
000000000
000000000
000000000
000000000
000000000
000000000
000000000
EOL;
$problem = <<<EOL
200905170
005000900
008000640
700046001
621000007
030020000
100030000
053008009
007002054
EOL;

$box = parse($problem);

$c = 0;
while(updated($box) || $c < 10){
    while(true){
        $box = updateCands($box);
        if(!updated($box)){
            break;
        }
    }
    $box=findUnique($box);
    $box=findUnique2($box);
    $c++;
}



//echo "--------------\n";
disp($box);

//echo "\033[31m$str \033[0m\n";

function parse(string $problem) :array{
    $box = [];
    $pp = preg_split("/\n/", $problem);
    foreach($pp as $y => $x){
        for($i=0; $i < strlen($x); $i++){
            $orig = (int)$x[$i] !== 0;
            $box[$y][$i] = new Number((int)$x[$i], [], false, $orig);
        }
    }
    return $box;
}

function disp(array $box){
    foreach($box as $y => $row){
        foreach($row as $x => $v){
            $space = " ";
            if($v->orig){
                $space = '.';
            }else if($v->num !== 0){
                $space = '*';
            }
            echo $space.$v->num;
        }
        echo "\n";
    }
}

function updated(array $box){
    foreach($box as $y => $row){
        foreach($row as $x => $v){
            if($v->update){
                return true;
            }
        }
    }
    return false;
}

class Number {
    public $cands = [];
    public $update;
    public $orig;

    public function __construct(public int $num, array $cands = [], $update = false, $orig = false){
        if($num === 0 && count($cands) === 0){
            $this->cands = [1,2,3,4,5,6,7,8,9];
        }else{
            $this->cands = $cands;
        }
        $this->update = $update;
        $this->orig = $orig;
    }

}


function updateCands(array $box, $debug = false){
    $nbox = [];
    foreach($box as $y => $row){
        foreach($row as $x => $v){
            if($v->num !== 0){
                if($v->orig){
                    $nbox[$y][$x] = $v; 
                }else{
                    $nbox[$y][$x] = new Number($v->num); 
                }
            }else{
                //$cands = $v->cands;
                $cands = [1,2,3,4,5,6,7,8,9];
                //x scan
                if($debug) echo "[y:{$y},x:{$x}]";
                for($tx=0; $tx<9; $tx++){
                    if($tx === $x) continue;
                    $tnum = ($box[$y][$tx])->num;
                    $cands = array_filter($cands, function($v) use ($tnum){ return $v !== $tnum;});
                }
                //y scan
                for($ty=0; $ty<9; $ty++){
                    if($ty === $y) continue;
                    $tnum = ($box[$ty][$x])->num;
                    $cands = array_filter($cands, function($v) use ($tnum){ return $v !== $tnum;});
                }
                //box scan
                $tx = floor($x / 3) * 3;
                $ty = floor($y / 3) * 3;
                for($i=0; $i<3; $i++){
                    for($j=0; $j<3; $j++){
                        $tnum = ($box[$i+$ty][$j+$tx])->num;
                        if($tnum === 0) continue;
                        $cands = array_filter($cands, function($v) use ($tnum){ return $v !== $tnum;});
                    }
                }
                if(count($cands) === 1) {
                    $nbox[$y][$x] = new Number(array_pop($cands), [], true); 
                }else{
                    $nbox[$y][$x] = new Number($v->num, $cands); 
                }
                if($debug){
                    echo " [cands:";
                    foreach($cands as $c){
                        echo $c;
                    }
                    echo "] ";
                }
                if($debug) echo "\n";
            }
           
        }
    }
    return $nbox;
}

function findUnique(array $box){
    foreach($box as $y => $row){
        foreach($row as $x => $v){
            if($v->num !== 0){
                $nbox[$y][$x] = new Number($v->num); 
                if($v->orig){
                    $nbox[$y][$x] = $v; 
                }else{
                    $nbox[$y][$x] = new Number($v->num); 
                }
                continue;
            }
            $debug = false;
            //if(($y >=3 && $y <=5) && ($x >=0 && $x <=2)) $debug =true;
            if($debug) echo " y:{$y}, x:{$x} ";
            $tx = (int)(floor($x / 3) * 3);
            $ty = (int)(floor($y / 3) * 3);
            $cands = $v->cands;
   //         echo " cands_count => ".count($cands). " ";
            for($i=0; $i<3; $i++){
                for($j=0; $j<3; $j++){
                    $tnum = ($box[$i+$ty][$j+$tx])->num;
                    if($tnum !== 0) {continue;} 
                    if(($i+$ty) === $y && ($j+$tx) === $x) {continue;}
                    $cands = array_diff($cands, ($box[$i+$ty][$j+$tx])->cands);
                }
            }
  //          echo " cands_count => ".count($cands). " ";
            if(count($cands) === 1){
                $nbox[$y][$x] = new Number(array_pop($cands), $v->cands, true); 
            }else{
                $nbox[$y][$x] = new Number($v->num, $v->cands); 
            }
            if($debug) echo "\n";
        }
    }
    return $nbox;
}

function findUnique2(array $box){
    $vbox = [];
    foreach($box as $y => $row){
        foreach($row as $x => $v){
            if($v->num !== 0){
                if($v->orig){
                    $nbox[$y][$x] = $v; 
                }else{
                    $nbox[$y][$x] = new Number($v->num); 
                }
                continue;
            }
            $debug = false;
            if($debug) echo " y:{$y}, x:{$x} ";
            $tx = (int)(floor($x / 3) * 3);
            $ty = (int)(floor($y / 3) * 3);
            $cands = $v->cands;
            if($debug)echo " cands_count => ".count($cands). " ";
            if(count($cands) === 2){
                if($debug) echo array_pop($cands) ." ". array_pop($cands);
                $vbox[$y][$x] = $v->cands;
                
                //$nbox[$y][$x] = new Number(array_pop($cands), $v->cands, true); 
            }
            $nbox[$y][$x] = new Number($v->num, $v->cands); 
            if($debug) echo "\n";
        }
    }
    $debug2 = true;
    foreach($vbox as $y => $row){
        foreach($row as $x => $v){
            if(fu2_have_x_friend($y, $x, $v, $vbox)){
                //fix y
                for($i=0; $i<9; $i++){
                    if( ($nbox[$y][$i])->num !== 0 ) continue;
                    $cands = array_diff(($nbox[$y][$i])->cands, $v);
                    if(count($cands) === 1) {
                        $nbox[$y][$i] = new Number(array_pop($cands), [], true);
                    }
                }
            }
            if(fu2_have_y_friend($y, $x, $v, $vbox)){
                //fix x
                for($i=0; $i<9; $i++){
                    if( ($nbox[$i][$x])->num !== 0 ) continue;
                    $cands = array_diff(($nbox[$i][$x])->cands, $v);
                    if(count($cands) === 1) {
                        $nbox[$i][$x] = new Number(array_pop($cands), [], true);
                    }
                }
            }
        }
    }
    return $nbox;
}

function fu2_have_y_friend(int $ty, int $tx, $fu2, $vbox){
    foreach($vbox as $y => $row){
        foreach($row as $x => $v){
            if($x !== $tx) continue;
            if($y === $ty) continue;
            if(count(array_diff($fu2, $v)) === 0){
                return true;
            }
        }
    }
    return false;
}

function fu2_have_x_friend(int $ty, int $tx, $fu2, $vbox){
    foreach($vbox as $y => $row){
        if($y !== $ty) continue;
        foreach($row as $x => $v){
            if($x === $tx) continue;
            if(count(array_diff($fu2, $v)) === 0){
                return true;
            }
        }
    }
    return false;
}

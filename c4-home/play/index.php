<?php
 //class ackmove will be the player
//http://cssrvlab01.utep.edu/classes/cs3360/leochoa2/c4-home
//class move will be the computer automated move

//need methods isWin and isDraw (implement logic here)
$PID =  $_Get["pid"];
$MOVE = $_Get["move"];
$url = dirname(dirname(__FILE__))."/writable/";
$return = new ackmove();

if (!$PID){
    $return->reason = "The PID is not valid";

} else if (!$MOVE || $MOVE > 6 || isColumnFull($MOVE)){
    $return -> reason = "The slot is not valid or full";
}
if($return->reason){// if there is a reason output it and exit
    echo json_encode(array("reason"=> $return -> reason));
    exit();
}
$file = file_get_contents(url.$PID.".txt");
$decoded_file = json_decode($file);
global $game_board;
$game_board = &$decoded_file->gameboard;

function isColumnFull($column){
    if(global $game_board[0][$column]){
        return true;
    }else {return false;}
}
function update_board($input){
    for( $i = 5; $i >=0;$i--){
        if (global $game_board[$i][$input]== 0){
            global $game_board[$i][$input]= 1;
            exit();
        }
    }
}
class ackmove{ //human user
    var $slot;
    var $userWin;
    var $row; //indices fo winning row. [x1,y1,x2..,xn]
    var $reason;

}
//NOTE: no computer move if human has game ending move

class move{ //computer logic


}

function isWin(){

    return(false);
}

function isDraw(){
    //only draw when board is full and nobody as won
    return (false);
}

while(1){
    //visit the url
    //if(ackmove::)
}

?>
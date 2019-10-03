<?php
 //class ackmove will be the player
//http://cssrvlab01.utep.edu/classes/cs3360/leochoa2/c4-home
//class move will be the computer automated move

//need methods isWin and isDraw (implement logic here)
$PID =  $_GET["pid"];
$MOVE = $_GET["move"];
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
$strategy = &$decoded_file->strategy;
$game_board = &$decoded_file->gameboard;
$response = &$decoded_file->response;
check();
if($strategy == "Random"){
    $aimove = random_move();
}else {
    $aimove = smart_move();
}
$ai_return = new move();

update_board($aimove);
echo json_encode(array("response"=> $response,"ack_move" => array("slot"=> $MOVE,
    "isWin"=> $return->userWin, "isDraw"=> $return->userDraw,
    "row"=> "[]"), "move"=> array("slot"=> $aimove, "isWin"=>$ai_return->isWin,
    "isDraw"=> $ai_return->isDraw, "row"=> "[]")));

function smart_move(){
    $smart_move = rand(0,6);
    return $smart_move;
}
function random_move(){
    $rand_move = rand(0,6);
    while(isColumnFull($rand_move)){
        $rand_move = rand(0,6);
    }
    return $rand_move;
}
function check($return)
{
    global $MOVE;
    update_board($MOVE);
    $return->userWin = isWin();
    $return->userDraw = isDraw();
    $return->slot = $MOVE;
}
function isWin(){
    global $game_board;
    return false;
}
function isDraw(){
    for($i = 0; $i <=6; $i++){
        if(isColumnFull($i)){
            return false;
        }
    }
    return true;
}
function isColumnFull($column){
    global $game_board;
    if($game_board[0][$column]){
        return true;
    }else {return false;}
}
function update_board($input){
    global $game_board;
    for( $i = 5; $i >=0;$i--){
        if ($game_board[$i][$input]== 0){
            $game_board[$i][$input]= 1;
            exit();
        }
    }
}
class ackmove{ //human user
    var $slot;
    var $userWin;
    var $row; //indices fo winning row. [x1,y1,x2..,xn]
    var $reason;
    var $isWin;
    var $userDraw;

}
//NOTE: no computer move if human has game ending move

class move{ //computer logic
    var $isDraw;
    var $isWin;
    var $slot;
    var $row;

}


?>
<?php
 //class ackmove will be the player
//http://cssrvlab01.utep.edu/classes/cs3360/leochoa2/c4-home
//class move will be the computer automated move

//need methods isWin and isDraw (implement logic here)
$PID =  $_GET["pid"];
$MOVE = $_GET["move"];
$url = dirname(dirname(__FILE__))."/writable/";
$return = new ackmove(); // possibly reason for error?

//REASONS FOR GAME TO EXIT
if (!$PID){
    $return->reason = "The PID is not valid";
    $return -> response = false;
    echo json_encode($return);
    exit();
}else if ($MOVE > 6){   //must be in range 0-6
    $return -> reason = "Must select valid slot.";
    $return -> response = false;
    echo json_encode($return);
    exit();
}else if(isColumnFull($MOVE)){  //full column
    $return -> reason = "Column full, try another slot.";
    $return -> response = false;
    echo json_encode($return);
    exit();
}else if($MOVE == null){   //move is null
    $return -> response = false;
    $return -> reason = "Please enter a move.";
    echo json_encode($return);
    exit();
}
$return -> slot = $MOVE;
$file = file_get_contents($url.$PID.".txt");
$decoded_file = json_decode($file);
$strategy = &$decoded_file->strategy;
$game_board = &$decoded_file->board;
$response = true;
//echo json_encode(array($strategy));
//echo json_encode(array($game_board));
//echo json_encode(array($response));
check($return);
if($strategy == "Random"){
    $aimove = random_move();
}else {
    $aimove = smart_move();
}
$ai_return = new ackmove();
$ai_return -> slot = $aimove;
check($ai_return);




$saved_board = $url.$PID.".txt";
$opened_board = fopen($saved_board,"w");//creates new file
$board_string = json_encode(array("pid"=>$PID, 'strategy'=> $strategy, 'board'=> $game_board));
fwrite($opened_board,$board_string);
fclose($opened_board);



echo json_encode(array("response"=> $response,"ack_move" => array("slot"=> $MOVE,
    "isWin"=> $return->userWin, "isDraw"=> $return->userDraw,
    "row"=> array(), "move"=> array("slot"=> $aimove, "isWin"=>$ai_return->userWin,
    "isDraw"=> $ai_return->userDraw, "row"=> array()))));

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
    update_board($return->slot);
    $return->userWin = isWin();
    $return->userDraw = isDraw();

}
function isWin(){
    global $game_board;
    return false;
}
function isDraw(){
    for($i = 0; $i <=6; $i++){
        if(!isColumnFull($i)){ //added !isWin
            return false;
        }
    }
    return true;
}
function isColumnFull($column){ //works
    global $game_board;
    if($game_board[0][$column]){
        return true;
    }else{
        return false;
    }
}
function update_board($input){
    global $game_board;
    for($i = 5; $i >=0;$i--){
        if ($game_board[$i][$input]== 0){
            $game_board[$i][$input]= 1;
            $i = -1;
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
    var $response;
}
//NOTE: no computer move if human has game ending move


class gameInfo{
    public $WIDTH;
    public $HEIGHT;
    public $STRATS;
    function __construct($WIDTH,$HEIGHT,$STRAT)
    {
        $this->WIDTH = $WIDTH;
        $this->HEIGHT = $HEIGHT;
        $this->STRATS = $STRAT;
    }
}
?>
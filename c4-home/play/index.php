<?php
//LUIS OCHOA
//ALEX VASQUEZ

//http://cssrvlab01.utep.edu/classes/cs3360/leochoa2/c4-home
//http://cssrvlab01.utep.edu/classes/cs3360/avasquez31/c4-home
//Last Check: failed 2 tests, smartMove not finished

$PID =  $_GET["pid"];
$MOVE = $_GET["move"];

global $color;
global $win_indices;
global $smart_return; //will place to drop

$color= 1;
$win_indices = array();
$url = dirname(dirname(__FILE__))."/writable/";

$return = new ackmove(); // possibly reason for error?

//REASONS FOR GAME TO EXIT
if (!$PID){
    $return->reason = "The PID is not valid";
    $return -> response = false;
    echo json_encode($return);
    exit();
}else if ($MOVE < 0 or $MOVE >=6){   //must be in range 0-6
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

$return -> slot = (int)$MOVE; //where we drop (if valid)

$file = file_get_contents($url.$PID.".txt");
$decoded_file = json_decode($file);
$strategy = &$decoded_file->strategy;
$game_board = &$decoded_file->board;
$response = true;

check($return); //check if slot is valid and if it results in win/draw

if($strategy == "Random"){ //set strategy
    $aimove = random_move();
}else {
    $aimove = smart_move();
}

$ai_return = new ackmove(); //AI instance
$ai_return -> slot = $aimove; //set move
check($ai_return); //update board, check conditions

$saved_board = $url.$PID.".txt";
$opened_board = fopen($saved_board,"w");//creates new file
$board_string = json_encode(array("pid"=>$PID, 'strategy'=> $strategy, 'board'=> $game_board));
fwrite($opened_board,$board_string);
fclose($opened_board);


echo json_encode(array("response"=> $response,

    "ack_move" => array("slot"=> $return->slot,
    "isWin"=> $return->userWin, "isDraw"=> $return->userDraw,

    "row"=> array()), "move"=> array("slot"=> $aimove, "isWin"=>$ai_return->userWin,
    "isDraw"=> $ai_return->userDraw, "row"=> $win_indices)));

function smart_move(){
    /*
    global $smart_return;
    $aiMove = rand(0,6);

    if (vertical_three_in_row() && !isColumnFull($aiMove)) {
        return $smart_return;
    } else{
        return random_move();
    }
    */
    return random_move();
    //$smart_move = vertical_three_in_row();
    //return $smart_move;
}

function random_move(){ //assigns random number between 0-6
    $rand_move = rand(0,6);
    while(isColumnFull($rand_move)){ //if full, choose different number
        $rand_move = rand(0,6);
    }
    return $rand_move;
}

function check($return){ //update board and check for Win/Draw
    update_board($return->slot);
    $return->userWin = isWin();
    $return->userDraw = isDraw();

}

function horizontal_win(){
    global $game_board;
    global $win_indices;
    for ($i = 0; $i <= count($game_board)-1; $i++) {
        for ($j = 0; $j <= count($game_board[$i]) - 3; $j++) {
            if ($game_board[$i][$j] != '0' && $game_board[$i][$j] == $game_board[$i][$j + 1]
                && $game_board[$i][$j] == $game_board[$i][$j + 2] && $game_board[$i][$j] == $game_board[$i][$j + 3]){
                $win_indices = array($i,$j+1,$i,$j+2,$i,$j+3, $i,$j+4);
                return true;
            }
        }
    }
    return false;
}

function vertical_win(){
    global $game_board;
    global $win_indices;
    for ($i = 0; $i <= count($game_board)-1; $i++) {
        for ($j = 0; $j <= count($game_board[$i]) - 3; $j++) {
            if ($game_board[$i][$j] != '0' && $game_board[$i][$j] == $game_board[$i+1][$j]
                && $game_board[$i][$j] == $game_board[$i+2][$j] && $game_board[$i][$j] == $game_board[$i+3][$j]){
                $win_indices = array($i,$j,$i+1, $j, $i+2, $j, $i+3, $j);
                return true;
            }
        }
    }
    return false;
}

function diagonal_win(){
    global $game_board;
    global $win_indices;
    for ($i = 0; $i <= count($game_board)-1; $i++) {
        for ($j = 0; $j <= count($game_board[$i]) - 3; $j++) {
            if ($game_board[$i][$j] != '0' && $game_board[$i][$j] == $game_board[$i+1][$j+1]
                && $game_board[$i][$j] == $game_board[$i+2][$j+2] && $game_board[$i][$j] == $game_board[$i+3][$j+3]) {
                $win_indices = array($i,$j,$i+1, $j+1, $i+2,$j+2, $i+3, $j+3);
                return true;
            }
        }
    }
    return false;
}
function vertical_three_in_row(){
    global $game_board;
    global $smart_return;
    for ($i = 0; $i <= count($game_board)-1; $i++) {
        for ($j = 0; $j <= count($game_board[$i]) - 3; $j++) {
            if ($game_board[$i][$j] != '0' && $game_board[$i][$j] == $game_board[$i+1][$j]
                && $game_board[$i][$j] == $game_board[$i+2][$j]){
                $smart_return = $j;
                return true;
            }
        }
    }
    return false;
}

function isWin(){ //check for all possible wins
    if(horizontal_win() or vertical_win() or diagonal_win())
        return true;
    else
        return false;
}

function isDraw(){ //when board full, and not win
    for($i = 0; $i <=6; $i++){
        if(!isColumnFull($i)){
            return false;
        }
    }
    return true;
}

function isColumnFull($column){ //return false if column not full
    global $game_board;
    if($game_board[0][$column]){
        return true;
    }else{
        return false;
    }
}
function update_board($input){
    global $game_board;
    global $color; //assign color to check for possible win
    for($i = 5; $i >=0;$i--){
        if ($game_board[$i][$input]== 0){
            $game_board[$i][$input]= $color;
            $i = -1;
        }
    }
    $color++;
    if($color >2){
        $color = 1;
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
?>
<?php

//create new game to play against computer strategy
//need response
//pid <-- ???????

//pid is  unique play identifier generated by web service

//  Upon an error, the web service will notify the client by providing an
//   an appropriate error response like:
//
//     {"response": false, "reason": "Strategy not specified"}
//     {"response": false, "reason": "Unknown strategy"}

//uniqid() -> generate identifier based on current time in microsecond

//Need strategy desing pattern to define STRATEGY CLASS
class Game{
    var $response;
    var $pid;
    var $reason;
    var $gameBoard;
}

$url =  dirname(dirname(__FILE__))."/writable/";
define('Strategy',$_GET['strategy']);

$currentGame = new Game(); //instance

if(Strategy == "Random" || Strategy == "Smart"){
    $currentGame->response = true;
    $currentGame->pid = uniqid();
    echo json_encode( array("response"=> $currentGame->response, "pid"=> $currentGame->pid)));
    $currentGame->gameBoard = $emptyBoard;

    
}else
    $currentGame->response = false;
    $currentGame->reason = "No such strategy";
echo json_encode(array("response"=> $currentGame->response, "reason"=> $currentGame->reason));
if($currentGame -> response == true){
    $saved_board = $url.$currentGame->pid."txt";
    $opened_board = fopen($saved_board,"w");
    $board_string = json_encode(array("pid"=>$currentGame->pid, 'strategy'=> Strategy, 'board'=> $currentGame->gameBoard));
    fwrite($opened_board,$board_string);
    fclose($opened_board);
}
$emptyBoard= array(array(0,0,0,0,0,0,0,),array(0,0,0,0,0,0,0,),
    array(0,0,0,0,0,0,0,),array(0,0,0,0,0,0,0,),
    array(0,0,0,0,0,0,0,),array(0,0,0,0,0,0,0,),
    array(0,0,0,0,0,0,0,));


//if($currentGame->response == false){
    //echo json_encode(array("response"=> $currentGame->response, "reason"=> $currentGame->reason));
//}else
    //echo json_encode(array("response"=> $currentGame->response, "pid"=> $currentGame->pid));



?>

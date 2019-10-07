<?php

//ALEX VASQUEZ
//LUIS OCHOA

class Game{
    var $response; //from jar
    var $pid;
    var $reason;
    var $gameBoard; //set to empty if new game
}

$url =  dirname(dirname(__FILE__))."/writable/";
$strategy = $_GET["strategy"];

$currentGame = new Game(); //instance
$strategies = array("Smart", "Random");
$emptyBoard= array( //new game board
    array(0,0,0,0,0,0,0,),
    array(0,0,0,0,0,0,0,),
    array(0,0,0,0,0,0,0,),
    array(0,0,0,0,0,0,0,),
    array(0,0,0,0,0,0,0,),
    array(0,0,0,0,0,0,0,));

if(in_array($strategy,$strategies)){ //if Smart or Random
    $currentGame->response = true;
    $currentGame->pid = uniqid();
    echo json_encode( array("response"=> $currentGame->response, "pid"=> $currentGame->pid));


}else{ //User strategy not available
    $currentGame->response = false;
    $currentGame->reason = "No such strategy";
    echo json_encode(array("response" => $currentGame->response, "reason" => $currentGame->reason));
}
if($currentGame -> response == true){ //set a new game
    $saved_board = $url.$currentGame->pid.".txt";
    $opened_board = fopen($saved_board,"w");//creates new file
    $currentGame->gameBoard = $emptyBoard; //set a new board
    $board_string = json_encode(array("pid"=>$currentGame->pid, 'strategy'=> $strategy, 'board'=> $currentGame->gameBoard));
    fwrite($opened_board,$board_string);
    fclose($opened_board);
}


?>

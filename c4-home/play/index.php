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

} else if (!$MOVE || $MOVE > 6){
    $return -> reason = "The slot is not valid"
}
if($return->reason){// if there is a reason output it and exit
    echo json_encode(array("reason"=> $return -> reason));
    exit();
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
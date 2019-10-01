<?php

 class Info{

    var $width; //board width
    var $height; //board height
    var $strategies; //strategy

     //TODO: figure out logic for strategy

    public function _construct($boardWidth, $boardHeight, $gameStrat){
        $this -> width = $boardWidth;
        $this -> height = $boardHeight;
        $this -> strategies = $gameStrat;
    }
}

$gameBoard = new Info();
$gameBoard ->_construct(6,7,["Smart", "Random"]);

echo json_encode($gameBoard);


?>

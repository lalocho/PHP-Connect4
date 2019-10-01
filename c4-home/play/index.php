<?php

//going to need width
//height
//strategy (think it will come from play)

//Make a move by dropping a disc in the specified column, x, to play
//   the specified game, p. Example: .../play/?pid=57cdc4815e1e5&move=3.
//
//   A normal response will be a JSON string like:
//
//     {"response": true,
//      "ack_move": {
//        "slot": 3,
//        "isWin": false,   // winning move?
//	"isDraw": false,  // draw?
//	"row": []},       // winning row if isWin is true
//      "move": {
//        "slot": 4,
//        "isWin": false,
//        "isDraw": false,
//        "row": []}}
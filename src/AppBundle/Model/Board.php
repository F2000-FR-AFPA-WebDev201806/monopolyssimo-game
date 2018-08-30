<?php

namespace AppBundle\Model;

use AppBundle\Model\Box;

class Board {

    const BOARD = array(
        0 => Array('index' => 20, 'valeur' => 100),
        1 => Array('index' => 21, 'valeur' => 0),
        2 => Array('index' => 22, 'valeur' => 50),
        3 => Array('index' => 23, 'valeur' => 100),
        4 => Array('index' => 24, 'valeur' => 0),
        5 => Array('index' => 25, 'valeur' => 100),
        6 => Array('index' => 26, 'valeur' => 0),
        7 => Array('index' => 27, 'valeur' => 5),
        8 => Array('index' => 28, 'valeur' => 1),
        9 => Array('index' => 29, 'valeur' => 100),
        10 => Array('index' => 30, 'valeur' => 5),
        11 => Array('index' => 19, 'valeur' => 50),
        12 => Array('index' => 31, 'valeur' => 100),
        13 => Array('index' => 18, 'valeur' => 0),
        14 => Array('index' => 32, 'valeur' => 0),
        15 => Array('index' => 17, 'valeur' => 1),
        16 => Array('index' => 33, 'valeur' => 0),
        17 => Array('index' => 16, 'valeur' => 100),
        18 => Array('index' => 34, 'valeur' => 50),
        19 => Array('index' => 15, 'valeur' => 100),
        20 => Array('index' => 35, 'valeur' => 0),
        21 => Array('index' => 14, 'valeur' => 50),
        22 => Array('index' => 36, 'valeur' => 50),
        23 => Array('index' => 13, 'valeur' => 5),
        24 => Array('index' => 37, 'valeur' => 100),
        25 => Array('index' => 12, 'valeur' => 100),
        26 => Array('index' => 38, 'valeur' => 0),
        27 => Array('index' => 11, 'valeur' => 0),
        28 => Array('index' => 39, 'valeur' => 1),
        29 => Array('index' => 10, 'valeur' => 0),
        30 => Array('index' => 9, 'valeur' => 100),
        31 => Array('index' => 8, 'valeur' => 100),
        32 => Array('index' => 7, 'valeur' => 5),
        33 => Array('index' => 6, 'valeur' => 5),
        34 => Array('index' => 5, 'valeur' => 5),
        35 => Array('index' => 4, 'valeur' => 100),
        36 => Array('index' => 3, 'valeur' => 1),
        37 => Array('index' => 2, 'valeur' => 50),
        38 => Array('index' => 1, 'valeur' => 1),
        39 => Array('index' => 33, 'valeur' => 50),
        40 => Array('index' => 0, 'valeur' => 50),
    );

    public $grid = [];

    public function __construct($liste_players) {

        $grid[0] = new Box(20, 100);
        $grid[1] = new Box(21, 0);
        $grid[2] = new Box(22, 50);
        $grid[3] = new Box(23, 100);
        $grid[4] = new Box(24, 0);
        $grid[5] = new Box(25, 100);
        $grid[6] = new Box(26, 0);
        $grid[7] = new Box(27, 5);
        $grid[8] = new Box(28, 1);
        $grid[9] = new Box(29, 100);
        $grid[10] = new Box(30, 5);
        $grid[11] = new Box(19, 50);
        $grid[12] = new Box(31, 100);
        $grid[13] = new Box(18, 0);
        $grid[14] = new Box(32, 0);
        $grid[15] = new Box(17, 1);
        $grid[16] = new Box(33, 0);
        $grid[17] = new Box(16, 100);
        $grid[18] = new Box(34, 50);
        $grid[19] = new Box(15, 100);
        $grid[20] = new Box(35, 0);
        $grid[21] = new Box(14, 50);
        $grid[22] = new Box(36, 50);
        $grid[23] = new Box(13, 5);
        $grid[24] = new Box(37, 100);
        $grid[25] = new Box(12, 100);
        $grid[26] = new Box(38, 0);
        $grid[27] = new Box(11, 0);
        $grid[28] = new Box(39, 1);
        $grid[29] = new Box(10, 0);
        $grid[30] = new Box(9, 100);
        $grid[31] = new Box(8, 100);
        $grid[32] = new Box(7, 5);
        $grid[33] = new Box(6, 5);
        $grid[34] = new Box(5, 5);
        $grid[35] = new Box(4, 100);
        $grid[36] = new Box(3, 1);
        $grid[37] = new Box(2, 50);
        $grid[38] = new Box(1, 1);
        $grid[39] = new Box(33, 50);
        $grid[40] = new Box(0, 50);
        foreach ($liste_players as $player) {
            $grid[40] . $liste_players[] = $player;
        }
    }

}

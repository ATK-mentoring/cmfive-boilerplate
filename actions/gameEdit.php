<?php

function gameEdit_GET(Web $w) {

    //check if we are editing or creating a new game
    $p = $w->pathMatch("id");
    $game = [];
    if (empty($p['id'])) {
        $game = new LeaderboardGame($w);
    } else {
        $game = LeaderboardService::getInstance($w)->getGameForId($p['id']);
    }

    $form = [
        'Game Details' => [
            [
                ['Name', 'text', 'name', $game->name]
            ]
        ]
    ];
    $action = '/leaderboard/gameEdit';
    if (!empty($p['id'])) {
        $action .= '/' . $p['id'];
    }
    $w->out(Html::multicolForm($form, $action));


}

function gameEdit_POST(Web $w) {
    //check if we are editing or creating a new game
    $p = $w->pathMatch("id");
    $game = [];
    if (empty($p['id'])) {
        $game = new LeaderboardGame($w);
    } else {
        $game = LeaderboardService::getInstance($w)->getGameForId($p['id']);
    }

    $game->fill($_POST);
    //hash('sha256', 'The quick brown fox jumped over the lazy dog.');
    // gerate hash for the game
    if (empty($game->id_hash)) {
        $date_string = date_format( new DateTime(), 'ddmmYYYYHHii');
        $game->id_hash = hash('sha256', $game->name . $date_string);
    }

    $game->user_id = AuthService::getInstance($w)->user()->id;
    
    $game->insertOrUpdate();
    $w->msg('game saved. Key = ' . $game->id_hash, '/leaderboard-public/gameView/' . $game->id_hash);

}

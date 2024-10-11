<?php

function addScore_ALL(Web $w) {
    //check if we are editing or creating a new game
    $p = $w->pathMatch("hash", "player_name", "player_score");
    
    if (empty($p['hash']) || empty($p['player_name']) || empty($p['player_score'])) {
        //return null;
        $w->error('no key provided', '/leaderboard');
    } 

    //sanitize inputs
    $p_hash = htmlspecialchars($p['hash']);
    $p_name = htmlspecialchars($p['player_name']);
    $p_score = htmlspecialchars($p['player_score']);

    $game = LeaderboardService::getInstance($w)->getGameForHash($p['hash']);
    if (empty($game)) {
        $w->error('game not found for key');
    }

    //check if score can be added
    LeaderboardService::getInstance($w)->addNewScore($game->id, $p_name, $p_score);



}
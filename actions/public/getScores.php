<?php

function getScores_ALL(Web $w) {
    $p = $w->pathMatch("hash");
    
    if (empty($p['hash'])) {
        //return null;
        $w->error('no key provided', '/leaderboard');
    } 

    $game = LeaderboardService::getInstance($w)->getGameForHash($p['hash']);
    if (empty($game)) {
        $w->error('game not found for key');
    }

    $scores = LeaderboardService::getInstance($w)->getScoresForGameId($game->id);
    
    usort($scores, function($a, $b){
        return (intVal($a->player_score) < intVal($b->player_score));
    });

    $results = [];
    foreach($scores as $score) {
        $results[] = [
            "name" => $score->player_name,
            "score" => $score->player_score
        ];
    }
    $w->setLayout(null);
    $w->out(json_encode($results));
}
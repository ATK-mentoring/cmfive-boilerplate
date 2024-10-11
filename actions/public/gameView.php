<?php

function gameView_ALL(Web $w) {
    //check if we are editing or creating a new game
    $p = $w->pathMatch("hash");
    
    if (empty($p['hash'])) {
        $w->error('no key provided', '/leaderboard');
    } 
    $game = LeaderboardService::getInstance($w)->getGameForHash($p['hash']);
    
    $w->ctx('title', $game->name);
    $w->ctx('id_hash', $game->id_hash);

    $table_headers = ['Player Name', 'Score', 'Date Time'];
    $scores = LeaderboardService::getInstance($w)->getScoresForGameId($game->id);
    $table = [];
    if (!empty($scores)) {
        foreach($scores as $score) {
            $row = [];
            $row[] = $score->player_name;
            $row[] = $score->player_score;
            $row[] = $score->dt_modified;
            $table[] = $row;
        }
        $w->ctx('scores_table', Html::table($table, null, "tablesorter", $table_headers));
    }
    //var_dump($scores);
}
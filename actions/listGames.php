<?php

function listGames_ALL(Web $w) {

    $user = AuthService::getInstance($w)->user();

    if (empty($user)) {
        $w->error("no User detected", "");
    }

    $games = [];
    if ($user->hasRole('admin')) {
        $games = LeaderboardService::getInstance($w)->getAllGames();
    } else {
        $games = LeaderboardService::getInstance($w)->getGamesForUserId($user->id);
    }

    // var_dump($games);

    $table_headers = ['Name', 'Key', 'Actions'];
    $table = [];
    if (!empty($games)) {
        foreach($games as $game) {
            $row = [];
            $row[] = $game->name;
            $row[] = $game->id_hash;
            $row[] = '';
            $table[] = $row;
        }
        $w->ctx('games_table', Html::table($table, null, 'tablesorter', $table_headers));
    }


}
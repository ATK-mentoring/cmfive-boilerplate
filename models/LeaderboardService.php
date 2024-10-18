<?php

/**
 * This class provides various lookup functions for Form object
 */
class LeaderboardService extends DbService {

    function getAllGames() {
        return $this->getObjects('LeaderboardGame');
    }

    function getGamesForUserId($user_id) {
        return $this->getObjects('LeaderboardGame', ['user_id' => $user_id]);
    }

    function getGameForId ($game_id) {
        return $this->getObject('LeaderboardGame', $game_id);
    }

    function getGameForHash ($id_hash) {
        return $this->getObject('LeaderboardGame', ['id_hash' => $id_hash]);
    }

    function getScoresForGameId($game_id) {
        return $this->getObjects('LeaderboardScore', ['game_id' => $game_id, 'is_deleted' => 0]);
    }

   

    function getScoreForPlayerNameandGameId($player_name, $game_id) {
        return $this->getObject('LeaderboardScore', ['game_id' => $game_id, 'player_name' => $player_name, 'is_deleted' => 0]);
    }

    function addNewScore($game_id, $player_name, $player_score) {
        //check how many saved scores in the game
        $is_top_ten = false;
        $can_save = false;
        $lowest_score = '';
        $scores = $this->getScoresForGameId($game_id);
        if (empty($scores) || Count($scores) < 10) {
            $is_top_ten = true;
        }
        

        if (!$is_top_ten) {
            // check lowest score
            $lowest_score = '99999999999999999';
            foreach($scores as $score) {
                if ($score->player_score < $lowest_score) {
                    $lowest_score = $score->player_score;
                }
            }
            if (!empty($lowest_score) && $player_score > $lowest_score->player_score) {
                //$lowest_score->delete();
                $is_top_ten = true;
            }
        }
       
        //now check name
        if ($is_top_ten) {
            $player_previous_score = $this->getScoreForPlayerNameandGameId($player_name, $game_id);
            if (!empty($player_previous_score)) {
                if ($player_score > $player_previous_score->player_score) {
                    // replace row
                    $player_previous_score->player_score = $player_score;
                    $player_previous_score->update();
                }
            } else {
                if (!empty($lowest_score)) {
                    $lowest_score->delete();
                }
                $new_score = new LeaderboardScore($this->w);
                $new_score->game_id = $game_id;
                $new_score->player_name = $player_name;
                $new_score->player_score = $player_score;
                $new_score->insertOrUpdate();
            }
        }
    }


    /**
     * Submenu navigation for leaderboard
     *
     * @param  Web    $w
     * @param  string $title
     * @param  array $prenav
     * @return array
     */
    public function navigation(Web $w, $title = null, $prenav = null)
    {
        if ($title) {
            $w->ctx("title", $title);
        }

        $nav = $prenav ? $prenav : [];
        if (AuthService::getInstance($w)->loggedIn()) {
            $w->menuLink("leaderboard/gameEdit", "New Game", $nav);
            $w->menuLink("leaderboard/listGames", "List Games", $nav);
            // $w->menuLink("form", "Forms", $nav);
        }

        return $nav;
    }

    // public function navList(): array
    // {
    //     return [
    //         new MenuLinkStruct("New Game", "leaderboard/gameEdit")
    //     ];
    // }
}
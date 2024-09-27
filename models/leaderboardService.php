<?php

/**
 * This class provides various lookup functions for Form object
 */
class leaderboardService extends DbService {





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
            // $w->menuLink("form-application", "Applications", $nav);
            // $w->menuLink("form", "Forms", $nav);
        }

        return $nav;
    }
}
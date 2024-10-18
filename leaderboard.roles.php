<?php
function role_leaderboard_user_allowed(Web $w,$path) {
    return $w->checkUrl($path, "leaderboard", "*", "*");
}

<?php

class LeaderboardInitialMigration extends CmfiveMigration
{
    public function up()
    {
        // UP
        $column = parent::Column();
        $column->setName('id')
                ->setType('biginteger')
                ->setIdentity(true);


        if (!$this->hasTable('leaderboard_game')) {
            $this->table('leaderboard_game', [
                'id' => false,
                'primary_key' => 'id'
            ])->addColumn($column)
            ->addStringColumn('name')
            ->addStringColumn('id_hash')
            ->addIdColumn('user_id')
            ->addCmfiveParameters()
            ->create();
        }

        if (!$this->hasTable('leaderboard_score')) {
            $this->table('leaderboard_score', [
                'id' => false,
                'primary_key' => 'id'
            ])->addColumn($column)
            ->addIdColumn('game_id')
            ->addStringColumn('player_name')
            ->addStringColumn('player_score')
            ->addCmfiveParameters()
            ->create();
        }

    }

    public function down()
    {
        // DOWN
        $this->hasTable('leaderboard_game') ? $this->dropTable('leaderboard_game') : null;
        $this->hasTable('leaderboard_score') ? $this->dropTable('leaderboard_score') : null;
    }

    public function preText()
    {
        return null;
    }

    public function postText()
    {
        return null;
    }

    public function description()
    {
        return null;
    }
}

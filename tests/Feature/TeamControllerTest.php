<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;

class TeamControllerTest extends PlayerControllerBaseTest
{
    public function test_team()
    {
        Artisan::call('migrate', ['--seed' => true]);

        // send post request with player data
        $response1 = $this->postJson(self::REQ_URI, self::PLAYERS[0]);
        $response2 = $this->postJson(self::REQ_URI, self::PLAYERS[1]);

        $requirements =
            [
                [
                    'position' => "defender",
                    'mainSkill' => "speed",
                    'numberOfPlayers' => 1
                ]
            ];


        $res = $this->postJson(self::REQ_TEAM_URI, $requirements);
         // assert that the player was created successfully
         $res->assertStatus(200)
         ->assertJson([
             'success' => true,
             "message" => "Team successfully obtained"
         ]);

        $this->assertNotNull($res);
    }
}

<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;

class PlayerControllerUpdateTest extends PlayerControllerBaseTest
{
    public function test_update()
    {
        Artisan::call('migrate', ['--seed' => true]);

        // send post request with player data
        $response = $this->postJson('/api/player', self::PLAYERS[0]);

        $data_update = [
            "name" => "test-up",
            "position" => "defender",
            "playerSkills" => [
                0 => [
                    "skill" => "attack",
                    "value" => 40
                ],
                1 => [
                    "skill" => "speed",
                    "value" => 80
                ]
            ]
        ];

        $response = $this->putJson(self::REQ_URI . '1', $data_update);
        // assert that the player was created successfully
        $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            "message" => "Player successfully updated"
        ]);

        $this->assertNotNull($response);
    }
}

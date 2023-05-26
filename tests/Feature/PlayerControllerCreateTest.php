<?php


// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;

class PlayerControllerCreateTest extends PlayerControllerBaseTest
{
    public function test_create()
    {
        Artisan::call('migrate', ['--seed' => true]);

        // send post request with player data
        $response = $this->postJson(self::REQ_URI, self::PLAYERS[0]);
        // assert that the player was created successfully
        $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            "message" => "Player successfully created"
        ]);

        $this->assertNotNull($response);
    }
}

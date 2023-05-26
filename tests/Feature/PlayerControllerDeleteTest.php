<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

use Illuminate\Support\Facades\Artisan;

class PlayerControllerDeleteTest extends PlayerControllerBaseTest
{

    public function test_delete()
    {
        Artisan::call('migrate', ['--seed' => true]);

        // send post request with player data
        $response = $this->postJson('/api/player', self::PLAYERS[0]);

        $res = $this->delete(self::REQ_URI . '1',[], [
            'authorization' => 'Bearer SkFabTZibXE1aE14ckpQUUxHc2dnQ2RzdlFRTTM2NFE2cGI4d3RQNjZmdEFITmdBQkE='
        ]);
        $res->assertStatus(200)
        ->assertJson([
            'success' => true,
            "message" => "Player correctly removed"
        ]);

        $this->assertNotNull($res);
    }
}

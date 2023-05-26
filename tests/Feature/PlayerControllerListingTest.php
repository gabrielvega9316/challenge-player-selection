<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;


use Illuminate\Support\Facades\Artisan;


class PlayerControllerListingTest extends PlayerControllerBaseTest
{
    public function test_index()
    {
        Artisan::call('migrate', ['--seed' => true]);

        // send post request with player data
        $response1 = $this->postJson(self::REQ_URI, self::PLAYERS[0]);
        $response2 = $this->postJson(self::REQ_URI, self::PLAYERS[1]);

        // Prueba la ruta y la respuesta HTTP
        $response = $this->get(self::REQ_URI);
        $response->assertStatus(200)
        ->assertJson([
            'success' => true,
            "message" => "Players successfully obtained",
            'players' => [
                [
                    'id' => $response1['player']['id'],
                    'name' => $response1['player']['name'],
                    'position' => $response1['player']['position'],
                    'skills' => [
                        [
                            'id' => $response1['player']['skills'][0]['id'],
                            'skill' => $response1['player']['skills'][0]['skill'],
                            'value' => $response1['player']['skills'][0]['value'],
                            'player_id' => $response1['player']['skills'][0]['player_id']
                        ],
                        [
                            'id' => $response1['player']['skills'][1]['id'],
                            'skill' => $response1['player']['skills'][1]['skill'],
                            'value' => $response1['player']['skills'][1]['value'],
                            'player_id' => $response1['player']['skills'][1]['player_id']
                        ]
                    ]
                ],
                [
                    'id' => $response2['player']['id'],
                    'name' => $response2['player']['name'],
                    'position' => $response2['player']['position'],
                    'skills' => [
                        [
                            'id' => $response2['player']['skills'][0]['id'],
                            'skill' => $response2['player']['skills'][0]['skill'],
                            'value' => $response2['player']['skills'][0]['value'],
                            'player_id' => $response2['player']['skills'][0]['player_id']
                        ],
                        [
                            'id' => $response2['player']['skills'][1]['id'],
                            'skill' => $response2['player']['skills'][1]['skill'],
                            'value' => $response2['player']['skills'][1]['value'],
                            'player_id' => $response2['player']['skills'][1]['player_id']
                        ]
                    ]
                ],
            ],
        ]);
    }
}


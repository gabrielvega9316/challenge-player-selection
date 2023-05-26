<?php

// /////////////////////////////////////////////////////////////////////////////
// TESTING AREA
// THIS IS AN AREA WHERE YOU CAN TEST YOUR WORK AND WRITE YOUR TESTS
// /////////////////////////////////////////////////////////////////////////////

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

abstract class PlayerControllerBaseTest extends TestCase
{
    use RefreshDatabase;

    final const REQ_URI = '/api/player/';
    final const REQ_TEAM_URI = '/api/team/process';
    final const PLAYERS = [
        [
            "name" => "test1",
            "position" => "midfielder",
            "playerSkills" => [
                0 => [
                    "skill" => "stamina",
                    "value" => 75
                ],
                1 => [
                    "skill" => "speed",
                    "value" => 80
                ]
            ]
        ],
        [
            "name" => "test2",
            "position" => "defender",
            "playerSkills" => [
                0 => [
                    "skill" => "attack",
                    "value" => 60
                ],
                1 => [
                    "skill" => "speed",
                    "value" => 80
                ]
            ]
        ]
    ];


    protected function log($data){
        fwrite(STDERR, print_r($data, TRUE));
    }
}

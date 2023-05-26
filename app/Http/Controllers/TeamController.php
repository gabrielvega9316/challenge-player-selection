<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ApiResponse;
use App\Models\Player;
use App\Models\PlayerSkill;

class TeamController extends Controller
{
    public function createTeam(Request $request) {
        $data = $request->all();
        $players_response = []; //array for selected players
        $players_selected = []; //array of ids to block playersa already chosen

        foreach($data as $attribute){
            $number_players = $attribute['numberOfPlayers'];
            $position = $attribute['position'];

            $players = Player::where('position', $attribute['position'])->get();
            //Validation for number of players available in the position
            if($attribute['numberOfPlayers'] > count($players)){
                return ApiResponse::badRequest('Insufficient number of players for the position: '.$attribute['position']);
            }
            //get players of the position sorted from highest to lowest by selected skill
            $skills_player = PlayerSkill::where('skill', $attribute['mainSkill'])->orderByDesc('value')->with(['player' => function ($query) use ($position){
                $query->select('id', 'name', 'position')->where('position', $position);
            }])->get();

            //the selected players are selected by position and skills. Subtract numberOfPlayers in each round if applicable.
            foreach($skills_player as $skill){
                if($skill->player && !in_array($skill->player->id, $players_selected)){
                    $players_response[] = $skill->player;
                    $players_selected[] = $skill->player->id;
                    $number_players--;
                }
                if($number_players === 0) break;
            }

            //If there are pending players to be selected, the players of the position chosen with the highest skill are selected.
            if($number_players > 0) {
                $skills_value = PlayerSkill::orderByDesc('value')->with(['player' => function ($query) use ($position){
                    $query->select('id', 'name', 'position')->where('position', $position);
                }])->get();

                foreach($skills_value as $skill){
                    if($skill->player && !in_array($skill->player->id, $players_selected)){
                        $players_response[] = $skill->player;
                        $players_selected[] = $skill->player->id;
                        $number_players--;
                    }
                    if($number_players === 0) break;
                }
            }
        }
        return ApiResponse::ok('Team successfully obtained', $players_response, 'team');
    }
}

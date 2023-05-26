<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use App\Helpers\ApiResponse;
use App\Models\Player;
use App\Models\PlayerSkill;

class PlayerController extends Controller
{
    public function index()
    {
        $player = Player::select('id', 'name', 'position')->get();
        try {
            if($player) return ApiResponse::ok('Players successfully obtained', $player->toArray(), 'players');
            else return ApiResponse::notFound("No players found");

        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function show($id)
    {
        $player = Player::select('id', 'name', 'position')->find($id);
        try {
            if($player) return ApiResponse::ok('Player successfully obtained', $player->toArray(), 'player');
            else return ApiResponse::notFound("No player found");

        } catch (\Throwable $th) {
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $player_validator = Player::playerValidator($data);
        if ($player_validator) return ApiResponse::badRequest($player_validator);

        DB::beginTransaction();
        try {
            $player = Player::create([
                'name' => $data['name'],
                'position' => $data['position']
            ]);

            foreach ($data['playerSkills'] as $item) {
                $skill_validator = PlayerSkill::skillValidator($item);
                if ($skill_validator) return ApiResponse::badRequest($skill_validator);

                $skill = PlayerSkill::create([
                    'skill' => $item['skill'],
                    'value' => $item['value'],
                    'player_id' => $player->id,
                ]);
            }
            DB::commit();

            $response = Player::select('id', 'name', 'position')->find($player->id);
            return ApiResponse::created('Player successfully created', $response->toArray(), 'player');

        } catch (\Throwable $th) {
            DB::rollback();
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function update(Request $request, $player_id)
    {
        $player = Player::find($player_id);
        try {
            if ($player) {
                $data = $request->all();
                $player_validator = Player::playerValidator($data);
                if ($player_validator) return ApiResponse::badRequest($player_validator);

                DB::beginTransaction();
                $player->update($data);

                foreach ($data['playerSkills'] as $item) {
                    $skill_validator = PlayerSkill::skillValidator($item);
                    if ($skill_validator) return ApiResponse::badRequest($skill_validator);

                    //Skills to  update
                    $skill = PlayerSkill::where('player_id', $player_id)->where('skill', $item['skill'])->first();
                    if($skill) {
                        $skill->update($item);
                    } else {
                    //Skill to create
                        $skill = PlayerSkill::create([
                            'skill' => $item['skill'],
                            'value' => $item['value'],
                            'player_id' => $player->id,
                        ]);
                    }
                }
                DB::commit();

                $response = Player::select('id', 'name', 'position')->find($player->id);
                return ApiResponse::created('Player successfully updated', $response->toArray(), 'player');
            } else {
                return ApiResponse::notFound('No player found');
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }

    public function destroy(Request $request, $player_id)
    {
        //Validacion por token
        $token = $request->bearerToken(); //obtengo token
        if (!$token) return response()->json(['message' => 'Unauthorized'], 401);
        if ($token !== 'SkFabTZibXE1aE14ckpQUUxHc2dnQ2RzdlFRTTM2NFE2cGI4d3RQNjZmdEFITmdBQkE=') {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        $player = Player::find($player_id);
        DB::beginTransaction();
        try {
            if($player){
                $player_skills = $player->skills()->delete();
                $player->delete();
                DB::commit();
                return ApiResponse::ok('Player correctly removed', $player->toArray());
            } else {
                return ApiResponse::notFound('No player found');
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return (config('app.debug')) ? ApiResponse::serverError($th->getMessage()) : ApiResponse::serverError();
        }
    }
}

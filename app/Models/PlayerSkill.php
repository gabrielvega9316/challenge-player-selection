<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\PlayerSkill as PlayerSkillEnum;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Validator;

class PlayerSkill extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    protected $fillable = [
        'skill',
        'value',
        'player_id'
    ];

    protected $casts = [
        'skill' => \App\Enums\PlayerSkill::class
    ];

    public static function rules(){
        return [
        'skill' =>['required' , new Enum(PlayerSkillEnum::class)],
        'value' => 'required|integer',
        ];
    }

    public function player(): BelongsTo
    {
        return $this->belongsTo(Player::class);
    }

    public static function skillValidator($data){
        $skill_validator = Validator::make($data, self::rules());
        if ($skill_validator->fails()){
            $error = $skill_validator->errors()->first().': '.$data[array_keys($skill_validator->errors()->toArray())[0]];
            return $error;
        }
        return false;
    }
}

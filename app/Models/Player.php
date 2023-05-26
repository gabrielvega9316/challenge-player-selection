<?php

namespace App\Models;

use App\Enums\PlayerPosition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Support\Facades\Validator;

/**
 * @property integer $id
 * @property string $name
 * @property PlayerPosition $position
 * @property PlayerSkill $skill
 */
class Player extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $fillable = [
        'name',
        'position'
    ];

    protected $casts = [
        'position' => PlayerPosition::class
    ];

    protected $with = ['skills'];

    public static function rules(){
        return [
            'name' => 'required|string|max:20',
            'position' => ['required' , new Enum(PlayerPosition::class)]
        ];
    }

    public function skills(): HasMany
    {
        return $this->hasMany(PlayerSkill::class);
    }

    public static function playerValidator($data){
        $player_validator = Validator::make($data, self::rules());
        if ($player_validator->fails()){
            $error = $player_validator->errors()->first().': '.$data[array_keys($player_validator->errors()->toArray())[0]];
            return $error;
        }
        return false;
    }

}

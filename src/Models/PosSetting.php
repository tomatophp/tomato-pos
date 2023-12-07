<?php

namespace TomatoPHP\TomatoPos\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class PosSetting extends Model
{
    protected  $table = "pos_settings";

    protected $fillable = [
        "user_id",
        "key",
        "value"
    ];

    protected $casts = [
        "value" => "json"
    ];


    public function user(){
        return $this->belongsTo(User::class);
    }
}

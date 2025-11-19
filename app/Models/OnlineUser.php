<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;

class OnlineUser extends Model
{
    use CrudTrait;
    protected $fillable = ['user_id', 'status', 'last_seen_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

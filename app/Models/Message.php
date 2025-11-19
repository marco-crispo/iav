<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use CrudTrait;
    /** @use HasFactory<\Database\Factories\MessageFactory> */
    use HasFactory;

    /**
     * fillable
     *
     * @var array
     */
    protected $fillable = [
        'sender_id',
        'receiver_id',
        'content',
        'is_read',
    ];

    /**
     * sender
     *
     * @return BelongsTo
     */
    public function sender() : BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id')->with('profile');
    }

    /**
     * receiver
     *
     * @return BelongsTo
     */
    public function receiver() : BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_id')->with('profile');
    }
}

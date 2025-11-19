<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    use CrudTrait;
    /** @use HasFactory<\Database\Factories\ProfileFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'availability_id',
        'name',
        'surname',
        'avatar',
        'cover_photo',
        'city',
        'country',
        'birthdate',
        'bio',
        'interests',
        'relationship_status',
        'sexual_orientation',
        'languages',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'sexual_orientation' => 'string',
        'availability_id' => 'integer',
    ];

    /**
     * user
     *
     * @return BelongsTo
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * availability
     *
     * @return BelongsTo
     */
    public function availability() : BelongsTo
    {
        return $this->belongsTo(Availability::class);
    }
}

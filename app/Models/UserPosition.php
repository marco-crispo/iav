<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Log;


class UserPosition extends Model
{
    use CrudTrait;
    /** @use HasFactory<\Database\Factories\UserPositionFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
    ];

    function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->with('profile');
    }

    /**
     * scopeNearby
     *
     * @param  mixed $query
     * @param  float $lat
     * @param  float $lng
     * @param  float $radius
     * @param  string|null $status
     * @return Builder
     */
    public function scopeNearby($query,
                                float $lat,
                                float $lng,
                                array $filters) : Builder
    {
        $haversine = "(6371 * acos(cos(radians($lat)) * cos(radians(user_positions.latitude)) * cos(radians(user_positions.longitude) - radians($lng)) + sin(radians($lat)) * sin(radians(user_positions.latitude))))";
        $query = $query
        ->join('profiles', 'user_positions.user_id', '=', 'profiles.user_id')
        ->join('availabilities', 'profiles.availability_id', '=', 'availabilities.id')
        ->leftJoin('online_users', 'user_positions.user_id', '=', 'online_users.user_id')
        ->select('user_positions.*')
        ->selectRaw('IF(online_users.last_seen_at IS NOT NULL, 1, 0) as online')
        ->selectRaw("$haversine AS distance")
        ->having('distance', '<=', $filters['radiusKm'])
        ->where(function ($query) use ($filters) {

            $query->where('user_positions.id', '>', 0);

            if ($filters['online'] ?? null) {
                $query->whereRaw('IF(online_users.last_seen_at IS NOT NULL, 1, 0) = ?', [$filters['online']]);
            }
            if ($filters['status'] ?? null) {
                $query->where('availabilities.status', $filters['status']);
            }
            if ($filters['name'] ?? null) {
                $query->where('profiles.name', 'like', '%' . $filters['name'] . '%');
            }
            if ($filters['surname'] ?? null) {
                $query->where('profiles.surname', 'like', '%' . $filters['surname'] . '%');
            }
            if ($filters['city'] ?? null) {
                $query->where('profiles.city', 'like', '%' . $filters['city'] . '%');
            }
            if ($filters['country'] ?? null) {
                $query->where('profiles.country', 'like', '%' . $filters['country'] . '%');
            }
            if ($filters['age'] ?? null) {
                $query->whereRaw('YEAR(CURDATE()) - YEAR(profiles.birthdate) between ? and ?', [$filters['age']['lower'], $filters['age']['upper']]);
            }
            if ($filters['bio'] ?? null) {
                $query->where('profiles.bio', 'like', '%' . $filters['bio'] . '%');
            }
            if ($filters['interests'] ?? null) {
                $query->where('profiles.interests', 'like', '%' . $filters['interests'] . '%');
            }
            if ($filters['relationship_status'] ?? null) {
                $query->where('profiles.relationship_status', 'like', '%' . $filters['relationship_status'] . '%');
            }
            if ($filters['sexual_orientation'] ?? null) {
                $query->where('profiles.sexual_orientation', 'like', '%' . $filters['sexual_orientation'] . '%');
            }
            if ($filters['languages'] ?? null) {
                $query->where('profiles.languages', 'like', '%' . $filters['languages'] . '%');
            }
        });
        // $query->where('user_positions.user_id', '!=', Auth::id());
        $query->orWhere('user_positions.user_id', Auth::id());

        return $query;
    }
}

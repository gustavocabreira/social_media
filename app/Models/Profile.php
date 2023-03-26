<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';
    protected $primaryKey = 'id';
    protected $fillable = [
        'organization_id',
        'name',
        'status',
    ];

    public static function boot(): void
    {
        parent::boot();

        static::creating(function($profile) {
            $profile->user_id = Auth::user()->id;
        });
    }

    public function organization(): BelongsTo
    {
        return $this->belongsTo(Organization::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function socialMedias(): BelongsToMany
    {
        return $this->belongsToMany(SocialMedia::class, 'profiles_social_medias');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SocialMedia extends Model
{
    use HasFactory;

    protected $table = 'social_medias';
    protected $primaryKey = 'id';
    protected $fillable = [
        'name',
        'icon',
        'status',
    ];

    public function profiles(): BelongsToMany
    {
        return $this->belongsToMany(Profile::class, 'profiles_social_medias');
    }
}

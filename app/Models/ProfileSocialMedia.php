<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfileSocialMedia extends Model
{
    use HasFactory;

    protected $table = 'profiles_social_medias';
    public $timestamps = false;

    protected $fillable = [
        'profile_id',
        'social_media_id',
    ];
}

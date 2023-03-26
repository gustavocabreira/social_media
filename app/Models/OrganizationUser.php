<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationUser extends Model
{
    use HasFactory;

    protected $table = 'organizations_users';
    protected $primaryKey = 'id';

    protected $fillable = [
        'organization_id',
        'user_id',
    ];
}

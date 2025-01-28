<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'tbl_users';

    protected $fillable = ['name', 'email', 'password', 'role', 'profile_picture', 'phone_number'];

    public function adminDestinations()
    {
        return $this->belongsTo(AdminDestination::class, 'user_id', 'id');
    }

    public function adminPlaces()
    {
        return $this->belongsTo(AdminPlace::class, 'user_id', 'id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
}

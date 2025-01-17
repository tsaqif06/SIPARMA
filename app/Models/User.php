<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'tbl_users';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    // Relasi ke tabel tbl_reviews
    public function reviews()
    {
        return $this->hasMany(Review::class, 'user_id');
    }

    // Relasi ke tabel tbl_transactions
    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'user_id');
    }

    // Relasi ke tabel tbl_complaints
    public function complaints()
    {
        return $this->hasMany(Complaint::class, 'user_id');
    }

    // Relasi ke tabel tbl_admin_destinations (1 user hanya memiliki 1 admin destination)
    public function adminDestination()
    {
        return $this->hasOne(AdminDestination::class, 'user_id');
    }

    // Relasi ke tabel tbl_admin_restaurants (1 user hanya memiliki 1 admin restaurant)
    public function adminRestaurant()
    {
        return $this->hasOne(AdminRestaurant::class, 'user_id');
    }
}

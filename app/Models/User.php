<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Model untuk entitas User (Pengguna).
 * Digunakan untuk menyimpan informasi pengguna yang terdaftar di sistem.
 *
 * @property int $id
 * @property string $name Nama pengguna.
 * @property string $email Alamat email pengguna.
 * @property string $password Kata sandi yang dienkripsi.
 * @property string $role Peran pengguna (misal: admin, member, dll).
 * @property string $profile_picture URL atau path ke gambar profil pengguna.
 * @property string $phone_number Nomor telepon pengguna.
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Nama tabel yang digunakan oleh model ini.
     *
     * @var string
     */
    protected $table = 'tbl_users';

    /**
     * Kolom yang dapat diisi secara massal.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'role', 'profile_picture', 'phone_number'];

    /**
     * Mendefinisikan relasi dengan model AdminDestination.
     * Menunjukkan bahwa seorang pengguna dapat memiliki banyak destinasi yang dikelola.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adminDestinations()
    {
        return $this->hasMany(AdminDestination::class, 'user_id', 'id');
    }

    /**
     * Mendefinisikan relasi dengan model AdminPlace.
     * Menunjukkan bahwa seorang pengguna dapat memiliki banyak tempat yang dikelola.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function adminPlaces()
    {
        return $this->hasMany(AdminPlace::class, 'user_id', 'id');
    }

    /**
     * Mendefinisikan relasi dengan model Transaction.
     * Menunjukkan bahwa seorang pengguna dapat memiliki banyak transaksi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Mendefinisikan relasi dengan model Review.
     * Menunjukkan bahwa seorang pengguna dapat memberikan banyak ulasan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Mendefinisikan relasi dengan model Complaint.
     * Menunjukkan bahwa seorang pengguna dapat mengajukan banyak keluhan.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function complaints()
    {
        return $this->hasMany(Complaint::class);
    }

    /**
     * Mendefinisikan relasi dengan model Recommendation.
     * Menunjukkan bahwa seorang pengguna dapat memberikan banyak rekomendasi.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recommendations()
    {
        return $this->hasMany(Recommendation::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * @OA\Schema(
 *    title="User",
 *   description="User model",
 * @OA\Property(
 * property="id",
 * type="integer",
 * description="User id",
 * example="1"
 * ),
 * @OA\Property(
 * property="name",
 * type="string",
 * description="User name",
 * example="John Doe"
 * ),
 * @OA\Property(
 * property="email",
 * type="string",
 * description="User email",
 * example="johndoe@gmail.com"
 * ),
 * @OA\Property(
 * property="password",
 * type="string",
 * description="User password",
 * example="password"
 * ),
 * @OA\Property(
 * property="created_at",
 * type="string",
 * format="date-time",
 * description="User created at",
 * example="2021-01-01 00:00:00"
 * ),
 * @OA\Property(
 * property="updated_at",
 * type="string",
 * format="date-time",
 * description="User updated at",
 * example="2021-01-01 00:00:00"
 * ),
 * @OA\Property(
 * property="deleted_at",
 * type="string",
 * format="date-time",
 * description="User deleted at",
 * example="2021-01-01 00:00:00"
 * ),
 * required={"name","email","password"},
 * )
 */

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return int
     */
    public function getJWTIdentifier(): int
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array<string, string>
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    public function transformFull(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email
        ];
    }
}

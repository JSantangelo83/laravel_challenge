<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(
 * title="Actor",
 * description="Actor model",
 * @OA\Property(
 * property="id",
 * type="integer",
 * description="Actor id",
 * example="1"
 * ),
 * @OA\Property(
 * property="name",
 * type="string",
 * description="Actor name",
 * example="John Doe"
 * ),
 * @OA\Property(
 * property="birth_date",
 * type="string",
 * format="date",
 * description="Actor birth date",
 * example="1990-01-01"
 * ),
 * 
 * @OA\Property(
 *     property="gender",
 *     ref="#/components/schemas/GenderEnum",
 *     description="Gender of the person"
 * ),
 * )
 */

class Actor extends Model
{
    use HasFactory;

    protected $casts = [
        'gender' => Gender::class
    ];

    public function movies(){
        return $this->belongsToMany(Movie::class, 'actors_movies');
    }

    public function transformFull(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender->getDescription(),
            'movies' => $this->movies->map(function($movie){
                return $movie->transformSimple();
            })
        ];
    }

    public function transformSimple(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender->getDescription(),
        ];
    }
}

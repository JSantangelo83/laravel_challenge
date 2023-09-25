<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Gender;


/**
 * @OA\Schema(
 * title="Director",
 * description="Director model",
 * @OA\Property(
 * property="id",
 * type="integer",
 * description="Director id",
 * example="1"
 * ),
 * @OA\Property(
 * property="name",
 * type="string",
 * description="Director name",
 * example="John Doe"
 * ),
 * @OA\Property(
 * property="birth_date",
 * type="string",
 * format="date",
 * description="Director birth date",
 * example="1990-01-01"
 * ),
 * 
 * @OA\Property(
 *     property="gender",
 *     ref="#/components/schemas/GenderEnum",
 *     description="Gender of the person"
 * ),
 *  
 * )
 * 
 */
class Director extends Model
{
    use HasFactory;

    protected $casts = [
        'gender' => Gender::class
    ];

    public function movies(){
        return $this->hasMany(Movie::class);
    }

    public function tvshows(){
        return $this->hasMany(TvShow::class);
    }

    public function episodes(){
        return $this->hasMany(TvShowEpisode::class);
    }

    public function transformFull(){
        return [
            'id' => $this->id,
            'name' => $this->name,
            'birth_date' => $this->birth_date,
            'gender' => $this->gender->getDescription(),
            'movies' => $this->movies->map(function($movie){
                return $movie->transformSimple();
            }),
            'episodes' => $this->episodes->map(function($episode){
                return $episode->transformSimple();
            }),
            'tvshow' => $this->tvshow->map(function($tvshow){
                return $tvshow->transformSimple();
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

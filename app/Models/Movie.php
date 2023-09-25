<?php

namespace App\Models;

use App\Enums\Genre;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 *  @OA\Schema(
 *  title="Movie",
 * description="Movie model",
 * @OA\Property(
 * property="id",
 * type="integer",
 * description="Movie id",
 * example="1"
 * ),
 * @OA\Property(
 * property="title",
 * type="string",
 * description="Movie title",
 *  
 * ),
 * @OA\Property(
 * property="release_date",
 * type="string",
 * format="date",
 * description="Movie release date",
 *  
 * ),
 * @OA\Property(
 * property="synopsis",
 * type="string",
 * description="Movie synopsis",
 *      
 * ),
 * @OA\Property(
 * property="rating",
 * type="integer",
 * description="Movie rating",
 * example="1"
 * ),
 * @OA\Property(
 * property="length",
 * type="time",
 * description="Movie length",
 *  
 * ),
 * @OA\Property(
 *     property="genre",
 *     ref="#/components/schemas/GenreEnum",
 *     description="Genre of the movie"
 * ),
 * @OA\Property(
 * property="director_id",
 * type="integer",
 * description="Movie director id",
 * example="1"
 * ),
 * @OA\Property(
 * property="age_classification_id",
 * type="integer",
 * description="Movie age classification id",
 * example="1"
 * ),
 * @OA\Property(
 * property="created_at",
 * type="string",
 * format="date-time",
 * description="Movie created at",
 *  
 * ),
 * @OA\Property(
 * property="updated_at",
 * type="string",
 * format="date-time",
 * description="Movie updated at",
 *  
 * ),
 *  
 * )
 *  
 * 
 * 
 * 
 */

class Movie extends Model
{
    use HasFactory;

    protected $with = ['actors', 'director', 'ageClassification'];

    protected $casts = [
        'genre' => Genre::class
    ];

    
    public function actors(){
        return $this->belongsToMany(Actor::class, 'actors_movies');
    }

    public function director(){
        return $this->belongsTo(Director::class, 'director_id');
    }

    public function ageClassification(){
        return $this->belongsTo(AgeClassification::class, 'age_classification_id');
    }

    public function transformFull(){
        return [
            'id' => $this->id,
            'title' => $this->title,
            'release_date' => $this->release_date,
            'synopsis' => $this->synopsis,
            'rating' => $this->rating,
            'length' => $this->length,
            'genre' => $this->genre->getDescription(),
            'director' => $this->director->transformSimple(),
            'age_classification' => $this->ageClassification->transformSimple(),
            'actors' => $this->actors->map(function($actor){
                return $actor->transformSimple();
            })
        ];
    }

    public function transformSimple(){
        return [
            'id' => $this->id,
            'title' => $this->title,
            'genre' => $this->genre->getDescription(),
            'release_date' => $this->release_date
        ];
    }
}

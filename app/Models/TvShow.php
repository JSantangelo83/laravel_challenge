<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 * title="TvShow",
 * description="TvShow model",
 * @OA\Property(
 * property="id",
 * type="integer",
 * description="TvShow id",
 * example="1"
 * ),
 * @OA\Property(
 * property="title",
 * type="string",
 * description="TvShow title",
 * example="The Witcher"
 * ),
 * @OA\Property(
 * property="release_date",
 * type="string",
 * format="date-time",
 * description="TvShow release date",
 * example="2021-01-01 00:00:00"
 * ),
 * @OA\Property(
 * property="synopsis",
 * type="string",
 * description="TvShow synopsis",
 * example="The Witcher is an American fantasy drama streaming television series produced by Lauren Schmidt Hissrich. It is based on the book series of the same name by Polish writer Andrzej Sapkowski."
 * ),
 * @OA\Property(
 * property="rating",
 * type="integer",
 * description="TvShow rating",
 * example="5"
 * ),
 * @OA\Property(
 *     property="genre",
 *     ref="#/components/schemas/GenreEnum",
 *     description="Genre of the Tv Show",
 * ),
 * @OA\Property(
 * property="director",
 * type="object",
 * description="TvShow director",
 * ref="#/components/schemas/Director"
 * ),
 * @OA\Property(
 * property="age_classification",
 * type="object",
 * description="TvShow age classification",
 * ref="#/components/schemas/AgeClassification"
 * ),
 * @OA\Property(
 * property="seasons",
 * type="array",
 * description="TvShow seasons",
 * @OA\Items(
 * ref="#/components/schemas/TvShowSeason"
 * )
 * )
 * )
 * )
 * 
 * 
 */
class TvShow extends Model
{
    use HasFactory;

    protected $with = ['seasons', 'director', 'ageClassification'];

    protected $casts = [
        'genre' => Genre::class
    ];

    public function seasons(){
        return $this->hasMany(Season::class, 'tv_show_id');
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
            'genre' => $this->genre->getDescription(),
            'director' => $this->director->transformSimple(),
            'age_classification' => $this->ageClassification->transformSimple(),
            'seasons' => $this->seasons->map(function($season){
                return $season->transformSimple();
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

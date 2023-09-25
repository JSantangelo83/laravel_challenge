<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *  title="TvShowEpisode",
 *  description="TvShow Episode model",
 * @OA\Property(
 * property="id",
 * type="integer",
 * description="TvShow Episode id",
 * example="1"
 * ),
 * @OA\Property(
 * property="episode_number",
 * type="integer",
 * description="TvShow Episode number",
 *  
 * ),
 * @OA\Property(
 * property="title",
 * type="string",
 * description="TvShow Episode title",
 * example="Episode 1"
 * ),
 * @OA\Property(
 * property="air_date",
 * type="string",
 * format="date-time",
 * description="TvShow Episode air date",
 *  
 * ),
 * @OA\Property(
 * property="synopsis",
 * type="string",
 * description="TvShow Episode synopsis",
 *  
 * ),
 * 
 * @OA\Property(
 * property="length",
 * type="time",
 * description="TvShow Episode length",
 * 
 * ),
 * @OA\Property(
 * property="season_id",
 * type="integer",
 * description="TvShow Episode season id",
 * example="1"
 * ),
 * @OA\Property(
 * property="director_id",
 * type="integer",
 * description="TvShow Episode director id",
 * example="1"
 * ),
 * @OA\Property(
 * property="created_at",
 * type="string",
 * format="date-time",
 * description="TvShow Episode created at",
 * example="2021-01-01 00:00:00"
 * ),
 * @OA\Property(
 * property="updated_at",
 * type="string",
 * format="date-time",
 * description="TvShow Episode updated at",
 * example="2021-01-01 00:00:00"
 * ),
 * @OA\Property(
 * property="deleted_at",
 * type="string",
 * format="date-time",
 * description="TvShow Episode deleted at",
 * example="2021-01-01 00:00:00"
 * ),
 * required={"episode_number","title","air_date","synopsis","length","season_id","director_id"},
 * ),
 * ),
 * 
 * 
 */
class TvShowEpisode extends Model
{
    use HasFactory;

    protected $with = ['season', 'director', 'actors'];

    public function season(){
        return $this->belongsTo(Season::class, 'season_id');
    }

    public function actors(){
        return $this->belongsToMany(Actor::class, 'actors_tv_show_episodes');
    }

    public function director(){
        return $this->belongsTo(Director::class, 'director_id');
    }

    public function transformFull(){
        return [
            'id' => $this->id,
            'episode_number' => $this->episode_number,
            'title' => $this->title,
            'air_date' => $this->air_date,
            'synopsis' => $this->synopsis,
            'length' => $this->length,
            'season' => $this->season->transformSimple(),
            'director' => $this->director->transformSimple(),
            'actors' => $this->actors->map(function($actor){
                return $actor->transformSimple();
            })
        ];
    }

    public function transformSimple(){
        return [
            'id' => $this->id,
            'episode_number' => $this->episode_number,
            'title' => $this->title,
            'air_date' => $this->air_date
        ];
    }
}

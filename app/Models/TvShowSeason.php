<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


/**
 * @OA\Schema(
 *   title="TvShowSeason",
 *  description="TvShow Season model",
 * @OA\Property(
 * property="id",
 * type="integer",
 * description="TvShow Season id",
 * example="1"
 * ),
 * @OA\Property(
 * property="season_number",
 * type="integer",
 * description="TvShow Season number",
 * example="1"
 * ),
 * @OA\Property(
 * property="tv_show_id",
 * type="integer",
 * description="TvShow Season tv show id",
 * example="1"
 * ),
 * @OA\Property(
 * property="created_at",
 * type="string",
 * format="date-time",
 * description="TvShow Season created at",
 * example="2021-01-01 00:00:00"
 * ),
 * @OA\Property(
 * property="updated_at",
 * type="string",
 * format="date-time",
 * description="TvShow Season updated at",
 * example="2021-01-01 00:00:00"
 * ),
 * @OA\Property(
 * property="deleted_at",
 * type="string",
 * format="date-time",
 * description="TvShow Season deleted at",
 * example="2021-01-01 00:00:00"
 * ),
 * required={"season_number","tv_show_id"},
 * )
 * 
 */
class TvShowSeason extends Model
{
    use HasFactory;

    protected $with = ['episodes', 'tvShow'];

    public function episodes(){
        return $this->hasMany(TvShowEpisode::class, 'season_id');
    }

    public function tvShow(){
        return $this->belongsTo(TvShow::class, 'tv_show_id');
    }

    public function transformFull(){
        return [
            'id' => $this->id,
            'season_number' => $this->season_number,
            'episodes' => $this->episodes->map(function($episode){
                return $episode->transformSimple();
            }),
            'tv_show' => $this->tvShow->transformSimple()
        ];
    }

    public function transformSimple(){
        return [
            'id' => $this->id,
            'season_number' => $this->season_number
        ];
    }
}

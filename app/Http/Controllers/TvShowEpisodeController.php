<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TvShowEpisode;
use App\Models\TvShow;
use App\Models\TvShowSeason;
use App\Models\Director;
use App\Models\Actor;

class TvShowEpisodeController extends Controller
{
    /**
     * Display a listing of the episode.
     */

    /**
     * @OA\Get(
     * path="/api/tvshows/{tvShow}/seasons/{season}/episodes",
     * summary="Display a listing of the episode",
     * tags={"Tv Show"},
     * @OA\Parameter(
     *     name="tvShow",
     *     in="path",
     *     description="ID of the TV Show",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     * ),
     * @OA\Parameter(
     *     name="season",
     *     in="path",
     *     description="ID of the Season",
     *     required=true,
     *     @OA\Schema(
     *         type="integer"
     *     )
     * ),
     * @OA\Response(
     *     response=200,
     *     description="A listing of the episode",
     *     @OA\JsonContent(
     *         @OA\Property(property="tvShowEpisodes", type="object"),
     *     ),
     * ),
     * )
     */

    public function index($season)
    {
        $season = TvShowSeason::findOrFail($season);

        $tvShowEpisodes = $season->episodes()->map(function($episodes){
            return $episodes->transformFull();
        });

        return response()->json($tvShowEpisodes);
    }

    /**
     * Store a newly created episode in storage.
     */

     /**
     * @OA\Post(
     *     path="/api/tvshows/{tvShow}/seasons/{season}/episodes",
     *     summary="Store a newly created episode in storage",
     *     tags={"Tv Show"},
     *     @OA\Parameter(
     *         name="tvShow",
     *         in="path",
     *         description="ID of the TV Show",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="season",
     *         in="path",
     *         description="ID of the Season",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass episode data",
     *         @OA\JsonContent(
     *             @OA\Property(property="episode_number", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="air_date", type="string", format="date"),
     *             @OA\Property(property="synopsis", type="string"),
     *             @OA\Property(property="length", type="integer"),
     *             @OA\Property(property="actors", type="array", @OA\Items(type="integer")),
     *             @OA\Property(property="director_id", type="integer"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Episode created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="tvShowEpisode", type="object"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tv Show Season not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     * )
     */

      
    public function store(Request $request, $season)
    {
        $tvShowEpisode = new TvShowEpisode;
        $tvShowEpisode->episode_number = $request->episode_number;
        $tvShowEpisode->title = $request->title;
        $tvShowEpisode->air_date = $request->air_date;
        $tvShowEpisode->synopsis = $request->synopsis;
        $tvShowEpisode->length = $request->length;

        // validations
        $request->validate([
            'episode_number' => 'required|integer',
            'title' => 'required|string',
            'air_date' => 'required|date',
            'synopsis' => 'required|string',
            'length' => 'required|integer',
            'actors' => 'require|array',
            'director_id' => 'required|integer',
        ]);

        // check if tv show season exists
        $tvShowSeason = TvShowSeason::find($season);
        if (!$tvShowSeason) return response()->json(['message' => 'Tv Show Season not found'], 404);
        else $tvShowEpisode->tvShowSeason()->associate($tvShowSeason);

        // check if director exists
        $director = Director::find($request->director_id);
        if (!$director) return response()->json(['message' => 'Director not found'], 404);
        else $tvShowEpisode->director()->associate($director);


        // check if actors exists
        $actors = $request->input("actors");
        foreach ($actors as $actor) {
            $actor = Actor::find($actor);
            if (!$actor) return response()->json(['message' => 'Actor not found'], 404);
        }

        $tvShowEpisode->save();

        //atach actors
        $tvShowEpisode->actors()->attach($actors);

        $data = [
            'message' => 'Tv Show Episode created successfully',
            'tvShowEpisode' => $tvShowEpisode
        ];

        return response()->json($data);
    }

    /**
     * Display the specified episode.
     */

     /**
     * @OA\Get(
     *     path="/api/tvshows/{tvShow}/seasons/{season}/episodes/{episode}",
     *     summary="Display the specified episode",
     *     tags={"Tv Show"},
     *     @OA\Parameter(
     *         name="tvShow",
     *         in="path",
     *         description="ID of the TV Show",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="season",
     *         in="path",
     *         description="ID of the Season",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="episode",
     *         in="path",
     *         description="ID of the Episode",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Display the specified episode",
     *         @OA\JsonContent(
     *             @OA\Property(property="tvShowEpisode", type="object"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tv Show Episode not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     * )
     */

     
    public function show(TvShowEpisode $episode)
    {
        if (!$episode) {
            return response()->json([
                'message' => 'Tv Show Episode not found'
            ], 404);
        }
        $episode->transformFull();
        return response()->json($episode);
    }

    /**
     * Update the specified episode in storage.
     */

    /**
     * @OA\Put(
     *     path="/api/tvshows/{tvShow}/seasons/{season}/episodes/{episode}",
     *     summary="Update the specified episode in storage",
     *     tags={"Tv Show"},
     *     @OA\Parameter(
     *         name="tvShow",
     *         in="path",
     *         description="ID of the TV Show",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="season",
     *         in="path",
     *         description="ID of the Season",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="episode",
     *         in="path",
     *         description="ID of the Episode",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass episode data",
     *         @OA\JsonContent(
     *             @OA\Property(property="episode_number", type="integer"),
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="air_date", type="string", format="date"),
     *             @OA\Property(property="synopsis", type="string"),
     *             @OA\Property(property="length", type="integer"),
     *             @OA\Property(property="actors", type="array", @OA\Items(type="integer")),
     *             @OA\Property(property="director_id", type="integer"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Episode updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="tvShowEpisode", type="object"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tv Show Episode not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     * )
     */


    public function update(Request $request, TvShowEpisode $episode, TvShowSeason $season)
    {
        //check if episode exists
        if (!$episode) {
            return response()->json([
                'message' => 'Tv Show Episode not found'
            ], 404);
        }


        $episode->season_id = $request->season_id;
        $episode->episode_number = $request->episode_number;
        $episode->title = $request->title;
        $episode->air_date = $request->air_date;
        $episode->synopsis = $request->synopsis;
        $episode->length = $request->length;

        // validations
        $request->validate([
            'season_id' => 'integer',
            'episode_number' => 'integer',
            'title' => 'string',
            'air_date' => 'date',
            'synopsis' => 'string',
            'length' => 'integer',
            'actors' => 'array',
            'director_id' => 'integer',
        ]);

        // check if tv show season exists
        $tvShowSeason = TvShowSeason::find($season);
        if (!$tvShowSeason) return response()->json(['message' => 'Tv Show Season not found'], 404);
        else $episode->tvShow()->associate($tvShowSeason);

        // check if director exists
        if ($episode->director_id) {
            $director = Director::find($request->director_id);
            if (!$director) return response()->json(['message' => 'Director not found'], 404);
            else $episode->director()->associate($director);
        }

        // check if actors exists
        if ($episode->actors) {
            $actors = $request->input("actors");
            foreach ($actors as $actor) {
                $actor = Actor::find($actor);
                if (!$actor) return response()->json(['message' => 'Actor not found'], 404);
            }
            $episode->actors()->sync($actors);
        }

        $episode->save();

        $data = [
            'message' => 'Tv Show Episode updated successfully',
            'tvShowEpisode' => $episode
        ];

        return response()->json($data);
    }

    /**
     * Remove the specified episode from storage.
     */

     /**
     * @OA\Delete(
     *     path="/api/tvshows/{tvShow}/seasons/{season}/episodes/{episode}",
     *     summary="Remove the specified episode from storage",
     *     tags={"Tv Show"},
     *     @OA\Parameter(
     *         name="tvShow",
     *         in="path",
     *         description="ID of the TV Show",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="season",
     *         in="path",
     *         description="ID of the Season",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="episode",
     *         in="path",
     *         description="ID of the Episode",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Episode deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="tvShowEpisode", type="object"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tv Show Episode not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     * )
     */

    
         
    public function destroy(TvShowEpisode $episode)
    {
        //check if episode exists
        if (!$episode) {
            return response()->json([
                'message' => 'Tv Show Episode not found'
            ], 404);
        }

        $episode->delete();

        $data = [
            'message' => 'Tv Show Episode deleted successfully',
            'tvShowEpisode' => $episode
        ];

        return response()->json($data);
    }
}

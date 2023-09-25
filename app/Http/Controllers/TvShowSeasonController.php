<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TvShowSeason;
use App\Models\TvShow;

class TvShowSeasonController extends Controller
{
    /**
     * Display a listing of the seasons.
     */

    /**
     * @OA\Get(
     * path="/api/tvshows/{tvShow}/seasons",
     * summary="Display a listing of the seasons",
     * tags={"Tv Show Seasons"},
     * @OA\Response(
     * response=200,
     * description="A listing of the seasons",
     * @OA\JsonContent(
     * @OA\Property(property="tvShowSeasons", type="object"),
     * ),
     * ),
     * ),
     * ),
     * 
     */
    public function index($tvShow)
    {
        $tvShow = TvShow::findOrFail($tvShow);

        $tvShowSeasons = $tvShow->seasons()->map(function($season){
            return $season->transformFull();
        });

        return response()->json($tvShowSeasons);
    }

    /**
     * Store a newly created season in storage.
     */

    /**
     * @OA\Post(
     *     path="/api/tvshows/{tvShow}/seasons",
     *     summary="Store a newly created season in storage",
     *     tags={"Tv Show Seasons"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass season data",
     *         @OA\JsonContent(
     *             @OA\Property(property="tvshow_id", type="integer"),
     *             @OA\Property(property="season_number", type="integer"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Season created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="tvShowSeason", type="object"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Tv Show not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object"),
     *         ),
     *     ),
     * )
     */

    public function store(Request $request)
    {
        $tvShowSeason = new TvShowSeason;
        $tvShowSeason->tvshow_id = $request->tvshow_id;
        $tvShowSeason->season_number = $request->season_number;

        // validations
        $request->validate([
            'tvShow_id' => 'integer',
            'season_number' => 'integer'
        ]);

        // check if tv show exists
        $tvShow = TvShow::find($request->tvshow_id);
        if (!$tvShow) return response()->json(['message' => 'Tv Show not found'], 404);
        else $tvShowSeason->tvShow()->associate($tvShow);

        $tvShowSeason->save();

        $data = [
            'message' => 'Tv Show Season created successfully',
            'tvShowSeason' => $tvShowSeason
        ];

        return response()->json($data);
    }

    /**
     * Display the specified season.
     */

    /**
     * @OA\Get(
     * path="/api/tvshows/seasons/{tvShowSeason}",
     * summary="Display the specified season",
     * tags={"Tv Show Seasons"},
     * @OA\Response(
     * response=200,
     * description="Display the specified season",
     * @OA\JsonContent(
     * @OA\Property(property="tvShowSeason", type="object"),
     * ),
     * ),
     * @OA\Response(
     * response=404,
     * description="Tv Show Season not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * ),
     * ),
     * )
     */

    public function show(TvShowSeason $tvShowSeason)
    {
        if (!$tvShowSeason) {
            return response()->json([
                'message' => 'Tv Show Season not found'
            ], 404);
        }
        $tvShowSeason->transformFull();
        return response()->json($tvShowSeason);
    }

    /**
     * Update the specified season in storage.
     */

    /**
     * @OA\Put(
     *     path="/api/tvshows/seasons/{tvShowSeason}",
     *     summary="Update the specified season in storage",
     *     tags={"Tv Show Seasons"},
     *     @OA\Parameter(
     *         name="tvShowSeason",
     *         in="path",
     *         required=true,
     *         description="Season ID",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass season data",
     *         @OA\JsonContent(
     *             @OA\Property(property="tvshow_id", type="integer"),
     *             @OA\Property(property="season_number", type="integer"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Season updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="tvShowSeason", type="object"),
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
     *         description="Validation errors",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object"),
     *         ),
     *     ),
     * )
     */

    public function update(Request $request, TvShow $tvShowSeason)
    {
        //check if season exists
        if (!$tvShowSeason) return response()->json(['message' => 'Tv Show Season not found'], 404);


        $tvShowSeason->tvShow_id = $request->tvShow_id;
        $tvShowSeason->season_number = $request->season_number;

        // validations
        $request->validate([
            'tvShow_id' => 'integer',
            'season_number' => 'integer'
        ]);

        // check if tv show exists
        if ($request->tvShow_id){
            $tvShow = TvShow::find($request->tvShow_id);
            if (!$tvShow) return response()->json(['message' => 'Tv Show not found'], 404);
            else $tvShowSeason->tvShow()->associate($tvShow);
        }

        $tvShowSeason->save();

        $data = [
            'message' => 'Tv Show Season updated successfully',
            'tvShowSeason' => $tvShowSeason
        ];

        return response()->json($data);
    }

    /**
     * Remove the specified season from storage.
     */

         /**
     * @OA\Delete(
     * path="/api/tvshows/seasons/{tvShowSeason}",
     * summary="Remove the specified season from storage",
     * tags={"Tv Show Seasons"},
     * @OA\Response(
     * response=200,
     * description="Season deleted successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * @OA\Property(property="tvShowSeason", type="object"),
     * ),
     * ),
     * 
     * @OA\Response(
     * response=404,
     * description="Tv Show Season not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * ),
     * ),
     * 
     * ),
     * ),
     * 
     */
    public function destroy(TvShow $tvShow)
    {
        //check if season exists
        if (!$tvShow) return response()->json(['message' => 'Tv Show not found'], 404);
        
        $tvShow->delete();

        $data = [
            'message' => 'Tv Show deleted successfully',
            'tvShow' => $tvShow
        ];

        return response()->json($data);
    }
}

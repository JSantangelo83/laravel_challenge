<?php

namespace App\Http\Controllers;

use App\Models\TvShow;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enums\Genre;
use App\Models\Director;
use App\Models\AgeClassification;

/**
 * @OA\Tag(
 * name="Tv Shows",
 * description="API Endpoints of Tv Shows Controller"
 * )
 */
class TvShowController extends Controller
{
    /**
     * Display a listing of the tv show.
     */

         /**
     * @OA\Get(
     * path="/api/tvshows",
     * summary="Display a listing of the tv shows",
     * tags={"Tv Shows"},
     * @OA\Response(
     * response=200,
     * description="A listing of the tv show",
     * @OA\JsonContent(
     * @OA\Property(property="tvShows", type="object"),
     * ),
     * ),
     * ),
     * ),
     * 
     */
    public function index()
    {
        $tvShows = TvShow::all();
        return response()->json($tvShows);
    }

    /**
     * Store a newly created tv show in storage.
     */

    /**
     * @OA\Post(
     *     path="/api/tvshows",
     *     summary="Store a newly created tv show in storage",
     *     tags={"Tv Shows"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass tv show data",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="release_date", type="string"),
     *             @OA\Property(property="rating", type="integer"),
     *             @OA\Property(property="genre", type="string"),
     *             @OA\Property(property="director_id", type="integer"),
     *             @OA\Property(property="age_classification_id", type="integer"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tv show created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="tvShow", type="object", ref="#/components/schemas/TvShow"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Invalid data passed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object"),
     *         ),
     *     ),
     * )
     */


    public function store(Request $request)
    {
        $tvShow = new TvShow;
        $tvShow->title = $request->title;
        $tvShow->release_date = $request->release_date;
        $tvShow->rating = $request->rating;
        $tvShow->genre = $request->genre;
        $tvShow->director_id = $request->director_id;
        $tvShow->age_classification_id = $request->age_classification_id;

        // validations
        $request->validate([
            'title' => 'required|string',
            'release_date' => 'required|date',
            'rating' => 'required|integer',
            'genre' => ['required', Rule::in(Genre::getGenreOptions())],
            'director_id' => 'required|integer',
            'age_classification_id' => 'required|integer'
        ]);

        // check if director exists
        $director = Director::find($request->director_id);
        if (!$director) return response()->json(['message' => 'Director not found'], 404);
        else $tvShow->director()->associate($director);

        // check if age classification exists
        $ageClassification = AgeClassification::find($request->age_classification_id);
        if (!$ageClassification) return response()->json(['message' => 'Age Classification not found'], 404);
        else $tvShow->ageClassification()->associate($ageClassification);

        $tvShow->save();

        $data = [
            'message' => 'Tv Show created successfully',
            'tvShow' => $tvShow
        ];

        return response()->json($data);
    }

    /**
     * Display the specified tv show.
     */

    /**
     * @OA\Get(
     * path="/api/tvshows/{tvShow}",
     * summary="Display the specified tv show",
     * tags={"Tv Shows"},
     * @OA\Response(
     * response=200,
     * description="Display the specified tv show",
     * @OA\JsonContent(
     * @OA\Property(property="tvShow", type="object"),
     * ),
     * 
     * ),
     * 
     * @OA\Response(
     * response=404,
     * description="Tv Show not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * ),
     * ),
     * ),
     * 
     * ),
     * ),
     * 
     */
    public function show(TvShow $tvShow)
    {
        if (!$tvShow) {
            return response()->json([
                'message' => 'Tv Show not found'
            ], 404);
        }
        $tvShow = $tvShow->transformFull();
        return response()->json($tvShow);
    }

    /**
     * Update the specified tv show in storage.
     */

     /**
     * @OA\Put(
     *     path="/api/tvshows/{tvShow}",
     *     summary="Update the specified tv show in storage",
     *     tags={"Tv Shows"},
     *     @OA\Parameter(
     *         name="tvShow",
     *         required=true,
     *         in="path",
     *         description="TV Show ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass tv show data",
     *         @OA\JsonContent(
     *             @OA\Property(property="title", type="string"),
     *             @OA\Property(property="release_date", type="string"),
     *             @OA\Property(property="rating", type="integer"),
     *             @OA\Property(property="genre", type="string"),
     *             @OA\Property(property="director_id", type="integer"),
     *             @OA\Property(property="age_classification_id", type="integer"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Tv show updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="tvShow", type="object", ref="#/components/schemas/TvShow"),
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
     *         description="Invalid data passed",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="errors", type="object"),
     *         ),
     *     ),
     * )
     */

      
    public function update(Request $request, TvShow $tvShow)
    {
        //check if tv show exists
        if (!$tvShow) return response()->json(['message' => 'Tv Show not found'], 404);


        $tvShow->title = $request->title;
        $tvShow->release_date = $request->release_date;
        $tvShow->rating = $request->rating;
        $tvShow->genre = $request->genre;
        $tvShow->director_id = $request->director_id;
        $tvShow->age_classification_id = $request->age_classification_id;

        // validations
        $request->validate([
            'title' => 'string',
            'release_date' => 'date',
            'rating' => 'integer',
            'genre' => ['required', Rule::in(Genre::getGenreOptions())],
            'director_id' => 'integer',
            'age_classification_id' => 'integer'
        ]);

        // check if director exists
        if ($request->director_id){
            $director = Director::find($request->director_id);
            if (!$director) return response()->json(['message' => 'Director not found'], 404);
            else $tvShow->director()->associate($director);
        }

        // check if age classification exists
        if ($request->age_classification_id){
            $ageClassification = AgeClassification::find($request->age_classification_id);
            if (!$ageClassification) return response()->json(['message' => 'Age Classification not found'], 404);
            else $tvShow->ageClassification()->associate($ageClassification);
        }

        $tvShow->save();

        $data = [
            'message' => 'Tv Show updated successfully',
            'tvShow' => $tvShow
        ];

        return response()->json($data);
    }

    /**
     * Remove the specified tv show from storage.
     */

        /**
     * @OA\Delete(
     * path="/api/tvshows/{tvShow}",
     * summary="Remove the specified tv show from storage",
     * tags={"Tv Shows"},
     * @OA\Response(
     * response=200,
     * description="Tv Show deleted successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * @OA\Property(property="tvShow", type="object"),
     * ),
     * ),
     * 
     * @OA\Response(
     * response=404,
     * description="Tv Show not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * ),
     * ),
     * ),
     * 
     * ),
     * ),
     * 
     */
    public function destroy(TvShow $tvShow)
    {
        //check if tv show exists
        if (!$tvShow) return response()->json(['message' => 'Tv Show not found'], 404);

        $tvShow->delete();

        $data = [
            'message' => 'Tv Show deleted successfully',
            'tvShow' => $tvShow
        ];

        return response()->json($data);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enums\Genre;


/**
 * @OA\Tag(
 * name="Actors",
 * description="API Endpoints of Actors Controller"
 * )
 */
class ActorController extends Controller
{
    /**
     * Display a listing of the actors.
     */

    /**
     * @OA\Get(
     * path="/api/actors",
     * summary="Display a listing of the actors",
     * tags={"Actors"},
     * @OA\Response(
     * response=200,
     * description="A listing of the actors",
     * @OA\JsonContent(
     * @OA\Property(property="actors", type="object", ref="#/components/schemas/Actor"),
     * ),
     * ),
     * ),
     * ),
     * 
     */
    

    public function index()
    {
        $actors = Actor::all();
        return response()->json($actors);
    }

    /**
     * Store a newly created actor in storage.
     */

    /**
     * @OA\Post(
     *     path="/api/actors",
     *     summary="Store a newly created actor in storage",
     *     tags={"Actors"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass actor data",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="genre", type="string"),
     *             @OA\Property(property="birth_date", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actor created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="actor", type="object", ref="#/components/schemas/Actor"),
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
        $actor = new Actor;
        $actor->name = $request->name;
        $actor->genre = $request->genre;
        $actor->birth_date = $request->birth_date;

        // validations
        $request->validate([
            'name' => 'required|string',
            'genre' => ['required', Rule::in(Genre::getGenreOptions())],
            'birth_date' => 'required|date'
        ]);

        $actor->save();

        $data = [
            'message' => 'Actor created successfully',
            'actor' => $actor
        ];

        return response()->json($data);
    }

    /**
     * Display the specified actor.
     */

    /**
     * @OA\Get(
     *     path="/api/actors/{actor}",
     *     summary="Display the specified actor",
     *     tags={"Actors"},
     *     @OA\Parameter(
     *         name="actor",
     *         in="path",
     *         description="Actor ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actor details",
     *         @OA\JsonContent(
     *             @OA\Property(property="actor", type="object", ref="#/components/schemas/Actor"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Actor not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     * )
     */

    public function show(Actor $actor)
    {
        if (!$actor) {
            return response()->json([
                'message' => 'Actor not found'
            ], 404);
        }
        $actor = $actor->transformFull();
        return response()->json($actor);
    }

    /**
     * Update the specified actor in storage.
     */

   /**
     * @OA\Put(
     *     path="/api/actors/{actor}",
     *     summary="Update the specified actor in storage",
     *     tags={"Actors"},
     *     @OA\Parameter(
     *         name="actor",
     *         in="path",
     *         description="Actor ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass actor data",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="genre", type="string"),
     *             @OA\Property(property="birth_date", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actor updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="actor", type="object", ref="#/components/schemas/Actor"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Actor not found",
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

    public function update(Request $request, Actor $actor)
    {
        $actor->name = $request->name;
        $actor->genre = $request->genre;
        $actor->birth_date = $request->birth_date;
        
        // validations
        $request->validate([
            'name' => 'string',
            'genre' => ['required', Rule::in(Genre::getGenreOptions())],
            'birth_date' => 'date'
        ]);

        // check if actor exists
        if (!$actor) {
            return response()->json([
                'message' => 'Actor not found'
            ], 404);
        }
        
        $actor->save();

        $data = [
            'message' => 'Actor updated successfully',
            'actor' => $actor
        ];

        return response()->json($data);
    }

    /**
     * Remove the specified actor from storage.
     */

     /**
     * @OA\Delete(
     *     path="/api/actors/{actor}",
     *     summary="Remove the specified actor from storage",
     *     tags={"Actors"},
     *     @OA\Parameter(
     *         name="actor",
     *         in="path",
     *         description="Actor ID",
     *         required=true,
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Actor deleted successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="actor", type="object", ref="#/components/schemas/Actor"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Actor not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Actor has movies or tv shows",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     * )
     */

    public function destroy(Actor $actor)
    {
        // check if actor exists
        if (!$actor) {
            return response()->json([
                'message' => 'Actor not found'
            ], 404);
        }

        //check if actor has movies or tv shows
        if ($actor->movies()->count() > 0 || $actor->tvShows()->count() > 0) {
            return response()->json([
                'message' => 'Actor has movies or tv shows'
            ], 422);
        }

        $actor->delete();

        $data = [
            'message' => 'Actor deleted successfully',
            'actor' => $actor
        ];

        return response()->json($data);
    }
}

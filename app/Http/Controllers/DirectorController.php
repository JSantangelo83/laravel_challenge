<?php

namespace App\Http\Controllers;

use App\Models\Director;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Enums\Genre;

/**
 * @OA\Tag(
 *    name="Directors",
 *   description="API Endpoints of Directors Controller"
 * )
 */

class DirectorController extends Controller
{
    /**
     * Display a listing of the directors.
     */

         /**
     * @OA\Get(
     * path="/api/directors",
     * summary="Display a listing of the directors",
     * tags={"Directors"},
     * @OA\Response(
     * response=200,
     * description="A listing of the directors",
     * @OA\JsonContent(
     * @OA\Property(property="directors", type="object", ref="#/components/schemas/Director"),
     * ),
     * ),
     * ),
     * ),
     * 
     */
    public function index()
    {
        $directors = Director::all();
        return response()->json($directors);
    }

    /**
     * Store a newly created director in storage.
     */

    /**
     * @OA\Post(
     *     path="/api/directors",
     *     summary="Store a newly created resource in storage",
     *     tags={"Directors"},
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass director data",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="genre", type="string"),
     *             @OA\Property(property="birth_date", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Director created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="director", type="object", ref="#/components/schemas/Director"),
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
        $director = new Director;
        $director->name = $request->name;
        $director->genre = $request->genre;
        $director->birth_date = $request->birth_date;

        // validations
        $request->validate([
            'name' => 'required|string',
            'genre' => ['required', Rule::in(Genre::getGenreOptions())],
            'birth_date' => 'required|date'
        ]);

        $director->save();

        $data = [
            'message' => 'Director created successfully',
            'director' => $director
        ];

        return response()->json($data);
    }

    /**
     * Display the specified director.
     */

    /**
     * @OA\Get(
     * path="/api/directors/{director}",
     * summary="Display the specified director",
     * tags={"Directors"},
     * @OA\Parameter(
     * name="director",
     * required=true,
     * in="path",
     * description="Director ID",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Director details",
     * @OA\JsonContent(
     * @OA\Property(property="director", type="object", ref="#/components/schemas/Director"),
     * ),
     * ),
     * @OA\Response(
     * response=404,
     * description="Director not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * ),
     * ),
     * ),
     * ),
     */

    public function show(Director $director)
    {
        if (!$director) {
            return response()->json([
                'message' => 'Director not found'
            ], 404);
        }
        $director = $director->transformFull();
        return response()->json($director);
    }

    /**
     * Update the specified director in storage.
     */
    
    /**
     * @OA\Put(
     *     path="/api/directors/{director}",
     *     summary="Update the specified director in storage",
     *     tags={"Directors"},
     *     @OA\Parameter(
     *         name="director",
     *         required=true,
     *         in="path",
     *         description="Director ID",
     *         @OA\Schema(
     *             type="integer"
     *         )
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         description="Pass director data",
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="genre", type="string"),
     *             @OA\Property(property="birth_date", type="string"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Director updated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *             @OA\Property(property="director", type="object", ref="#/components/schemas/Director"),
     *         ),
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Director not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string"),
     *         ),
     *     ),
     * )
     */

    
    public function update(Request $request, Director $director)
    {
        //check if director exists
        if (!$director) {
            return response()->json([
                'message' => 'Director not found'
            ], 404);
        }

        $director->name = $request->name;
        $director->genre = $request->genre;
        $director->birth_date = $request->birth_date;

        // validations
        $request->validate([
            'name' => 'string',
            'genre' => Rule::in(Genre::getGenreOptions()),
            'birth_date' => 'date'
        ]);
        
        $director->save();

        $data = [
            'message' => 'Director updated successfully',
            'director' => $director
        ];

        return response()->json($data);
    }

    /**
     * Remove the specified director from storage.
     */

    /**
     * @OA\Delete(
     * path="/api/directors/{director}",
     * summary="Remove the specified director from storage",
     * tags={"Directors"},
     * @OA\Parameter(
     * name="director",
     * required=true,
     * in="path",
     * description="Director ID",
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Director deleted successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * @OA\Property(property="director", type="object", ref="#/components/schemas/Director"),
     * ),
     * ),
     * @OA\Response(
     * response=404,
     * description="Director not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * ),
     * ),
     * @OA\Response(
     * response=403,
     * description="Director has movies or tv shows or episodes",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * ),
     * ),
     * ),
     */

    public function destroy(Director $director)
    {
        //check if director exists
        if (!$director) {
            return response()->json([
                'message' => 'Director not found'
            ], 404);
        }

        //check if director has movies or tv shows or episodes
        if ($director->movies->count() > 0 || $director->tvShows->count() > 0 || $director->episodes->count() > 0) {
            return response()->json([
                'message' => 'Director has movies or tv shows or episodes'
            ], 403);
        }

        $director->delete();

        $data = [
            'message' => 'Director deleted successfully',
            'director' => $director
        ];

        return response()->json($data);
    }
}

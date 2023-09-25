<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Director;
use App\Models\AgeClassification;
use App\Enums\Genre;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\Actor;

/**
 * @OA\Tag(
 *    name="Movies",
 *   description="API Endpoints of Movies Controller"
 * )
 */

class MovieController extends Controller
{
    /**
     * Display a listing of the movie.
     */

         /**
     * @OA\Get(
     * path="/api/movies",
     * summary="Display a listing of the movie",
     * tags={"Movies"},
     * @OA\Response(
     * response=200,
     * description="A listing of the movie",
     * @OA\JsonContent(
     * @OA\Property(property="movies", type="object", ref="#/components/schemas/Movie")
     * ),
     * ),
     * ),
     * 
     */
    public function index()
    {
        $genre = request()->query('genre');
        $sort = request()->query('sort', 'title');

        $query = Movie::query();

        if ($genre) $query->where('genre', $genre);

        $movies = $query->orderBy($sort)->get();

        return response()->json($movies);
    }

    /**
     * Store a newly created movie in storage.
     */

    /**
     * @OA\Post(
     *      path="/api/movies",
     *      summary="Store a newly created movie in storage",
     *      tags={"Movies"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass movie data",
     *          @OA\JsonContent(
     *              @OA\Property(property="title", type="string"),
     *              @OA\Property(property="release_date", type="string"),
     *              @OA\Property(property="synopsis", type="string"),
     *              @OA\Property(property="rating", type="integer"),
     *              @OA\Property(property="length", type="string"),
     *              @OA\Property(property="genre", type="string"),
     *              @OA\Property(property="director_id", type="integer"),
     *              @OA\Property(property="age_classification_id", type="integer"),
     *              @OA\Property(property="actors", type="array", @OA\Items(type="integer")),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="A newly created movie",
     *          @OA\JsonContent(
     *              @OA\Property(property="movie", type="object", ref="#/components/schemas/Movie")
     *          ),
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Director not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      )
     * )
     */
    public function store(Request $request)
    {
        $movie = new Movie;
        $movie->title = $request->title;
        $movie->release_date = $request->release_date;
        $movie->synopsis = $request->synopsis;
        $movie->rating = $request->rating;
        $movie->length = $request->length;
        $movie->genre = $request->genre;

        // validations
        $request->validate([
            'title' => 'required|string',
            'release_date' => 'required|date',
            'synopsis' => 'required|string',
            'rating' => 'required|integer',
            'length' => 'required|time',
            'genre' => ['required', Rule::in(Genre::getGenreOptions())],
            'director_id' => 'required|integer',
            'age_classification_id' => 'required|integer',
            'actors' => 'require|array'
        ]);
        
        // check if director exists
        $director = Director::find($request->director_id);
        if (!$director) return response()->json(['message' => 'Director not found'], 404);
        else $movie->director()->associate($director);

        // check if age classification exists
        $ageClassification = AgeClassification::find($request->age_classification_id);
        if (!$ageClassification) return response()->json(['message' => 'Age classification not found'], 404);
        else $movie->ageClassification()->associate($ageClassification);

        // check if actors exists
        $actors = $request->input("actors");
        foreach ($actors as $actor) {
            $actor = Actor::find($actor);
            if (!$actor) return response()->json(['message' => 'Actor not found'], 404);
        }

        $movie->save();

        // attach actors to movie
        $movie->actors()->attach($actors);

        $data = [
            'message' => 'Movie created successfully',
            'movie' => $movie
        ];

        return response()->json($data);
        
    }

    /**
     * Display the specified movie.
     */


    /**
     * @OA\Get(
     * path="/api/movies/{movie}",
     * summary="Display the specified movie",
     * tags={"Movies"},
     * @OA\Parameter(
     * name="movie",
     * in="path",
     * description="Movie id",
     * required=true,
     * @OA\Schema(
     * type="integer"
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="The specified movie",
     * @OA\JsonContent(
     * @OA\Property(property="movie", type="object", ref="#/components/schemas/Movie")
     * ),
     * ),
     * @OA\Response(
     * response=404,
     * description="Not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string")
     * ),
     * ),
     * ),
     */

    public function show(Movie $movie)
    {
        if (!$movie) {
            return response()->json([
                'message' => 'Movie not found'
            ], 404);
        }
        $movie = $movie->transformFull();
        return response()->json($movie);
    }

    /**
     * Update the specified movie in storage.
     */

     /**
     * @OA\Put(
     *      path="/api/movies/{movie}",
     *      summary="Update the specified movie in storage",
     *      tags={"Movies"},
     *      @OA\Parameter(
     *          name="movie",
     *          in="path",
     *          description="Movie id",
     *          required=true,
     *          @OA\Schema(
     *              type="integer"
     *          )
     *      ),
     *      @OA\RequestBody(
     *          required=true,
     *          description="Pass movie data",
     *          @OA\JsonContent(
     *              @OA\Property(property="title", type="string"),
     *              @OA\Property(property="release_date", type="string"),
     *              @OA\Property(property="synopsis", type="string"),
     *              @OA\Property(property="rating", type="integer"),
     *              @OA\Property(property="length", type="string"),
     *              @OA\Property(property="genre", type="string"),
     *              @OA\Property(property="director_id", type="integer"),
     *              @OA\Property(property="age_classification_id", type="integer"),
     *              @OA\Property(property="actors", type="array", @OA\Items(type="integer")),
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="The specified movie",
     *          @OA\JsonContent(
     *              @OA\Property(property="movie", type="object", ref="#/components/schemas/Movie")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Director not found",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Validation error",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string")
     *          )
     *      )
     * )
     */


    public function update(Request $request, Movie $movie)
    {
        //check if movie exists
        if (!$movie) return response()->json(['message' => 'Movie not found'], 404);


        $movie->title = $request->title;
        $movie->release_date = $request->release_date;
        $movie->synopsis = $request->synopsis;
        $movie->rating = $request->rating;
        $movie->length = $request->length;
        $movie->genre = $request->genre;

        // validations
        $request->validate([
            'title' => 'string',
            'release_date' => 'date',
            'synopsis' => 'string',
            'rating' => 'integer',
            'length' => 'time',
            'genre' => Rule::in(Genre::getGenreOptions()),
            'director_id' => 'integer',
            'age_classification_id' => 'integer',
            'actors' => 'array'
        ]);

        // check if director exists
        if ($request->director_id){
            $director = Director::find($request->director_id);
            if (!$director) return response()->json(['message' => 'Director not found'], 404);
            else $movie->director()->associate($director);
        }
 
        // check if age classification exists
        if ($request->age_classification_id){
            $ageClassification = AgeClassification::find($request->age_classification_id);
            if (!$ageClassification) return response()->json(['message' => 'Age classification not found'], 404);
            else $movie->ageClassification()->associate($ageClassification);
        }

        // check if actors exists
        if ($request->actors){
            $actors = $request->input("actors");
            foreach ($actors as $actor) {
                $actor = Actor::find($actor);
                if (!$actor) return response()->json(['message' => 'Actor not found'], 404);
            }
            $movie->actors()->sync($request->actors);
        }

        $data = [
            'message' => 'Movie updated successfully',
            'movie' => $movie
        ];

        return response()->json($data);
    }

    /**
     * Remove the specified movie from storage.
     */

    /**
     * @OA\Delete(
     * path="/api/movies/{movie}",
     * summary="Remove the specified movie from storage",
     * tags={"Movies"},
     * @OA\Parameter(
     * name="movie",
     * in="path",
     * description="Movie id",
     * required=true,
     * @OA\Schema(
     * type="integer"
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Removed specified movie",
     * @OA\JsonContent(
     * @OA\Property(property="movie", type="object", ref="#/components/schemas/Movie"),
     * ),
     * ),
     * @OA\Response(
     * response=404,
     * description="Not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * ),
     * ),
     * )
     */

    public function destroy(Movie $movie)
    {
        //check if movie exists
        if (!$movie) return response()->json(['message' => 'Movie not found'], 404);

        $movie->delete();

        $data = [
            'message' => 'Movie deleted successfully',
            'movie' => $movie
        ];

        return response()->json($data);
    }
}

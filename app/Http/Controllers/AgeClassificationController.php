<?php

namespace App\Http\Controllers;

use App\Models\AgeClassification;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 * name="Age Classification",
 * description="API Endpoints of Age Classification Controller"
 * )
 */

class AgeClassificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    /**
     * @OA\Get(
     * path="/api/age-classifications",
     * summary="Display a listing of the resource",
     * tags={"Age Classification"},
     * @OA\Response(
     * response=200,
     * description="A listing of the resource",
     * @OA\JsonContent(
     * @OA\Property(property="ageClassifications", type="object", ref="#/components/schemas/AgeClassification"),
     * ),
     * ),
     * ),
     * ),
     * 
     */
    public function index()
    {
        $ageClassifications = AgeClassification::all();
        return response()->json($ageClassifications);
    }

    /**
     * Store a newly created resource in storage.
     */

    /**
     * @OA\Post(
     * path="/api/age-classifications",
     * summary="Store a newly created resource in storage",
     * tags={"Age Classification"},
     * @OA\RequestBody(
     *  required=true,
     * description="Pass age classification data",
     *  
     * @OA\JsonContent(
     * @OA\Property(property="description", type="string"),
     * @OA\Property(property="code", type="string"),
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Age Classification created successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * @OA\Property(property="ageClassification", type="object", ref="#/components/schemas/AgeClassification"),
     * ),
     * ),
     * ),
     * ),
     * 
     */

    public function store(Request $request)
    {
        $ageClassification = new AgeClassification;
        $ageClassification->description = $request->description;
        $ageClassification->code = $request->code;
        $ageClassification->save();

        $data = [
            'message' => 'Age Classification created successfully',
            'ageClassification' => $ageClassification
        ];

        return response()->json($data);
    }

    /**
     * Display the specified resource.
     */

    /**
     * @OA\Get(
     * path="/api/age-classifications/{ageClassification}",
     * summary="Display the specified resource",
     * tags={"Age Classification"},
     * @OA\Parameter(
     * name="ageClassification",
     * in="path",
     * description="Age Classification ID",
     * required=true,
     * @OA\Schema(
     * type="integer",
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Age Classification details",
     * @OA\JsonContent(
     * @OA\Property(property="ageClassification", type="object", ref="#/components/schemas/AgeClassification"),
     * ),
     * ),
     * 
     * @OA\Response(
     * response=404,
     * description="Age Classification not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * ),
     * ),
     * ),
     * ),
     * 
     */

    public function show(AgeClassification $ageClassification)
    {
        if (!$ageClassification) {
            return response()->json([
                'message' => 'Age Classification not found'
            ], 404);
        }

        return response()->json($ageClassification);
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Put(
     * path="/api/age-classifications/{ageClassification}",
     * summary="Update the specified resource in storage",
     * tags={"Age Classification"},
     * @OA\Parameter(
     * name="ageClassification",
     * in="path",
     * description="Age Classification ID",
     * required=true,
     * @OA\Schema(
     * type="integer",
     * ),
     * ),
     * @OA\RequestBody(
     *  required=true,
     * description="Pass age classification data",
     *  
     * @OA\JsonContent(
     * @OA\Property(property="description", type="string"),
     * @OA\Property(property="code", type="string"),
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Age Classification updated successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * @OA\Property(property="ageClassification", type="object", ref="#/components/schemas/AgeClassification"),
     * ),
     * ),
     * @OA\Response(
     * response=404,
     * description="Age Classification not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * ),
     * ),
     * ),
     * ),
     */

    public function update(Request $request, AgeClassification $ageClassification)
    {
        // check if age classification exists
        if (!$ageClassification) {
            return response()->json([
                'message' => 'Age Classification not found'
            ], 404);
        }

        $ageClassification->description = $request->description;
        $ageClassification->code = $request->code;
        $ageClassification->save();

        $data = [
            'message' => 'Age Classification updated successfully',
            'ageClassification' => $ageClassification
        ];

        return response()->json($data);
    }

    /**
     * Remove the specified resource from storage.
     */

    /**
     * @OA\Delete(
     * path="/api/age-classifications/{ageClassification}",
     * summary="Remove the specified resource from storage",
     * tags={"Age Classification"},
     * @OA\Parameter(
     * name="ageClassification",
     * in="path",
     * description="Age Classification ID",
     * required=true,
     * @OA\Schema(
     * type="integer",
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Age Classification deleted successfully",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * @OA\Property(property="ageClassification", type="object", ref="#/components/schemas/AgeClassification"),
     * 
     * ),
     * ),
     * 
     * @OA\Response(
     * response=404,
     * description="Age Classification not found",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * ),
     * ),
     * 
     * @OA\Response(
     * response=403,
     * description="Age Classification cannot be deleted because it has movies or tv shows",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string"),
     * ),
     * ),
     * ),
     * ),
     * 
     */
    public function destroy(AgeClassification $ageClassification)
    {
        //check if age classification exists
        if (!$ageClassification) {
            return response()->json([
                'message' => 'Age Classification not found'
            ], 404);
        }

        // check if age classification has movies
        $movies = $ageClassification->movies;
        if (sizeof($movies) > 0) {
            return response()->json([
                'message' => 'Age Classification cannot be deleted because it has movies'
            ], 403);
        }
        // check if age classification has tv shows
        $tvShows = $ageClassification->tvShows;
        if (sizeof($tvShows) > 0) {
            return response()->json([
                'message' => 'Age Classification cannot be deleted because it has tv shows'
            ], 403);
        }

        $ageClassification->delete();

        $data = [
            'message' => 'Age Classification deleted successfully',
            'ageClassification' => $ageClassification
        ];

        return response()->json($data);
    }
}

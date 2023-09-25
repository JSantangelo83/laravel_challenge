<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/** 
 * @OA\Schema(
 * title="AgeClassification",
 * description="AgeClassification model",
 * @OA\Property(
 * property="id",
 * type="integer",
 * description="AgeClassification id",
 * example="1"
 * ),
 * @OA\Property(
 * property="code",
 * type="string",
 * description="AgeClassification code",
 * example="G"
 * ),
 * @OA\Property(
 * property="description",
 * type="string",
 * description="AgeClassification description",
 * example="General Audiences"
 * )
 * )
 * 
 */

class AgeClassification extends Model
{
    use HasFactory;

    public function transformFull(){
        return [
            'id' => $this->id,
            'code' => $this->code,
            'description' => $this->description
        ];
    }

    public function transformSimple(){
        return [
            'id' => $this->id,
            'code' => $this->code
        ];
    }
}
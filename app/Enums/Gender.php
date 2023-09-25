<?php

namespace App\Enums;

/**
 * @OA\Schema(
 *     schema="GenderEnum",
 *     type="string",
 *     enum={"App\Enums\Gender::MALE", "App\Enums\Gender::FEMALE"},
 *     description="Gender options: Male, Female"
 * )
 */

enum Gender: string {
    case MALE = 'M';
    case FEMALE = 'F';
    
    public static function getGenderOptions(){
        return [
            Gender::MALE,
            Gender::FEMALE,
        ];
    }

    public function getDescription(){
        return match($this) {
            Gender::MALE => 'Male',
            Gender::FEMALE => 'Female',
        };
    }
}
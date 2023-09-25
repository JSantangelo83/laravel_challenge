<?php

namespace App\Enums;
/**
 * @OA\Schema(
 *     schema="GenreEnum",
 *     type="string",
 *     enum={"App\Enums\Genre::ACTION", "App\Enums\Genre::COMEDY", "App\Enums\Genre::DRAMA", "App\Enums\Genre::FANTASY", "App\Enums\Genre::HORROR", "App\Enums\Genre::MYSTERY", "App\Enums\Genre::ROMANCE", "App\Enums\Genre::SCIENCEFICTION", "App\Enums\Genre::THRILLER"},
 *     description="Genre options: Action, Comedy, Drama, Fantasy, Horror, Mystery, Romance, Science Fiction, Thriller"
 * )
 */

enum Genre: string {
    case ACTION = 'AC';
    case COMEDY = 'CO';
    case DRAMA = 'DR';
    case FANTASY = 'FA';
    case HORROR = 'HO';
    case MYSTERY = 'MY';
    case ROMANCE = 'RO';
    case SCIENCEFICTION = 'SF';
    case THRILLER = 'TH';

    public static function getGenreOptions(){
        return [
            Genre::ACTION,
            Genre::COMEDY,
            Genre::DRAMA,
            Genre::FANTASY,
            Genre::HORROR,
            Genre::MYSTERY,
            Genre::ROMANCE,
            Genre::SCIENCEFICTION,
            Genre::THRILLER,
        ];
    }

    public function getDescription(){
        return match($this) {
            Genre::ACTION => 'Action',
            Genre::COMEDY => 'Comedy',
            Genre::DRAMA => 'Drama',    
            Genre::FANTASY => 'Fantasy',
            Genre::HORROR => 'Horror',
            Genre::MYSTERY => 'Mystery',
            Genre::ROMANCE => 'Romance',
            Genre::SCIENCEFICTION => 'Science Fiction',
            Genre::THRILLER => 'Thriller',
        };
    }
}


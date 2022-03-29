<?php

namespace App\ViewModels;

use Spatie\ViewModels\ViewModel;

class MovieViewModel extends ViewModel
{
    public $movie;

    public function __construct($movie)
    {
        $this->movie = $movie;
    }

    public function movie()
    {
        return collect($this->movie)->merge([
            'poster_path' => 'https://image.tmdb.org/t/p/w500' . $this->movie['poster_path'],
            'vote_average' => $this->movie['vote_average'] * 10 . '%',
            'release_date' => \Carbon\Carbon::parse($this->movie['release_date'])->format('M d, Y'),
            'genres' => collect($this->movie['genres'])->pluck('name')->flatten()->implode(', '),
            'crew' => collect($this->movie['credits']['crew'])->take(2),
            'images' => collect($this->movie['images']['backdrops'])->take(9),
            'cast' => collect($this->movie['credits']['cast'])->take(5)
        ])->only([
            'poster_path', 'id', 'title', 'vote_average', 'release_date', 'genre_ids', 'overview', 'genres', 'credits', 'videos', 'images', 'crew', 'cast',
        ]);
    }


    private function actorNames()
    {
        return collect($this->movie['credits']['cast'])->pluck('name');
    }
}

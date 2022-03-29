<?php

namespace App\ViewModels;

use Carbon\Carbon;
use Spatie\ViewModels\ViewModel;

class MoviesViewModel extends ViewModel
{

    public $popularMovies;
    public $nowPlayingMovies;
    public $genres;

    public function __construct($popularMovies, $nowPlayingMovies, $genres)
    {
        $this->popularMovies = $popularMovies;
        $this->nowPlayingMovies = $nowPlayingMovies;
        $this->genres = $genres;
    }

    public function popularMovies()
    {
        return $this->formatMovies($this->popularMovies);
    }

    public function nowPlayingMovies()
    {
        return $this->formatMovies($this->nowPlayingMovies);

    }

    public function genres()
    {
        // @foreach($movie['genre_ids'] as $genre){{ $genres->get($genre) }}@if(!$loop->last),@endif @endforeach
        return collect($this->genres)->mapWithKeys(function ($genre) {
            return [$genre['id'] => $genre['name']];
        });
    }

    private function formatMovies($movies)
    {
        return collect($movies)->map(function ($movie) {

            $genresFormatted = collect($movie['genre_ids'])->mapWithKeys(function ($value) {
                return [$value => $this->genres()->get($value)];
            })->implode(', ');

            return collect($movie)->merge([
                'poster_path' => $movie['poster_path']
                    ? 'https://image.tmdb.org/t/p/w500' . $movie['poster_path']
                    : 'https://ui-avatars.com/api/?size=w500&name=' . $movie['title'],
                'vote_average' => $movie['vote_average'] * 10 . '%',
                'release_date' => \Carbon\Carbon::parse($movie['release_date'])->format('M d, Y'),
                'genres' => $genresFormatted
            ])->only([
                'poster_path', 'id', 'title', 'vote_average', 'release_date', 'genre_ids', 'overview', 'genres'
            ]);
        });
    }
}

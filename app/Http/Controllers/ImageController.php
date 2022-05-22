<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class ImageController extends BaseController
{
    const API_MAIN_PHOTOS_URL = 'http://jsonplaceholder.typicode.com/photos';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Session::has('images')) {
            $data = Http::get(
                self::API_MAIN_PHOTOS_URL
            )->json();

            Session::put('images', $data);
        }

        return $this->sendResponse($this->getImagesData(), 'User Favourites');
    }

    private function getImagesData(): array
    {
        $images = Session::get('images');
        if (empty($images)) {
            return [];
        }

        /** @var User $user */
        $user = Auth::user();
        if ($user) {
            return $images;
        }

        //Prepare for guests
        $dayOfTheWeek = Carbon::now()->dayOfWeek;
        //Sunday and Saturday
        if (0 === $dayOfTheWeek || 6 === $dayOfTheWeek) {
            $allFavourites = User::all(['favourites'])->toArray();
            $favouriteData = [];
            foreach ($allFavourites as $favourites) {
                $favouriteData = array_merge($favouriteData, json_decode($favourites['favourites'], true) ?? []);
            }

            $favouriteData = array_unique(array_values($favouriteData));
            $favouriteImages = array_filter($images, function ($image) use ($favouriteData) {
                return in_array($image['id'], $favouriteData);
            });

            return  array_values($favouriteImages);
        } else {
            $allUsersCounts = User::all()->sortByDesc('fav_count')->reject(function ($user) {
                return $user->fav_count === 0;
            });

            return $allUsersCounts->toArray();
        }
    }
}

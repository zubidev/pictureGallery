<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserController extends BaseController
{

    /**
     * Get authenticated user.
     */
    public function current(Request $request)
    {
        return response()->json($request->user());
    }

    public function getFavourites()
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        if (!$user) {
            return $this->sendError("No User is logged in");
        }

        return $this->sendResponse($user->getFavourites(), 'User favourites');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        if (!$user) {
            return $this->sendError("No User is logged in");
        }

        if ($request->has('isFav') && $id) {
            $isFavourite = $request->get('isFav');
            if ($isFavourite) {
                $user->setFavourite($id);
            } else {
                $user->removeFavourite($id);
            }
            $user->setFavCount();
            $user->save();
        }

        return $this->sendResponse($user, 'User Information has been updated');
    }
}

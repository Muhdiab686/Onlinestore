<?php

namespace App\Http\Controllers;

use App\Models\Favorite;
use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class FavoritelistController extends BaseController
{

    public function AddToFavorite(Request $request) {

        $user = auth()->user()->id;
        if(!Medicine::find($request->medicine_id)){
            return $this->sendError('Invalid Id');
        }

        // see if this medicine is Added by this user or not
         $Fav0 = Favorite::where('medicin_id', $request->medicine_id)->where('user_id', $user)->first();
        if ($Fav0) {
            return $this->sendError('medicine have been Add Befor');
        }

        $Fav = Favorite::create([
            'medicin_id' => $request->medicine_id,
            'user_id' => $user
        ]);
        return $this->sendResponse3('medicine added successfully');
    }


    public function RemoveFromFavorite(Request $request,$medicine_id) {

        $user = auth()->user()->id;
        // see if this medicine is Added by this user or not
        $Fav0 = Favorite::where('medicin_id', $request->medicine_id)->where('user_id', $user)->first();

        if ($Fav0==null) {
            return $this->sendError('medicine is not added befor');
        }

        $Fav = Favorite::where('medicin_id', $request->medicine_id)->where('user_id', $user)->first();
        $Fav->delete();

        return $this->sendResponse3('medicine has removed successfully');
    }



    public function showFav()
    {
        $user = auth()->user()->id;
        $Fav = Favorite::where('user_id', $user)->with('medicin')->get();
        return response()->json([
            'data' => $Fav
        ]);
    }




}

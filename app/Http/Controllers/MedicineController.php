<?php

namespace App\Http\Controllers;
use App\Models\Medicine;
use App\Models\Category;
use App\Models\User;
use App\Models\Favoritelist;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\AuthController;

use Illuminate\Http\Request;

class MedicineController extends BaseController
{
    public function create_Medecine (Request $request){
    $rules = [
        'scientific_name' => ['required', 'string', 'max:255'],
        'commercial_name' => ['required', 'string', 'max:255'],
        'manufacture_company'=>['required', 'string', 'max:255'],
        'quantity' => ['required', 'numeric', 'min:1'],
        'expiry_date'=>'required',
        'price' => ['required', 'numeric', 'min:1'],
        'category_id' => ['required', 'numeric'],
    ];

    $validator = Validator::make($request->all(),$rules);

    if ($validator->fails()) {
        return $this->sendError('pleas check vlidate',$validator->errors()->toArray());
    }


    if(!Category::find($request->category_id)){
        return $this->sendError('this category is not found ');
    }


    $user =auth()->user()->id;
    $scientific_name = $request->input('scientific_name');
    $commercial_name = $request->input('commercial_name');
    $manufacture_company = $request->input('manufacture_company');
    $quantity = $request->input('quantity');
    $expiry_date = $request->input('expiry_date');
    $price = $request->input('price');
    $category_id = $request->input('category_id');

    $medecine = Medicine::create([
        'scientific_name' => $scientific_name,
        'commercial_name' => $commercial_name,
        'manufacture_company' => $manufacture_company,
        'quantity' => $quantity,
        'expiry_date' => $expiry_date,
        'price' => $price,
        'category_id' => $category_id,
        'user_id' => $user,
    ]);
    return $this->sendResponse2($medecine,'creating successfully');
    }

    public function search_name($name)
    {
        
     $category = Category::where('name', 'LIKE', '%'. $name. '%')->first();
     $medicine = Medicine::where('commercial_name' ,'LIKE', '%'. $name. '%')->first();
        if($category){
            $name_Cat = Category::where('name', 'LIKE', '%'.$name. '%')->with('medicin')->get();
            return $this->sendResponse2('found Category successfully',$name_Cat);
        }
        if($medicine){
            $name_Med = Medicine::where('commercial_name', 'LIKE', '%'.$name. '%')->with('category')->get();
            return $this->sendResponse2('found Category successfully',$name_Med);
        }
        else{
            return $this->sendError('No Data not found');
        }
    }
    public function showsingle(Request $request, $id)
    {
        $medicine = Medicine::find($id);
        if (!$medicine) {
            return response()->json([
                'success' => '0',
                'message' => 'invalid id',
                'data'=> $medicine,
            ], 404);
        }
        $medicine = $medicine->with('category')->get();
        return $this->sendResponse2('found Category successfully',$medicine);
    }
}

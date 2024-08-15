<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends BaseController
{
    public function store(Request $request)
    {

        $rules = [
            'name' => ['required','string', 'max:255'],
        ];

        $input['name'] = $request->input('name');

        $category = Category::create([
            'name' => $input['name'],
        ]);
        return $this->sendResponse2($category,'creating successfully');
    }

    public function show()
    {
        $categoryQuery = Category::with('medicin')->get();
        return $this->sendResponse2($categoryQuery,'this is Type ');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => ['string', 'max:255'],
        ];

        $input['name'] = $request->input('name');
        $validator = Validator::make($input, $rules);
        if($validator->fails()) {
            return $this->sendError('Please validate error',$validator->errors()->toArray());
        }

        $category = Category::find($id);
        if ($input['name'])
            $category->update([
                'name' => $input['name']
            ]);
        return $this->sendResponse2($category,'updating successfully');
    }

    public function destroy(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) {
            return $this->sendError('Invalid Id');
        }
        $category->medicin()->delete();
        $category->delete();
        return $this->sendResponse2($category,'Destoying successfully');
    }
}

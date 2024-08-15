<?php

namespace App\Http\Controllers;

use App\Models\ordar;
use App\Models\Medicine;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class orderController extends BaseController
{
    public function createorder(Request $request)
    {
        $rules = [
            'name' => ['required'],
            'quantity' => ['required'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->sendError('please validate error', $validator->errors()->toArray());
        }
        
        $Medicine = DB::table('medicines')->where('commercial_name',$name)->get();
        foreach($Medicine as $t){
            if($t->commercial_name==$request->name)
            {
                $ii= $t->id;
            }
        }
        if (!$Medicine) {
            return $this->sendError('this Medicine is not found');
        }
        $user =auth()->user()->id;
        $ordar = new Ordar();
        $ordar->user_id = $user;
        $ordar->Medicin_id = $ii;
        $ordar->name = $request->name;
        $ordar->quantity = $request->quantity;
        $ordar->Payment_status = 'Unpaid';
        $ordar->save();
        return $this->sendResponse1($ordar, 'ordar created successfully');
    }

    public function showordar(Request $request){

        $show_ord = Ordar::where('user_id',Auth()->user()->id)->get();
        return $this->sendResponse1($show_ord, 'ordar created successfully');
    }

    public function showordars(Request $request){

        $show_ord = Ordar::with('user')->get();
        return $this->sendResponse1($show_ord, 'ordar created successfully');
    }

    public function In_preparation(Request $request,$ord_id){
        $getord = Ordar::where('id',$ord_id)->with('user')->get();
        foreach($getord as $t){
            $name = $t->name;
            $quantity_ord = $t->quantity;     
        } 

        $Medicine = Medicine::where('commercial_name',$name)->get();
        foreach($Medicine as $t){
            $quantity_med = $t->quantity;
        }
        if ($quantity_ord << $quantity_med){
            $ord = Ordar::where('id',$ord_id)->first();
            $ord = $ord->update([
                $ord->status = 'In_prepartion'
            ]);
            return $this->sendResponse1($ord, 'ordar In_prepartion successfully');
        }else{
            return $this->sendError('quantity is not found');
        }
    }

    public function Has_sent(Request $request,$ord_id){
        $getord = Ordar::where('id',$ord_id)->with('user')->get();
        foreach($getord as $t){
            $state_ord = $t->status;
            $quantity_ord = $t->quantity;
            $name = $t->name;
        }
        if ($state_ord == "In_prepartion"){
            $ord = Ordar::where('id',$ord_id)->first();
            $ord = $ord->update([
                $ord->status = 'Has been sent'
            ]);
            
            $medicine = Medicine::where('commercial_name',$name)->get();
          
            foreach($medicine as $t1){
                $quantity_med = $t1->quantity;
            }
            $quantity_new = $quantity_med - $quantity_ord;
            $medi = Medicine::where('commercial_name',$name)->first();
            $medi = $medi->update([
                $medi->quantity = $quantity_new
            ]);
            return $this->sendResponse1($ord, 'ordar In_prepartion successfully');
        }else{
            return $this->sendError('The ordar needs to be prepared');
        }
    }
    public function IT_Received(Request $request,$ord_id){
        $getord = Ordar::where('id',$ord_id)->with('user')->get();
        foreach($getord as $t){
            $state_ord = $t->status;
            //dd($state_ord);
            $Pay_state = $t->Payment_status ;
        }
        if ($state_ord == "Has been sent"){
            $ord = Ordar::where('id',$ord_id)->first();
           // dd($ord);
            $ord = $ord->update([
                $ord->status = 'Received',
                $ord->Payment_status = 'paid'
            ]);
            return $this->sendResponse1($ord, 'ordar Received And Paid successfully');
        }else{
            return $this->sendError('The ordar needs to be prepared');
        }
    }
}
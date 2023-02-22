<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShopRequest;
use App\Models\Shop;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    public $success;
    public $error;
    public function __construct()
    {
        $this->success = response()->json([
            'message' => 'Shop updated successfully',
        ], 201);
        $this->error = response()->json([
            'message' => 'Error',
        ], 201);
    }
    /**
     * Validate
     *
     * @return JSON
     */
    public function validator($validator){
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    }
    /**
     * Update Shop
     *
     * @return JSON
     */
    public function UpdateShop(ShopRequest $request){

        $validator = Validator::make($request->all(),[]);

        $this->validator($validator);
        $shop = Shop::where('user_id',Auth::user()->id)->first();


        try{
          $shop = Shop::where('user_id',Auth::user()->id)->update(array_merge(
                $validator->validated(),
                [
                    'images' => $this->updateImages($request,$shop),
                ]
            ));
            var_dump($shop);
        }catch(ModelNotFoundException){
            return $this->error;
        }

        return $this->success;
    }
    /**
     * API Upload Images.
     *
     * @return $imgagesname
     */
    public function updateImages(ShopRequest $request,$shop){
        if($request->images){
            $imagesName = '';

            $images = explode(",", $shop->images);
                foreach ($images as $image) {
                    if ($image) {
                        unlink('assets/storage/media' . '/' . $image);
                    }
                }
            foreach ($request->images as $key => $image) {
                $imgName = Carbon::now()->timestamp . $key . '.' . $image->extension();
                $image->storeAs('media', $imgName);
                $imagesName = $imagesName . ',' . $imgName;
            }
            $new_images = $imagesName;

        }else{
            $new_images = $shop->images;
        }
        return $new_images;
    }
     /**
     * Confirm Account Shop
     *
     * @return JSON
     */
    public function Confirm_shop($token,$email){
        $check = DB::table('confirm_email')->where([
            'token' => $token,
            'email' => $email,
        ]);
        if($check){
            try{
                $userEmail = User::where('email',$email)->first();
                $shop =  Shop::where('user_id',$userEmail->id)->first();
                $shop->status = 1;
                $shop->save();
                $check->delete();
                return redirect()->to('http://127.0.0.1:4200/profile');
            }catch(ModelNotFoundException){
                return $this->error;
            }
        }
    }
}

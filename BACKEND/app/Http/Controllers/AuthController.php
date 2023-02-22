<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Http\Requests\ShopRequest;
use App\Models\Shop;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
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
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request){
    	$validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $this->validator($validator);

        if (! $token = auth()->attempt($validator->validated())) {
            return response()->json(['error' => 'Email or Password Not Right!'], 401);
        }

        return $this->createNewToken($token);
    }

    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register(AuthRequest $request) {
        $validator = Validator::make($request->all(),[]);

        $this->validator($validator);
try{
    $user = User::create(
        [
            'email' => $request->email,
            'name' => $request->fname .' '.$request->lname,
            'phone'=> $request->phone,
            'password' => bcrypt($request->password)
        ]
    );
    $shop = Shop::create(
        [
            'shopname' => $request->shopname,
            'address' => $request->address,
            'hotline' => $request->hotline,
            'taxcode'=> $request->taxcode,
            'user_id' => $user->id,
            'status' => 0,
            'images' => $this->uploadImages($request),
        ]
    );
}catch(ModelNotFoundException){
    return response()->json(['error' => 'Not Right!'], 401);
}

        $this->sendMail($request);

        return $this->login($request);
        // return response()->json(['message' => 'User Created successfully']);
    }

    /**
     * API Send Mail.
     *
     * @return
     */
    public function sendMail(AuthRequest $request){
        $token = $this->createToken($request->email);
        $title_mail = "Thank you for registering to shop at our Website!";
        $data_mail = [];
        $data_mail['email'][]= $request->email;
        $data = array(
            'name' => $request->fname . ' '. $request->lname,
            'phone'=> $request->phone,
            'email'=> $request->email,
            'shopname'=> $request->shopname,
            'address'=> $request->address,
            'hotline'=> $request->hotline,
            'taxcode'=> $request->taxcode,
            'images'=> $request->images,
            'token'=> $token,
            'password' => $request->password,
        );
        Mail::send('Mail.Mail_Confirm',['data'=>$data],function($messages) use ($title_mail,$data_mail){
            $messages->to($data_mail['email'])->subject($title_mail);//send this mail with subject
            $messages->from($data_mail['email'],$title_mail);//send from this mail
        });
    }
    public function createToken($email){
        $oldToken = DB::table('confirm_email')->where('email',$email)->first();
        if($oldToken){
            return $oldToken->token;
        }
        $token = Str::random(40);
        $this->saveToken($token,$email);
        return $token;
    }
    public function saveToken($token,$email){
        DB::table('confirm_email')->insert([
            'email'=> $email,
            'token'=> $token,
        ]);
    }
    /**
     * API Upload Images.
     *
     * @return $imgagesname
     */
    public function uploadImages(AuthRequest $request){
        // $request->file('file')
         $imagesname= '';
         $file = $request->file('images');

         if($file){
            foreach($file as $key => $image){
                $imgName = Carbon::now()->timestamp. $key. '.' . $image->extension();
                $image->storeAs('media',$imgName);
                $imagesname = $imagesname . ',' . $imgName;
            }
           return $imagesname;
        }
        return $imagesname = "error";
    }
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();

        return response()->json(['message' => 'User successfully signed out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        return $this->createNewToken(auth()->refresh());
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function userProfile() {
        return response()->json(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function changePassWord(Request $request) {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required|string|min:6',
            'new_password' => 'required|string|confirmed|min:6',
        ]);

        $this->validator($validator);

        $userId = auth()->user()->id;

        $user = User::find(Auth::user()->id)->first();

        if(Hash::check($request->password,$user->password)){
            return response()->json([
                'message' => 'vui lòng nhập một mật khẩu mới',
            ], 500);
        }
        $user = User::where('id', $userId)->update(
                    ['password' => bcrypt($request->new_password)]
                );

        return response()->json([
            'message' => 'User successfully changed password',
            'user' => $user,
        ], 201);
    }

   
}

<?php

namespace App\Http\Controllers\Dashboard;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiResponse;
use Laravel\Sanctum\HasApiTokens;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\Dashboard\UserResource;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserController extends Controller
{
    use ApiResponse, HasApiTokens, HasFactory, Notifiable;

    //user all list(admin only)
    public function index(){
        $user = User::with(['role'])->paginate(10);

        return $this->successResponse('User retrieved successfully', UserResource::collection($user), 200);
    }

    //user create(admin only)
    public function store(Request $request){
        $validator = Validator::make($request->all(),
        [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6|regex:/[0-9]/|regex:/[a-zA-Z]/',
            'roleId' => 'required'
        ]);

        if($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $request->roleId
            ]);

            return $this->successResponse('User created successfully', new UserResource($user), 201);

        } catch (\Exception $e) {
            return $this->errorResponse('User creation failed:'. $e->getMessage(), 500);
        }
    }

    //user show
    public function show($id){
        $user = User::find($id);

        if(!$user) {
            return $this->errorResponse('User not found.', 404);
        }

        return $this->successResponse('User Fetched Successfully', new UserResource($user), 200);
    }
}

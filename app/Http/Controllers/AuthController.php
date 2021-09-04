<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\FuncCall;
use Exception;
use App\Models\Responsibility;

class AuthController extends Controller
{

    public function Register(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:64',
            'email' => 'required|string|email|max:64|unique:users',
            'password' => 'required|string|max:20|min:6',
            'password_confirm' => 'required|string|max:20|min:6'
        ]);

        if($validatedData['password'] != $validatedData['password_confirm']){
            return response()->json([
                'message' => 'Password not match'
            ]);
        }

        $user = User::create([
            'name' => $validatedData['name'],
            'email' => $validatedData['email'],
            'password' => Hash::make($validatedData['password']),
            'password_confirm' => Hash::make($validatedData['password_confirm'])
        ]);

        return response([
            'user' => $user,
        ], 200);
    }

    public function Login(Request $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Invalid login details'
            ], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        $user->tokens()->delete();
        $token = $user->createToken('auth_token')->plainTextToken;
        return response([
            'token' => $token,
            'token type' => 'Bearer'
        ], 200);

    }

    public function User(Request $request)
    {
        return Auth::user();
    }

    public function Logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response([
            'message' => 'Logout Access!'
        ]);
    }

    public function ListUsers()
    {       
        if (!Gate::allows('is_admin')) {
            throw new Exception("Unauthenticated.");
        }

        return response()->json([
            'message' => 'view listuser Success',
            user::all()
        ]);
    }



    public function GetUser($id)
    {    
        $user = user::find($id);

        if (!Gate::allows('is_admin')) {
            throw new Exception("Unauthenticated.");
        }

        if ($user) {
            return response()->json([
                'message' => 'view user Success !',
                'user' => $user
            ]);
        } else {
            return response()->json(['message' => 'No User !']);
        }
    }


    public function SetUser(Request $request)  
    {

        if (!Gate::allows('is_admin')) {
            throw new Exception("Unauthenticated.");
        }

        $request->validate([
            'id' => 'required|int',
            'name' => 'string|max:255',
            'is_admin' => 'boolean',
            'is_active' => 'boolean',
        ]);


        $id = $request['id'];

        //return response()->json(['message' => $request['is_admin']]);

        $user = user::find($id);
        if ($user) {
            if ($request['name'] != null)
                $user->name = $request['name'];

            if ($request['is_admin'] != null) {
                if ($request['is_admin'] == 0)
                    $user->is_admin = 0;
                else  $user->is_admin = 1;
            }   /// เงื่อนไขต้องใช้ใน put ปรับเปลี่ยนเอา

            if ($request['is_active'] != null) {
                if ($request['is_active'] == 0)
                    $user->is_active = 0;
                else $user->is_active = 1;
            }

            $user->update();

            return response()->json([
                'message' => 'Update Access !',
                'edit by admin email' => Auth::user()->email,
                'user' => $user
            ], 200);

        } else {
            return response()->json([
                'message' => 'Not User !'
            ], 203);
        }
    }

}

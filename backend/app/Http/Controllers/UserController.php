<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function show(string $id)
    {
        $user = User::where('id', $id)
                    ->first();

        return ($user) ? 
               response()->json(['status' => 200, 'user' => $user], 200) : 
               response()->json(['status' => 404, 'message' => 'User Not Found'], 404) ;
    }

    public function update(Request $request, string $id)
    {
        $user = User::where('id', $id)
                    ->first();

        /* VALIDATION USER */        
        if(!$user)
            return response()->json(['status' => 404, 'message' => 'User Not Found'], 404);

        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string'],
            'email' => ['required', 'string', 'max:255', 'email'],
        ]);
        
        if($validator->fails())
            return response()->json(['status' => 422, 'message' => $validator->messages()], 422);
        /* VALIDATION USER */
        
        /* UPDATE USER */
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();
        /* UPDATE USER */

        return response()->json(['status' => 200, 'message' => 'User Update Successfully', 'user' => $user], 200);
    }
}

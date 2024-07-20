<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function deleteImage(Request $request) 
    {
        /* VALIDATION REQUEST AND GET */
        $validator = Validator::make($request->all(), [
            'img' => ['required', 'string']   
        ]);

        if($validator->fails())
            return response()->json(['status' => 422, 'message' => $validator->messages()], 422);

        $validate = $validator->validate();
        /* VALIDATION REQUEST AND GET */

        /* DELETE IMG PREV, IF IMG EXISTS */
        if($validate['img'])
        {
            if(Storage::disk('public')->exists($validate['img'])) 
            {
                /* UPDATE IN DATABASE */
                $user = User::where('img', $validate['img'])
                            ->first();
                $user->img = null;
                $user->save();
                /* UPDATE IN DATABASE */

                Storage::disk('public')->delete($validate['img']);
    
                return response()->json(['status' => 200, 'message' => 'Delete Image Success', 'user' => $user], 200);
            }
        }
        /* DELETE IMG PREV, IF IMG EXISTS */

        return response()->json(['status' => 404, 'message' => 'Delete Image Error, Path File Empty'], 404);
    }

    public function uploadImage(Request $request)
    {
        /* VALIDATION REQUEST */     
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer'],
            'file' => ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:1024']
        ]);

        if($validator->fails())
            return response()->json(['status' => 422, 'message' => $validator->messages()], 422);

        $validate = $validator->validate();
        /* VALIDATION REQUEST */ 

        /* VALIDATION USER EXISTS */
        $user = User::where('id', $validate['id'])
                    ->first();

        if(!$user)
            return response()->json(['status' => 404, 'message' => 'User Not Found'], 404);
        /* VALIDATION USER EXISTS */
        
        /* DELETE IMG PREV, IF IMG EXISTS */
        if($user->img)
            if(Storage::disk('public')->exists($user->img))
                Storage::disk('public')->delete($user->img);
        /* DELETE IMG PREV, IF IMG EXISTS */

        /* UPLOAD IMG AND UPDATE IN DATABASE */   
        $filename = $request->id . "-" . Carbon::now()->timestamp . "." .$request->file('file')->getClientOriginalExtension();
        $path = Storage::disk('public')->putFileAs('user-imgs', $request->file('file'), $filename);
        
        $user->img = $path;
        $user->save();
        /* UPLOAD IMG AND UPDATE IN DATABASE */
        
        return response()->json(['status' => 200, 'message' => 'Upload Image Successfully', 'user' => $user], 200);
    }
    
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

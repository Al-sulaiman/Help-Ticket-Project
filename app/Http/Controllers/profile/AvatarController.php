<?php

namespace App\Http\Controllers\profile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateAvatarRequest;
use Illuminate\Support\Facades\Storage;


class AvatarController extends Controller
{
    public function update(UpdateAvatarRequest $request){

    //also instead of using the $path request and where to store the avatar there is anther syntax
    
   // $path = Storage::disk('public')->put('avatars',$request)->file('avatar');

    $path = $request->file('avatar')->store('avatars','public');
         
         if($oldavatar=$request->user()->avatar){

            storage::disk('public')->delete($oldavatar);
         }


     auth()->user()->update(['avatar' => $path]);
         
     return  redirect(route('profile.edit'))->with('message', 'avatar is updated');
     
     

      //  return 'hello';
     //  return response()->redirectTo('/profile');
     // return back()->with('message', 'update' ); //now i have to go through the user_avatar_form.php and use the redirect session
    
    
    }  

}

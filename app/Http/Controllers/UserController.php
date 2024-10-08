<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Drivers\Gd\Driver;

class UserController extends Controller
{
    public function storeAvatar(Request $request){
        $request->validate([
            "avatar" => "required|image|max:3000"
        ]);
        
        $user = auth()->user();

        $filename = $user->id . "-" . uniqid() . ".jpg";

        $manager = new ImageManager(new Driver());
        $image = $manager->read($request->file("avatar"));
        $imgData = $image->cover(120,120)->toJpeg();
        Storage::put("public/avatars/" . $filename , $imgData);

        $oldAvatar = $user->avatar;

        $user->avatar = $filename;
        $user->save();

        if($oldAvatar != "/fallback-avatar.jpg"){
            Storage::delete(str_replace("/storage/","public/", $oldAvatar));
        }

        return back()->with("success", "Congrats on the new Avatar");
        
    }

    public function showAvatarForm(){
        return view("avatar-form");
    }

    public function profile(User $user){
        // return $pizza->posts()->get();
        return view("profile-posts",["avatar" => $user->avatar,"username" => $user->username,"posts" => $user->posts()->latest()->get(), "postCount" => $user->posts()->count()]);
    }

    //
    public function logout(){
        auth()->logout();
        return redirect("/")->with("success","you have succesfully logged out");
    }

    public function showCorrectHomepage(){
        if(auth()->check()){
            return view("homePage_feed");
        }else{ 
            return view("homePage");
        }
    }

    public function login(Request $request){
        $incomingFields = $request->validate([
            "loginusername" => "required",
            "loginpassword" => "required"
        ]);
        if(auth()->attempt(["username" => $incomingFields["loginusername"], "password" => $incomingFields["loginpassword"]])){
            $request->session()->regenerate();
            return redirect("/")->with("success","You have succesfully logged in");
        }else{
            return redirect("/")->with("failure","Invalid login");
        }
    }

    public function register(Request $request){
        $incomingFields = $request->validate([
            "username" => ["required", "min:3", "max:20", Rule::unique("users","username")],
            "email" => ["required","email", Rule::unique("users","email")], 
            "password" => ["required","min:3","confirmed"]
        ]);

        $incomingFields["password"] = bcrypt($incomingFields["password"]);

        $user = User::create($incomingFields);
        auth()->login($user);
        return redirect("/")->with("success","Thanks for creating an account");
        
    }
}

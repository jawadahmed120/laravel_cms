<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExampleController extends Controller
{
    public function homePage(){
        $my_name = "Jawad";
        $animals = ["dog","cat","lion","elephant"];
        return view("homePage",["name" => $my_name,"animal_names" => $animals]);
    }

    public function aboutPage(){
        return view("single_post");
    }
}

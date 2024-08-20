<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// User related URL
// Route::get("/",[UserController::class,"showCorrectHomepage"])->name("login"); // auth middleware part 2
Route::get("/",[UserController::class,"showCorrectHomepage"]);
Route::post("/register", [UserController::class, "register"])->middleware("guest");;
Route::post("/login",[UserController::class, "login"])->middleware("guest");
Route::post("/logout",[UserController::class, "logout"])->middleware("mustBeLoggedIn");

// Post related URL
// Route::get("/create-post", [PostController::class, "showCreateForm"])->middleware("auth");  // auth middleware part 1
// Route::get("/create-post", [PostController::class, "showCreateForm"])->middleware("mustBeLoggedIn");
Route::get("/create-post", [PostController::class, "showCreateForm"]);
// Route::post("/create-post", [PostController::class, "storeNewPost"])->middleware("mustBeLoggedIn");
Route::post("/create-post", [PostController::class, "storeNewPost"]);
// Route::get("/post/{post}", [PostController::class, "viewSinglePost"])->middleware("mustBeLoggedIn");
Route::get("/post/{post}", [PostController::class, "viewSinglePost"]);
// delete post
Route::delete("/post/{post}",[PostController::class, "delete"])->middleware("can:delete,post");
Route::get("/post/{post}/edit",[PostController::class, "showEditForm"])->middleware("can:update,post");
Route::put("/post/{post}",[PostController::class, "actuallyUpdate"])->middleware("can:update,post");
// Route::update("/post/{post}",[PostController::class, "actuallyUpdate"])->middleware("can:update,post");

// Profile related routes
Route::get("/profile/{user:username}",[UserController::class,"profile"]);
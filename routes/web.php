<?php
 
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\profile\AvatarController;
use App\Http\Controllers\TicketController;
use OpenAI\Laravel\Facades\OpenAI;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/avatar', [AvatarController::class, 'update'])->name('profile.avatar');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';


Route::get('/openai' ,function(){
   

$result = OpenAI::images()->create([
     "prompt" => "create a new avatar for user with name".auth()->user()->name,
     "n" => "1",
     "size" => "256x256",
]);

echo $result['choices'][0]['text']; // an open-source, widely-used, server-side scripting language.
});


Route::post('/auth/redirect', function () {
    return Socialite::driver('github')->redirect();
})->name('login.github');

Route::get('/auth/callback', function () {
    $user = Socialite::driver('github')->user();
    $user= User::firstOrCreate(['email' => $user->email],
    [     
    'name' => $user->name,
    'password' => 'password',
    ]);

    Auth::login($user);
    return redirect('/dashboard');
 
    // $user->token
});

Route::middleware('auth')->group( function(){

    //this one is for creating and updating and editing and storing 
    //better than writing it more than once
    //and to check it just run (php artisan route:list)
    Route::resource('ticket',TicketController::class);

// Route::get('/ticket/create',[TicketController::class,'create'])->name('ticket.create');
// Route::post('/ticket/create',[TicketController::class,'store'])->name('ticket.store');
});
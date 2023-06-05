<?php

use App\Models\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */
 
Route::get('clear_cache', function () {

    \Artisan::call('storage:link');

    dd("Cache is storage:link");

});

Route::middleware(['preventBackHistory'])->group(function () {

    Route::get('/', function (Request $request) {
        $restaurant = Restaurant::find(config('app.default_restaurant'));
        if (empty($restaurant)) {
            $restaurant = Restaurant::first();
        }
        return (new App\Http\Controllers\Restaurant\MenuController)->show($restaurant);
        // return redirect('login');
        // return view('welcome');
    })->name('/');

    Auth::routes(['register' => false]);

    Route::controller(App\Http\Controllers\Restaurant\MenuController::class)->group(function () {
        //  restaurant profile
        Route::get('{restaurant}/menu', 'show')->name('restaurant.menu');
        Route::get('{restaurant}/{food_category}/menu', 'categoryItems')->name('restaurant.menu.item');
        Route::post('foods/{food}','getFoodDetails')->name('restaurant.food');
    });

    Route::put('default/{language}/languages', [App\Http\Controllers\Restaurant\LanguageController::class, 'defaultLanguage'])->name('restaurant.default.language');

    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware(['auth', 'default_restaurant_exists']);
    Route::post('theme_mode', [App\Http\Controllers\Controller::class, 'themeMode'])->name('theme.mode');

    Route::group(['middleware' => ["auth", "default_restaurant_exists"], 'as' => "restaurant."], function () {
        Route::post('global-search', [App\Http\Controllers\HomeController::class, 'globalSearch'])->name('global.search');
        //  Profile
        Route::controller(App\Http\Controllers\Restaurant\ProfileController::class)->group(function () {
            //  restaurant profile
            Route::get('profile', 'show')->name('profile');
            Route::get('profile/edit', 'edit')->name('profile.edit');
            Route::put('profile/update', 'update')->name('profile.update');

            //  restaurant password
            Route::get('profile/change-password', 'passwordEdit')->name('password.edit');
            Route::put('profile/password/update', 'passwordUpdate')->name('password.update');
        });

        //  restaurant user manage -- running
        Route::resource('users', App\Http\Controllers\Restaurant\UserController::class);
        Route::controller(App\Http\Controllers\Restaurant\UserController::class)->group(function () {
            Route::put('{user}/assign', 'assignRestaurant')->name('user.assign.restaurant');
        });

        // restaurant manage
        Route::resource('restaurants', App\Http\Controllers\Restaurant\RestaurantController::class);

        Route::get('qr', [App\Http\Controllers\Restaurant\RestaurantController::class, 'createQR'])->name('create.QR');
        Route::post('{restaurant}/genarteQR', [App\Http\Controllers\Restaurant\RestaurantController::class, 'genarteQR'])->name('genarteQR');

        // set current(default) restaurant
        Route::put('default/{restaurant}/restaurant', [App\Http\Controllers\Restaurant\RestaurantController::class, 'defaultRestaurant'])->name('default.restaurant');

        // main-menu management
        Route::resource('main-menu', App\Http\Controllers\Restaurant\MainMenuController::class, [
            'except' => ['show'],
            'names' => [
                'index' => 'main_menu.index',
                'store' => 'main_menu.store',
                'create' => 'main_menu.create',
                'update' => 'main_menu.update',
                'edit' => 'main_menu.edit',
                'destroy' => 'main_menu.destroy',
            ],
        ]);
        Route::controller(App\Http\Controllers\Restaurant\MainMenuController::class)->group(function () {
            Route::post('main_menu/change/position', 'positionChange')->name('main_menu.change.position');
            Route::get('add_static_data', 'add_static_data');
        });
        
        // food category management
        Route::resource('food-categories', App\Http\Controllers\Restaurant\FoodCategoryController::class, [
            'except' => ['show'],
            'names' => [
                'index' => 'food_categories.index',
                'store' => 'food_categories.store',
                'create' => 'food_categories.create',
                'update' => 'food_categories.update',
                'edit' => 'food_categories.edit',
                'destroy' => 'food_categories.destroy',
            ],
        ]);
        Route::controller(App\Http\Controllers\Restaurant\FoodCategoryController::class)->group(function () {
            Route::post('change/position', 'positionChange')->name('food_categories.change.position');
            Route::get('add_static_data', 'add_static_data');
        });
        // food management
        Route::resource('foods', App\Http\Controllers\Restaurant\FoodController::class);
        Route::controller(App\Http\Controllers\Restaurant\FoodController::class)->group(function () {
            Route::post('food/change/position', 'positionChange')->name('foods.change.position');
            Route::post('food/update_image', 'uploadImage')->name('foods.update-image');
        });

        Route::controller(App\Http\Controllers\Restaurant\EnvSettingController::class)->group(function () {
            Route::get('environment/setting', 'show')->name('environment.setting');
            Route::put('environment/setting', 'update')->name('environment.setting.update');

            //Display Setting
            Route::get('environment/setting/display', 'displaySetting')->name('environment.setting.display');
            Route::put('environment/setting/display/save', 'displaySave')->name('environment.setting.display.update');

            //Email Setting
            Route::get('environment/setting/email', 'emailSetting')->name('environment.setting.email');
            Route::put('environment/setting/email/save', 'emailSave')->name('environment.setting.email.update');
        });

        Route::controller(App\Http\Controllers\Restaurant\ThemeController::class)->group(function () {
            Route::get('themes', 'index')->name('themes.index');
            Route::put('theme-update', 'update')->name('themes.update');
        });

        Route::resource('languages', App\Http\Controllers\Restaurant\LanguageController::class, ['except' => ['show']]);
    });

    Route::get('dev_logs', [\Rap2hpoutre\LaravelLogViewer\LogViewerController::class, 'index']);
});
Route::group(['middleware' => ["auth", "default_restaurant_exists"], 'as' => "restaurant."], function () {
    Route::controller(App\Http\Controllers\Restaurant\LanguageController::class)->group(function () {
        Route::get('export-sample', 'sampleDownload')->name('languages.export.sample');
        Route::post('import-sample', 'sampleImport')->name('languages.import.sample');
    });
});

<?php

namespace App\Http\Controllers\Restaurant;

use App\Http\Controllers\Controller;
use App\Http\Requests\Restaurant\RestaurantRequest;
use App\Models\Food;
use App\Models\FoodCategory;
use App\Models\Restaurant;
use App\Models\RestaurantUser;
use App\Models\User;
use App\Repositories\Restaurant\FoodCategoryRepository;
use App\Repositories\Restaurant\RestaurantRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class RestaurantController extends Controller
{
    public function index()
    {
        $request = request();
        $user = auth()->user();

        $params = $request->only('par_page', 'sort', 'direction', 'filter');

        $par_page = 10;
        if (in_array($request->par_page, [10, 25, 50, 100])) {
            $par_page = $request->par_page;
        }
        $params['par_page'] = $par_page;
        if ($user->user_type != User::USER_TYPE_ADMIN)
            $params['user_id'] = $user->id;
        $restaurants = (new RestaurantRepository())->getUserRestaurants($params);
        return view('restaurant.restaurants.index', ["restaurants" => $restaurants]);
    }

    public function create()
    {
        return view('restaurant.restaurants.create');
    }

    public function store(RestaurantRequest $request)
    {
        $user = auth()->user();
        $data = $request->only('name', 'type', 'contact_email', 'phone_number', 'language', 'city', 'state', 'country', 'zip', 'address', 'logo', 'dark_logo', 'cover_image', 'clone_data_into','theme');
        $data['user_id'] = $user->id;

        DB::beginTransaction();
        $newUser = Restaurant::create($data);

        $inserts = [];
        $insertedFood = [];
        if (isset($data['clone_data_into'])) {
            $params['restaurant_id'] = $data['clone_data_into'];
            $foodCategories = (new FoodCategoryRepository)->getRestaurantFoodCategories($params);
            $foodCategories->load('foods');
            foreach ($foodCategories as $category) {
                $newCategory = (new FoodCategory)->fill($category->toarray());
                $newCategory->restaurant_id = $newUser->id;
                $newCategory->save();
                $cat_id = $newCategory->id;
                foreach ($category->foods as $food) {
                    if (!in_array($food->name, $insertedFood)) {
                        $insertedFood[] = $food->name;
                        $newfood = (new Food())->fill($food->toarray());
                        $newfood->restaurant_id = $newUser->id;
                        $newfood->save();
                    }
                    $inserts[] = ['food_category_id' => $cat_id, 'food_id' => $newfood->id];
                }
            }
            if (count($inserts) > 0) {
                $inserts = array_map("unserialize", array_unique(array_map("serialize", $inserts)));
                $d =   DB::table('food_food_category')->insert($inserts);
            }
        }
        RestaurantUser::create([
            'restaurant_id' => $newUser->id,
            'user_id' => $user->id,
            'role' => RestaurantUser::ROLE_STAFF,
        ]);

        $adminUsers = User::where('user_type', User::USER_TYPE_ADMIN)->where('id', '!=', $user->id)->get();
        foreach ($adminUsers as $auser) {
            RestaurantUser::create([
                'restaurant_id' => $newUser->id,
                'user_id' => $auser->id,
                'role' => RestaurantUser::ROLE_ADMIN,
            ]);
        }
        DB::commit();
        $request->session()->flash('Success', __('system.messages.saved', ['model' => __('system.restaurants.title')]));
        return redirect(route('restaurant.restaurants.index'));
    }

    public function show(Restaurant $restaurant)
    {
        if (($redirect = $this->checkRestaurantIsValidUser($restaurant)) != null) {
            return redirect($redirect);
        }
        $restaurant->load(['created_user', 'users' => function ($q) {
            $q->limit(5);
        }]);
        return view('restaurant.restaurants.view', ['restaurant' => $restaurant]);
    }

    public function genarteQR(Restaurant $restaurant)
    {
        $request = request();
        $size = $request->size  && ($request->size >= 100 &&  $request->size <= 325) ? $request->size : 325;
        $logo_size = $request->logo_size  && ($request->logo_size >= 0.25 &&  $request->logo_size <=  0.5) ? $request->logo_size : 0.25;
        if (isset($request->image) && $request->has('image')) {
            $file = $request->image;
            $logo = File::get($file->getRealPath());
        } elseif ($request->logo == true && isset($restaurant->qr_details['logo'])) {
            $logo = Storage::get($restaurant->qr_details['logo']);
        }

        $color = $request->color ?? "#000000";
        list($cr, $cg, $cb) = sscanf($color, "#%02x%02x%02x");
        $color_transparent = $request->color_transparent  && ($request->color_transparent >= 1 &&  $request->color_transparent <=  100) ? $request->color_transparent : 100;

        $back_color = $request->back_color  ??  "#ffffff";
        list($br, $bg, $bb) = sscanf($back_color, "#%02x%02x%02x");
        $back_color_transparent = $request->back_color_transparent  && ($request->back_color_transparent >= 0 &&  $request->back_color_transparent <=  100) ? $request->back_color_transparent : 1;

        $gradient_method = $request->gradient_method  && in_array($request->gradient_method, ['vertical', 'horizontal', 'diagonal', 'inverse_diagonal', 'radial']) ? $request->gradient_method : 'vertical';
        $gradient_color1 = $request->gradient_color1 ?? "#000000";
        list($l1r, $l1g, $l1b) = sscanf($gradient_color1, "#%02x%02x%02x");
        $gradient_color2 =  $request->gradient_color2 ??  "#000000";
        list($l2r, $l2g, $l2b) = sscanf($gradient_color2, "#%02x%02x%02x");

        $qr_style = $request->qr_style  && in_array($request->qr_style,  ['square', 'dot', 'round']) ? $request->qr_style : 'square';
        $qr_style_size = $request->qr_style_size  && ($request->qr_style_size >= 0.25 &&  $request->qr_style_size <=  0.5) ? $request->qr_style_size : 1;

        $eye_style = $request->eye_style  && in_array($request->eye_style,  ['square', 'circle']) ? $request->eye_style : 'square';
        $eye_inner_color  = $request->eye_inner_color ?? "#000000";
        list($eir, $eig, $eib) = sscanf($eye_inner_color, "#%02x%02x%02x");
        $eye_outer_color  = $request->eye_outer_color ?? "#000000";
        list($eor, $eog, $eob) = sscanf($eye_outer_color, "#%02x%02x%02x");

        $QR  = QrCode::size($size)->format('png');
        if (isset($logo)) {
            if ($request->save == 0) {
                list($width, $height) = getimagesize(imageDataToCollection($logo));
                if (($width * $logo_size) > 500 || ($height * $logo_size) > 500) {
                    $logo_size /= 2;
                }
            }
            $QR = $QR->mergeString($logo, $logo_size);
        }
        $QR = $QR->backgroundColor($br ?? 0, $bg ?? 0, $bb ?? 0, $back_color_transparent);

        if ($request->gradient_method) {
            $QR = $QR->gradient($l1r ?? 0, $l1g ?? 0, $l1b ?? 0, $l2r ?? 0, $l2g ?? 0, $l2b ?? 0, $gradient_method);
        } else if ($request->color) {
            $QR = $QR->color($cr ?? 0, $cg ?? 0, $cb ?? 0, $color_transparent);
        }

        $QR = $QR->eye($eye_style);
        $QR = $QR->eyeColor(0, $eir ?? 0, $eig ?? 0, $eib ?? 0, $eor ?? 0, $eog ?? 0, $eob ?? 0);
        $QR = $QR->eyeColor(1, $eir ?? 0, $eig ?? 0, $eib ?? 0, $eor ?? 0, $eog ?? 0, $eob ?? 0);
        $QR = $QR->eyeColor(2, $eir ?? 0, $eig ?? 0, $eib ?? 0, $eor ?? 0, $eog ?? 0, $eob ?? 0);
        $QR = $QR->style($qr_style);

        if ($request->save == 1) {
            $qr_details = [
                'size' => $size,
                'logo' => $request->logo == true && isset($restaurant->qr_details['logo']) ? $restaurant->qr_details['logo'] : '',

                'is_logo_visible' => $request->is_logo_visible,
                'logo_size' => $logo_size,

                'color' => sprintf("#%02x%02x%02x", $cr ?? 0, $cg ?? 0, $cb ?? 0),
                'color_transparent' => $color_transparent,

                'back_color' => sprintf("#%02x%02x%02x", $br ?? 0, $bg ?? 0, $bb ?? 0),
                'back_color_transparent' => $back_color_transparent,

                'gradient_method' => $request->gradient_method != null ? $gradient_method : '',
                'gradient_color1' => sprintf("#%02x%02x%02x", $l1r ?? 0, $l1g ?? 0, $l1b ?? 0),
                'gradient_color2' => sprintf("#%02x%02x%02x", $l2r ?? 0, $l2g ?? 0, $l2b ?? 0),

                'qr_style' => $qr_style,
                'qr_style_size' => $qr_style_size,

                'eye_style' => $eye_style,
                'eye_inner_color' => sprintf("#%02x%02x%02x", $eir ?? 0, $eig ?? 0, $eib ?? 0),
                'eye_outer_color' => sprintf("#%02x%02x%02x", $eor ?? 0, $eog ?? 0, $eob ?? 0),
            ];
            if ($request->has('image')) {
                $file = $request->image;
                $qr_details['logo'] = uploadFile($file, 'qr_code_logo');
            }
            $restaurant->fill(['qr_details' => $qr_details])->save();
            $request->session()->flash('Success', __('system.messages.updated', ['model' => __('system.qr_code.menu')]));
        }

        $image = base64_encode(
            $QR->generate(route('restaurant.menu', $restaurant->id)),
        );
        return view('restaurant.restaurants.genarteqr', ['image' => $image]);
    }

    public function edit(Restaurant $restaurant)
    {
        if (($redirect = $this->checkRestaurantIsValidUser($restaurant)) != null) {
            return redirect($redirect);
        }
        return view('restaurant.restaurants.edit', ['restaurant' => $restaurant]);
    }

    public function checkRestaurantIsValidUser($restaurant)
    {

        $user = auth()->user();
        if ($user->user_type == User::USER_TYPE_ADMIN) {
            return;
        }
        $restaurant->load(['users' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }]);
        if (count($restaurant->users) == 0) {
            $back = request()->get('back', route('restaurant.restaurants.index'));
            request()->session()->flash('Error', __('system.messages.not_found', ['model' => __('system.restaurants.title')]));

            return $back;
        }
    }

    public function createQR()
    {
        $users = auth()->user();
        $users->load(['restaurant' => function ($q) {
            $q->select('restaurants.*');
        }]);
        $restaurant = $users->restaurant;
        return view('restaurant.restaurants.create_qr', ['restaurant' => $restaurant]);
    }

    public function update(RestaurantRequest $request, Restaurant $restaurant)
    {
        if (($redirect = $this->checkRestaurantIsValidUser($restaurant)) != null) {
            return redirect($redirect);
        }
        $data = $request->only('name', 'type', 'contact_email', 'phone_number', 'language', 'city', 'state', 'country', 'zip', 'address', 'logo', 'cover_image', 'dark_logo','theme');
        $restaurant = $restaurant->fill($data)->save();
        $request->session()->flash('Success', __('system.messages.updated', ['model' => __('system.restaurants.title')]));

        if ($request->back) {
            return redirect($request->back);
        }
        return redirect(route('restaurant.restaurants.index'));
    }

    public function defaultRestaurant(Restaurant $restaurant)
    {
        if (($redirect = $this->checkRestaurantIsValidUser($restaurant)) != null) {
            return redirect($redirect);
        }
        $user = request()->user();
        $request = request();
        $user->restaurant_id = $restaurant->id;
        $user->save();
        $request->session()->flash('Success', __('system.messages.change_success_message', ['model' => __('system.restaurants.title')]));

        if ($request->back) {
            return redirect($request->back);
        }
        return redirect(route('home'));
    }

    public function destroy(Restaurant $restaurant)
    {
        $request = request();
        if (($redirect = $this->checkRestaurantIsValidUser($restaurant)) != null) {
            return redirect($redirect);
        }
        $restaurant->load(['users' => function ($q) use ($restaurant) {
            $q->where('users.restaurant_id', $restaurant->id);
        }]);

        if (count($restaurant->users) > 0) {
            foreach ($restaurant->users as $restoUser) {
                $restoUser->load(['restaurants' => function ($q) use ($restaurant) {
                    $q->wherePivot('restaurant_id', '!=', $restaurant->id);
                }]);
                if (count($restoUser->restaurants) > 0) {
                    $restoUser->restaurant_id = $restoUser->restaurants->first()->id;
                } else {
                    $restoUser->restaurant_id = null;
                }
                $restoUser->save();
            }
        }
        $restaurant->delete();
        $request->session()->flash('Success', __('system.messages.deleted', ['model' => __('system.restaurants.title')]));

        if ($request->back) {
            return redirect($request->back);
        }

        return redirect(route('restaurant.restaurants.index'));
    }

    public static function getRestaurantsDropdown()
    {
        $restaurants = (new RestaurantRepository())->getAllRestaurantsWithIdAndName();
        return $restaurants;
    }
}

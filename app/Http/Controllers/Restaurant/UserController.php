<?php

namespace App\Http\Controllers\Restaurant;

use App\Models\User;
use App\Models\Restaurant;
use Illuminate\Http\Request;
use Spatie\Searchable\Search;
use App\Models\RestaurantUser;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Spatie\Searchable\ModelSearchAspect;
use App\Http\Requests\Restaurant\UserRequest;
use App\Repositories\Restaurant\UserRepository;

class UserController extends Controller
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
        $params['user_id'] = $user->id;
        if ($request->get('user_list', 'current') == 'current' || !isAdmin())
            $params['restaurant_id'] = $user->restaurant_id;
        elseif ($request->user_list == 'not_assigned') {
            $params['not_assigned'] = true;
        } elseif ($request->user_list !=  'all') {
            return redirect(route('restaurant.users.index'));
        }
        $users = (new UserRepository())->getRestaurantUsers($params);
        if ($request->user_list ==  'all') {
            $users->load('restaurants');
        }
        return view('restaurant.users.index', ['users' => $users]);
    }

    public function create()
    {
        return view('restaurant.users.create');
    }

    public function store(UserRequest $request)
    {
        $user = auth()->user();
        $data = $request->all();

        $newUser = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'phone_number' => $data['phone_number'],
            'profile_image' => $data['profile_image'] ?? null,
            'password' => Hash::make($data['password']),
            'status' => User::STATUS_ACTIVE,
            'city' => $data['city'],
            'state' => $data['state'],
            'city' => $data['city'],
            'country' => $data['country'],
            'zip' => $data['zip'],
            'address' => $data['address'],
            'restaurant_id' => $user->restaurant_id,
        ]);
        RestaurantUser::create([
            'restaurant_id' => $user->restaurant_id,
            'user_id' => $newUser->id,
            'role' => RestaurantUser::ROLE_STAFF,
        ]);
        $request->session()->flash('Success', __('system.messages.saved', ['model' => __('system.users.title')]));

        return redirect(route('restaurant.users.index'));
    }

    public function show(User $user)
    {
        //
    }

    public function edit(User $user)
    {
        if (($redirect = $this->checkRestaurantIsValidUser($user)) != null) {
            return redirect($redirect);
        }

        return view('restaurant.users.edit', ['user' => $user]);
    }

    public function update(UserRequest $request, User $user)
    {
        if (($redirect = $this->checkRestaurantIsValidUser($user)) != null) {
            return redirect($redirect);
        }
        $data = $request->only('first_name', 'last_name', 'phone_number',  'city', 'state', 'country', 'zip', 'profile_image', 'address');
        $user = $user->fill($data)->save();
        $request->session()->flash('Success', __('system.messages.updated', ['model' => __('system.users.title')]));

        if ($request->back) {
            return redirect($request->back);
        }
        return redirect(route('restaurant.users.index'));
    }

    public function checkRestaurantIsValidUser($user)
    {
        if (auth()->user()->user_type == User::USER_TYPE_ADMIN) {
            return;
        }

        $restaurant = auth()->user()->restaurant;
        $restaurant->load(['users' => function ($q) use ($user) {
            $q->where('user_id', $user->id);
        }]);
        if (count($restaurant->users) == 0) {
            $back = request()->get('back', route('restaurant.users.index'));
            request()->session()->flash('Error', __('system.messages.not_found', ['model' => __('system.users.title')]));

            return $back;
        }
    }

    public function destroy(User $user)
    {
        $request = request();
        if (($redirect = $this->checkRestaurantIsValidUser($user)) != null) {
            return redirect($redirect);
        }
        $user->load(['restaurants' => function ($q) use ($user) {
            $q->where('restaurants.user_id', $user->id);
        }]);
        foreach ($user->restaurants as $restaurant) {
            $restaurant->load(['users' => function ($q) use ($user) {
                $q->wherePivot('user_id', "!=", $user->id);
            }]);
            if (count($restaurant->users) > 0) {
                $restaurant->user_id = $restaurant->users->first()->id;
            } else {
                $restaurant->user_id = null;
            }
            $restaurant->save();
        }
        $user->delete();
        $request->session()->flash('Success', __('system.messages.deleted', ['model' => __('system.users.title')]));

        if ($request->back) {
            return redirect($request->back);
        }

        return redirect(route('restaurant.users.index'));
    }

    public function assignRestaurant(User $user)
    {
        $request = request();
        if (($this->checkRestaurantIsValidUser($user)) != null) {
            request()->session()->flash('Error', __('system.messages.not_found', ['model' => __('system.users.title')]));
        }
        $restaurant = Restaurant::find($request->assign_restaurant);
        if (!$restaurant) {
            request()->session()->flash('Error', __('system.messages.not_found', ['model' => __('system.restaurants.title')]));
        }
        RestaurantUser::create([
            'restaurant_id' => $restaurant->id,
            'user_id' => $user->id,
            'role' => RestaurantUser::ROLE_STAFF,
        ]);
        $request->session()->flash('Success', __('system.messages.updated', ['model' => __('system.users.title')]));
        return redirect()->back();
    }
}

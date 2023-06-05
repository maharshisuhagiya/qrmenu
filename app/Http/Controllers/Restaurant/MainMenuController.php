<?php

namespace App\Http\Controllers\Restaurant;

use Illuminate\Support\Str;
use App\Models\MainMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\File\File;
use App\Http\Requests\Restaurant\MainMenuRequest;
use App\Models\Food;
use App\Repositories\Restaurant\MainMenuRepository;
use Illuminate\Http\UploadedFile;

class MainMenuController extends Controller
{
    public function index()
    {
        $request = request();
        $user = auth()->user();
        $params = $request->only('par_page', 'sort', 'direction', 'filter', 'restaurant_id');
        $params['restaurant_id'] = $params['restaurant_id'] ?? $user->restaurant_id;
        $mainMenu = (new MainMenuRepository)->getRestaurantFoodCategories($params);
        return view('restaurant.main_menu.index', ['mainMenu' => $mainMenu]);
    }

    public function create()
    {
        return view('restaurant.main_menu.create');
    }

    public function store(MainMenuRequest $request)
    {
        try {
            DB::beginTransaction();
            $input = $request->only('main_menu_name', 'restaurant', 'restaurant_id', 'main_menu_image', 'lang_main_menu_name', 'main_menu_description');
            $input['sort_order'] = MainMenu::max('sort_order') + 1;
            MainMenu::create($input);
            DB::commit();
            $request->session()->flash('Success', __('system.messages.saved', ['model' => __('system.main_menu.title')]));
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollback();
            $request->session()->flash('Error', __('system.messages.operation_rejected'));
            return redirect()->back();
        }
        return redirect()->route('restaurant.main_menu.index');
    }

    public function show(MainMenu $mainMenu)
    {
    }

    public function checkRestaurantIsValidMainMenu($main_menu_id, $user = null)
    {

        if (empty($user)) {
            $user = auth()->user();
        }

        $user->load(['restaurant.main_menu' => function ($q) use ($main_menu_id) {
            $q->where('id', $main_menu_id);
        }]);

        if (!isset($user->restaurant) || count($user->restaurant->food_categories) == 0) {
            $back = request()->get('back', route('restaurant.main_menu.index'));
            request()->session()->flash('Error', __('system.messages.not_found', ['model' => __('system.main_menu.title')]));

            return $back;
        }
    }

    public function edit(MainMenu $mainMenu)
    {
        if (($redirect = $this->checkRestaurantIsValidMainMenu($mainMenu->id)) != null) {
            return redirect($redirect);
        }
        return view('restaurant.main_menu.edit', ['mainMenu' => $mainMenu]);
    }

    public function update(MainMenuRequest $request, MainMenu $mainMenu)
    {
        if (($redirect = $this->checkRestaurantIsValidMainMenu($mainMenu->id)) != null) {
            return redirect($redirect);
        }
        $input = $request->only('main_menu_name', 'main_menu_image', 'lang_main_menu_name', 'main_menu_description');
        $mainMenu->fill($input)->save();

        $request->session()->flash('Success', __('system.messages.updated', ['model' => __('system.main_menu.title')]));
        if ($request->back) {
            return redirect($request->back);
        }
        return redirect(route('restaurant.main_menu.index'));
    }

    public function destroy(MainMenu $mainMenu)
    {
        $request = request();
        if (($redirect = $this->checkRestaurantIsValidMainMenu($mainMenu->id)) != null) {
            return redirect($redirect);
        }
        $mainMenu->delete();
        $request->session()->flash('Success', __('system.messages.deleted', ['model' => __('system.main_menu.title')]));
        if ($request->back) {
            return redirect($request->back);
        }
        return redirect(route('restaurant.main_menu.index'));
    }

    public function positionChange()
    {
        $request = request();
        $mainMenu = MainMenu::where('id', $request->id)->update(['sort_order' => $request->index]);
        return true;
    }


    public static function getCurrentRestaurantAllFoodCategories()
    {
        $user = request()->user();
        $user->load(['restaurant.main_menu' => function ($q) {
            $q->orderBy('main_menu_name', 'asc');
        }]);
        $food_categories = $user->restaurant->food_categories->mapWithKeys(function ($food_category, $key) {
            return [$food_category->id => $food_category->main_menu_name];
        });
        return ['' => __('system.fields.select_Category')] + $food_categories->toarray();
    }
}

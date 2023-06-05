<?php

namespace App\Http\Requests\Restaurant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;

class MainMenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {

        $request = request();
        $langs = getAllCurrentRestaruentLanguages();
        $rules = [
            'main_menu_image' => ['nullable', 'max:50000', "image", 'mimes:jpeg,png,jpg,gif,svg'],
            'main_menu_name' => ['required', 'string', 'max:255', 'min:2'],

            // 'restaurant_id' => ['required'],
            'restaurant_id' => ['required', 'unique:main_menus,restaurant_id,null,id,main_menu_name,' . $request->main_menu_name],
        ];


        $request->request->add(['restaurant' => array_filter($request->restaurant ?? [])]);
        // dd($request->all());
        if (isset($this->main_menu)) {
            $old_id = $this->main_menu->id;
            unset($rules['restaurant.*']);
            unset($rules['restaurant']);
            $rules['restaurant_id'] = ['required', 'unique:main_menus,restaurant_id,' . $old_id . ',id,main_menu_name,' . $request->main_menu_name];
            if (count($langs) > 0) {
                $rules['lang_main_menu_name.*'] = ['required',];
                $rules['restaurant_ids.*'] = ['required'];

                foreach ($langs as $key => $lang) {
                    $rules['lang_main_menu_name.' . $key] = ['string', 'max:255', 'min:2'];
                    $rules['restaurant_ids.' . $key] = ["unique:main_menus,restaurant_id,$old_id,id,lang_main_menu_name->$key," .  str_replace("%", "%%", $request->lang_main_menu_name[$key])];
                }
            }
        } elseif (count($langs) > 0) {
            $rules['lang_main_menu_name.*'] = ['required',];
            $rules['restaurant_ids.*'] = ['required'];

            foreach ($langs as $key => $lang) {
                $rules['lang_main_menu_name.' . $key] = ['string', 'max:255', 'min:2'];
                // dd("unique:main_menus,restaurant_id,null,id,lang_main_menu_name->$key," . $request->lang_main_menu_name[$key]);
                $rules['restaurant_ids.' . $key] = ["unique:main_menus,restaurant_id,null,id,lang_main_menu_name->$key," . str_replace("%", "%%", $request->lang_main_menu_name[$key])];
            }
        }
        // dd($rules);
        // DB::enableQueryLog();
        return $rules;
    }

    public function messages()
    {

        $langs = getAllCurrentRestaruentLanguages();
        $request = request();
        $lbl_main_menu_image = strtolower(__('system.fields.main_menu_image'));
        $lbl_main_menu_name = strtolower(__('system.fields.main_menu_name'));
        $messages = [
            "main_menu_image.max" => __('validation.gt.file', ['attribute' => $lbl_main_menu_image, 'value' => 50000]),
            "main_menu_image.image" => __('validation.enum', ['attribute' => $lbl_main_menu_image]),
            "main_menu_image.mimes" => __('validation.enum', ['attribute' => $lbl_main_menu_image]),

            "main_menu_name.required" => __('validation.required', ['attribute' => $lbl_main_menu_name]),
            "main_menu_name.string" => __('validation.custom.invalid', ['attribute' => $lbl_main_menu_name]),
            "main_menu_name.max" => __('validation.custom.invalid', ['attribute' => $lbl_main_menu_name]),

            "restaurant_id.required" => __('validation.custom.select_required', ['attribute' => 'restaurant']),
            "restaurant_id.unique" => __('validation.unique', ['name' => $request->main_menu_name, 'attribute' => $lbl_main_menu_name]),
        ];
        if (count($langs) > 0) {

            foreach ($langs as $key => $lang) {
                $messages["lang_main_menu_name.$key.string"] = __('validation.custom.invalid', ['attribute' => 'category name ' . strtolower($lang)]);
                $messages["lang_main_menu_name.$key.max"] = __('validation.custom.invalid', ['attribute' => 'category name ' . strtolower($lang)]);
                $messages["lang_main_menu_name.$key.min"] = __('validation.custom.invalid', ['attribute' => 'category name ' . strtolower($lang)]);
                $messages["restaurant_ids.$key.unique"]  = __('validation.unique', ['name' => $request->lang_main_menu_name[$key], 'attribute' => $lbl_main_menu_name . " " . strtolower($lang)]);
            }
        }
        return $messages;
    }
}

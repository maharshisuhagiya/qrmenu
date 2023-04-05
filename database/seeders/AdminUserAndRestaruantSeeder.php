<?php

namespace Database\Seeders;

use App\Models\Restaurant;
use App\Models\RestaurantUser;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserAndRestaruantSeeder extends Seeder
{
    public function run()
    {
        $user = User::where('email', 'admin@menu.com')->count();
        if ($user == 0) {
            $user = User::create([
                'first_name' => 'Jeremy',
                'last_name' => 'Mazon',
                'email' => 'admin@menu.com',
                'phone_number' => '+918855226633',
                'password' => Hash::make('Admin@123#'),
                'status' => User::STATUS_ACTIVE,
                'user_type' => User::USER_TYPE_ADMIN,
            ]);

            $restaurant = Restaurant::create([
                'user_id' => $user->id,
                'name' => 'The Salad Life',
                'type' => 'Hotel',
            ]);

            $user->restaurant_id = $restaurant->id;
            $user->save();

            $restaurant_user = RestaurantUser::create([
                'restaurant_id' => $restaurant->id,
                'user_id' => $user->id,
                'role' => RestaurantUser::ROLE_ADMIN,
            ]);

            echo "Success, User Created!";
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Gym;
use App\Models\City;
use App\Models\User;
use App\Models\GymManager;
use App\Models\UserTrainingPackage;

class RevenueController extends Controller
{
    public function show ()
   {
       $loginUser = Auth::user();
       $revenue =0 ;
       if($loginUser->hasRole('gym_manager'))
       {
           $gymId = GymManager::where('staff_id',$loginUser->id)->first()->gym_id;
           $revenue = Gym::where('id',$gymId)->get()[0]->revenue;
       }
       else if ($loginUser->hasRole('city_manager'))
       {
           $cityId=City::where('staff_id',$loginUser->id)->first()->id;
           $gyms= Gym::where('city_id',$cityId)->get();
           foreach($gyms as $gym)
           {
               $revenue += $gym->revenue;
           }
       }
       else if ($loginUser->hasRole('Super-Admin'))
       {
           $cities = City::all();
           foreach($cities as $city)
           {
             $gyms= Gym::where('city_id',$city->id)->get();
             foreach($gyms as $gym)
             {
                $revenue += $gym->revenue;
             }
           }
       }
       return view ('revenue.show',[
        'revenue' => $revenue
    ]);
   }
}

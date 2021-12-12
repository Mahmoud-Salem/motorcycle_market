<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Motorcycle;
use App\Models\Images;
use App\Models\User;
use Illuminate\Support\Facades\Redis;

use Validator;

class MotorcycleController extends Controller
{

    // Store product into db
    public function store(Request $request)
    {
        $request->validate([
            'model' => 'required|string',
            'make' => 'required|string',
            'year' => 'required|int',
            'description' => 'required|string',
        ]);

        $motorcycle = new Motorcycle;

        $motorcycle->model = $request->model;
        $motorcycle->make = $request->make;
        $motorcycle->year = $request->year;
        $motorcycle->description = $request->description;
        $motorcycle->user_id = auth()->user()->id;

        $motorcycle->save();
        return response()->json($motorcycle,201);
    }

    // mark item as sold
    public function soldItem(Request $request, Motorcycle $motorcycle)
    {

        if($motorcycle->user_id != auth()->user()->id)
        {
            return response()->json(['error' => 'UnAuthorised Access'], 401);
        }
        
        $motorcycle->sold = 1 ;
        $motorcycle->save();
        return response()->json(['Motocycle Updated Successfully'], 200);
    }

    // get all available products
    public function index()
    {
        $cachedProducts = Redis::get('products');
        if(isset($cachedProducts)) {
            $products = json_decode($cachedProducts, FALSE);
      
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched from Redis',
                'data' => $products,
            ]);
        }else {
            $products = Motorcycle::with(['images','user'])->where('sold',0)->get();
            Redis::set('products', $products,'EX',1);
            return response()->json([
                'status_code' => 200,
                'message' => 'Fetched from Database',
                'data' => $products,
            ]);
        }
    }

}

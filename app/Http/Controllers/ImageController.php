<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Motorcycle;
use App\Models\Images;
use Validator;

class ImageController extends Controller
{
    public function addImage(Request $request, Motorcycle $motorcycle)
    {


        if($motorcycle->user_id != auth()->user()->id)
        {
            return response()->json(['error' => 'UnAuthorised Access'], 401);
        }

        $request->validate([
            'images' => 'required',
            'images.*' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        $files = $request->file('images');
        foreach ($files as $file) {      
            // save image to directory
            $imageName = time().'-'.$file->getClientOriginalName();     
            $file->move(public_path('images'), $imageName);
            // save path to db
            $image = new Images;
            $image->location = 'images/'.$imageName;
            $image->product_id = $motorcycle->id;
            $image->save();
        }
        return response()->json(['Images Uploaded Successfully'], 200);
    }
}

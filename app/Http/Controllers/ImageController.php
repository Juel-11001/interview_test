<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    public function upload()
    {
        $images=Image::latest()->get();
        return view('upload', compact('images'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validation=$request->validate([
            'image' => 'required|image|mimes:jpeg,png|max:1024',
        ],[
            'image.required' => 'Please upload an image to continue. It is required for this action.',
            'image.image' => 'The selected file must be a valid image (JPEG, PNG). Kindly check your file format.',
            'image.mimes' => 'Only JPEG and PNG image formats are allowed. Please upload an image in one of these formats.',
            'image.max' => 'The image size is too large (max: 2MB). Please upload a smaller image.',
        ]);
        if($request->hasFile('image')){
            $image=$request->file('image');
            $imageName=uniqid().'.'.$image->getClientOriginalExtension();
            $image->move(public_path('images'),$imageName);
            $filepath = 'images'.'/'.$imageName;
        }
        Image::create([
           'filepath' => $filepath
        ]);
        return redirect()->route('image.upload')->with('success', 'Image has been uploaded successfully.');

      
    }   
}

<?php

namespace App\Http\Controllers;

use App\Http\Resources\Developer\DeveloperResource;
use App\Http\Resources\Developer\DeveloperResourceCollection;
use App\Models\Country;
use App\Models\Developper;
use App\Notifications\WelcomeDeveloperNotification;
use Illuminate\Http\Request;

class DevelopperController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(new DeveloperResourceCollection(Developper::all()));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => ["required", 'unique:developpers,name'],
            "bio" => ["nullable", 'min:20'],
            "email" => ["required", "email"],
            "phone" => ["nullable", "phone:CM,AUTO"],
            'country' => ["required", "exists:countries,iso_code"]

        ]);

        $developper = auth()->user()->developer()->create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'bio' => $request->bio,
            'slug' => Developper::slug(),
            'website' => $request->website,
            'country_id' => Country::whereIsoCode($request->country)->first()->id
        ]);

        auth()->user()->notify(new WelcomeDeveloperNotification());

        return response()->json(new DeveloperResource($developper->refresh()));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Developper  $developper
     * @return \Illuminate\Http\Response
     */
    public function show(Developper $developper)
    {
        return response()->json(new DeveloperResource($developper->refresh()));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Developper  $developper
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Developper $developper)
    {
        $developper->forceFill($request->only(['name', 'bio', 'email', 'phone', 'website']))->update();

        if ($request->country) {
            $request->validate([
                "country" => ["required", 'exists:countries,iso_code']
            ]);

            $developper->country = Country::whereIsoCode($request->country)->first()->iso_code;

            $developper->save();
        }

        return response()->json(new DeveloperResource($developper->refresh()));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Developper  $developper
     * @return \Illuminate\Http\Response
     */
    public function destroy(Developper $developper)
    {
        $developper->delete();
        return true;
    }

    public function upload(Request $request, Developper $developper)
    {
        $request->validate([
            'file' => ["required", 'image']
        ]);

        $path = $request->file(('file'))->storePublicly($developper->slug);

        $developper->poster = $path;
        $developper->save();

        return response()->json(get_file_absolute_path($path));
    }
}

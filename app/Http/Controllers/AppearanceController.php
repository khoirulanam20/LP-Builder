<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LandingPage;
use App\Models\Appearance;
use Illuminate\Validation\Rule;

class AppearanceController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        $landingPage = LandingPage::firstOrCreate(
            ['user_id' => $user->id],
            ['title' => 'My Digital Product', 'slug' => \Illuminate\Support\Str::slug($user->name . '-' . uniqid())]
        );

        $appearance = Appearance::firstOrCreate(
            ['landing_page_id' => $landingPage->id]
        );

        return view('appearance.index', compact('landingPage', 'appearance'));
    }

    public function update(Request $request)
    {
        $user = $request->user();
        $landingPage = LandingPage::where('user_id', $user->id)->firstOrFail();
        $appearance = Appearance::where('landing_page_id', $landingPage->id)->firstOrFail();

        $request->validate([
            'slug' => ['required', 'string', 'max:255', Rule::unique('landing_pages')->ignore($landingPage->id)],
            'theme_color' => 'required|string|max:50',
            'about_text' => 'nullable|string',
            'logo' => 'nullable|image|max:1024',
            'social_links' => 'nullable|array'
        ]);

        $landingPage->update(['slug' => \Illuminate\Support\Str::slug($request->slug)]);

        $appearance->theme_color = $request->theme_color;
        $appearance->about_text = $request->about_text;
        
        if($request->social_links) {
            // filter out nulls
            $appearance->social_links = array_filter($request->social_links);
        } else {
            $appearance->social_links = null;
        }

        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('appearances', 'public');
            $appearance->logo_path = $path;
        }

        $appearance->save();

        return redirect()->route('appearance.index')->with('status', 'Appearance updated!');
    }
}

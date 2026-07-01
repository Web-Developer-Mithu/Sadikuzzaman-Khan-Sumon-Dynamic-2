<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AchievementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $items = Achievement::orderBy('created_at', 'desc')->paginate(12);
        return view('admin.achievement.achievement_list', compact('items'));
    }

    public function create()
    {
        return view('admin.achievement.create_achievement_item');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $achievement = new Achievement();
        $achievement->title = $request->input('title');

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $slug = Str::slug($request->input('title')) ?: 'achievement-item';
            $filename = "{$slug}-" . time() . ".{$extension}";

            $publicAchievementPath = public_path('achievement-images');
            if (! is_dir($publicAchievementPath)) {
                mkdir($publicAchievementPath, 0755, true);
            }

            $file->move($publicAchievementPath, $filename);
            $achievement->image = $filename;
        }

        $achievement->save();

        return redirect('/admin/achievement')->with('success', 'Achievement added successfully.');
    }

    public function edit($id)
    {
        $item = Achievement::findOrFail($id);
        return view('admin.achievement.create_achievement_item', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = Achievement::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $item->title = $request->input('title');

        if ($request->hasFile('image') && $request->file('image')->isValid()) {
            if ($item->image && file_exists(public_path('achievement-images/' . $item->image))) {
                unlink(public_path('achievement-images/' . $item->image));
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $slug = Str::slug($request->input('title')) ?: 'achievement-item';
            $filename = "{$slug}-" . time() . ".{$extension}";

            $publicAchievementPath = public_path('achievement-images');
            if (! is_dir($publicAchievementPath)) {
                mkdir($publicAchievementPath, 0755, true);
            }

            $file->move($publicAchievementPath, $filename);
            $item->image = $filename;
        }

        $item->save();

        return redirect('/admin/achievement')->with('success', 'Achievement updated successfully.');
    }

    public function destroy($id)
    {
        $item = Achievement::findOrFail($id);

        if ($item->image && file_exists(public_path('achievement-images/' . $item->image))) {
            unlink(public_path('achievement-images/' . $item->image));
        }

        $item->delete();

        return redirect()->back()->with('success', 'Achievement deleted successfully.');
    }
}

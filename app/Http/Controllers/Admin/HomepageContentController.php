<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageContent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\ActivityLog;

class HomepageContentController extends Controller
{
    public function edit()
    {
        $content = HomepageContent::first();

        return view(
            'admin.content.edit',
            compact('content')
        );
    }

    public function update(
        Request $request
    )
    {
        $content = HomepageContent::first();

        $validated = $request->validate([

            'title' => 'required|max:255',

            'description' => 'required',

            'button_text' => 'required|max:255',

            'logo' => 'nullable|image|max:2048',

            'background' => 'nullable|image|max:4096',

            'registration_background' => 'nullable|image|max:4096',
        ]);

        if ($request->hasFile('logo')) {

            if ($content->logo) {

                Storage::disk('public')
                    ->delete($content->logo);
            }

            $validated['logo'] = $request
                ->file('logo')
                ->store(
                    'homepage/logo',
                    'public'
                );
        }

        if ($request->hasFile('background')) {

            if ($content->background) {

                Storage::disk('public')
                    ->delete($content->background);
            }

            $validated['background'] = $request
                ->file('background')
                ->store(
                    'homepage/background',
                    'public'
                );
        }

        if ($request->hasFile('registration_background')) {

            if ($content->registration_background) {

                Storage::disk('public')
                    ->delete(
                        $content->registration_background
                    );
            }

            $validated['registration_background'] =
                $request->file('registration_background')
                    ->store(
                        'homepage/registration-background',
                        'public'
                    );
        }
        
        $validated['updated_by'] = auth()->id();

        $content->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'activity' => 'Mengubah Homepage CMS',
            'ip_address' => $request->ip()
        ]);

        return back()->with(
            'success',
            'Homepage berhasil diperbarui.'
        );
    }
}
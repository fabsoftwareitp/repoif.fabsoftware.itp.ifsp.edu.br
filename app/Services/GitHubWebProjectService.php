<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class GitHubWebProjectService
{
    public function store(Request $request)
    {
        $request->validate([
            'url' => ['required', 'string', 'regex:/^https:\/\/github\.com\/[^\/]+\/[^\/]+$/'],
        ]);

        $userId =  Auth::id();

        Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'url' => $request->url,
            'user_id' => $userId,
        ]);

        return redirect(URL::route('project.index'));
    }
}

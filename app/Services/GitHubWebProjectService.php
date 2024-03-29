<?php

namespace App\Services;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class GitHubWebProjectService
{
    public function store(Request $request)
    {
        $request->validate([
            'url' => ['required', 'string', 'max:255', 'regex:/^https:\/\/github\.com\/[^\/]+\/[^\/]+$/'],
        ]);

        $userId =  Auth::id();

        $project = Project::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'url' => $request->url,
            'user_id' => $userId,
        ]);

        return redirect(URL::route('project.show', ['project' => $project->id]));
    }

    public function update(Request $request, Project $project)
    {
        $request->validate([
            'url' => ['required', 'string', 'max:255', 'regex:/^https:\/\/github\.com\/[^\/]+\/[^\/]+$/'],
        ]);

        if ($project->path_web) {
            Storage::delete($project->path);
            Storage::deleteDirectory($project->path_web);
            $project->path = null;
            $project->path_web = null;
            $project->file_name = null;
        }

        $project->title = $request->title;
        $project->description = $request->description;
        $project->url = $request->url;
        $project->save();

        return redirect(URL::route('project.show', ['project' => $project->id]));
    }
}

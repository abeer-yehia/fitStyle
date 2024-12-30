<?php

namespace App\Http\Controllers;

use App\Http\Requests\Section\StoreSectionRequest;
use App\Http\Requests\Section\UpdateSectionRequest;
use App\Http\Resources\SectionResource;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class DashboardSectionController extends Controller
{
    public function index(Request $request)
    {
        $sections = SectionResource::collection(
            Section::query()
            ->with('products')
                ->get()
        );
        return inertia('Dashboard/Section/index', [
            'sections' => $sections
        ]);
    }
    public function create()
    {
        return inertia('Dashboard/Section/create');
    }

    public function store(StoreSectionRequest $request)
    {
      Section::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);
        return redirect()->route('sections.index');
    }

    public function edit(Section $section)
    {
        return inertia('Dashboard/Section/edit', ['section' => $section]);
    }

    public function update(UpdateSectionRequest $request, Section $section)
    {
      $section->update($request->all());
      return redirect()->route('sections.index');
    }

    public function destroy(Section $section)
    {
        $section->delete();
        return redirect()->back();
    }

}

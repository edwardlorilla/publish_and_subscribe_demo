<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tag;
class TagController extends Controller
{
    public function index()
    {
        return response()->json(Tag::orderBy(request('column') ? request('column') : 'updated_at', request('direction') ? request('direction') : 'desc')
            ->search(request('search'))
            ->paginate());
    }

    public function search()
    {
        return response()->json(Tag::search(request('search'))->get());
    }

    public function store(Request $request)
    {
        $input = $request->validate([
            'details' => 'required',
        ]);
        $tag = new Tag;
        $tag->details = $input['details'];
        $tag->save();
        return response()->json($tag);
    }

    public function show(Tag $tag)
    {
        return response()->json($tag);
    }

    public function update(Request $request, Tag $tag)
    {
        $input = $request->validate([
            'details' => 'required',
        ]);
        $tag->details = $input['details'];
        $tag->save();
        return response()->json($tag);
    }

    public function destroy(Tag $tag)
    {
        return response()->json($tag->delete());
    }
}

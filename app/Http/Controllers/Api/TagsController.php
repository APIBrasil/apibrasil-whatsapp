<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tags;

class TagsController extends Controller
{
    public function store(Request $request)
    {
        try {

            $this->validate($request, [
                'name' => 'required|string|max:118|min:1',
                'description' => 'required|max:118|min:1|string',
                'color' => 'nullable|max:900|min:1|string',
            ]);

            $request->merge(['user_id' => $request->user()->id]);
            $tag = Tags::create($request->all());

            return response()->json([
                'status' => 'success',
                'data' => $tag
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => []
            ], 500);

        }
    }

    public function show(Request $request)
    {
        try {

            $tag = Tags::where('user_id', auth()->user()->id)
            ->where('id', $request->tag_id)
            ->firstOrFail();

            return response()->json([
                'error' => false,
                'data' => $tag
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => []
            ], 500);

        }
    }

    public function update(Request $request)
    {
        try {

            $this->validate($request, [
                'name' => 'required|string|max:118|min:1',
                'description' => 'required|max:118|min:1|string',
                'color' => 'nullable|max:900|min:1|string',
            ]);

            $tag = Tags::where('user_id', auth()->user()->id)
            ->where('id', $request->tag_id)
            ->firstOrFail();

            $tag->update($request->all());

            return response()->json([
                'error' => false,
                'data' => $tag
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => []
            ], 500);

        }
    }

    public function destroy(Request $request)
    {
        try {

            $tag = Tags::where('user_id', auth()->user()->id)
            ->where('id', $request->tag_id)
            ->firstOrFail();

            $tag->delete();

            return response()->json([
                'error' => false,
                'data' => $tag
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => []
            ], 500);

        }
    }
}

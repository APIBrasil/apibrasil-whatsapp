<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tags;
use Auth;
class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $tags = Tags::orderBy('id','DESC')
        ->where('user_id', Auth::user()->id)
        ->paginate(10);

        return view('tags.index',compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        try {

            return view('tags.create');


        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {

            $this->validate($request, [
                'name' => 'required',
            ]);

            $tag = new Tags();
            $tag->name = $request->name;
            $tag->description = $request->description;
            $tag->color = $request->color;
            $tag->user_id = $request->user()->id;
            $tag->save();

            return redirect()->route('tags.index')->with('success','Tag created successfully');

        } catch (\Throwable $th) {
            throw $th;


        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {

            $tag = Tags::findOrFail($id);

            return view('tags.edit',compact('tag'));

        } catch (\Throwable $th) {
            throw $th;
        }

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $this->validate($request, [
                'name' => 'required',
            ]);

            $tag = Tags::findOrFail($id);
            $tag->name = $request->name;
            $tag->description = $request->description;
            $tag->color = $request->color;
            $tag->user_id = $request->user()->id;
            $tag->save();

            return redirect()->route('tags.index')->with('success','Tag updated successfully');

        } catch (\Throwable $th) {
            throw $th;


        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        try {

            $tag = Tags::findOrFail($id);
            $tag->delete();

            return redirect()->route('tags.index')->with('success','Tag deleted successfully');

        } catch (\Throwable $th) {
            throw $th;
        }

    }

}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contacts;

class ContactsController extends Controller
{

    public function store(Request $request)
    {
        try {

            $this->validate($request, [
                "phone" => "required|string|max:18|min:12",
                "name"  => "required|string|max:255|min:3",
                "isBusiness" => "nullable|boolean",
                "tag_id" => "nullable",
            ]);

            $request->merge(['user_id' => $request->user()->id]);
            $contact = Contacts::create($request->all());

            return response()->json([
                'status' => 'success',
                'data' => $contact
            ], 200);

        } catch (\Throwable $th) {
            \Log::critical(['Falha no store contacts', $th->getMessage()]);
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function show(Request $request)
    {
        try {

            $contact = Contacts::where('user_id', auth()->user()->id)
            ->where('phone', $request->phone)
            ->first();

            return response()->json([
                'error' => false,
                'data' => $contact
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
                "phone" => "required|string|max:18|min:12",
                "name"  => "required|string|max:255|min:3",
                "isBusiness" => "nullable|boolean",
                "id" => "required|integer",
                "tag_id" => "nullable",
            ]);

            $contact = Contacts::where('user_id', auth()->user()->id)
            ->where('id', $request->id)
            ->firstOrFail();

            $contact->update([
                "phone" => $request->phone,
                "name"  => $request->name,
                "isBusiness" => $request->isBusiness,
                "id" => $request->id,
                "tag_id" => $request->tag_id,
            ]);

            return response()->json([
                'error' => false,
                'data' => $contact
            ], 200);

        } catch (\Throwable $th) {

            return response()->json([
                'error' => true,
                'message' => $th->getMessage(),
                'data' => []
            ], 500);

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
        //
    }
}

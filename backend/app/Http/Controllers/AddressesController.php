<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Address;

class AddressesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $addresses = Address::cursorPaginate(50);

        return response(['status' => 'success','data' => $addresses]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate(Address::$rules, Address::$messages);

        $address = Address::create($request->all());

        return response(['status' => 'success', 'data' => $address], 201);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $address = Address::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response(['status' => 'error', 'message' => 'Endereço não encontrado'], 404);
        }

        $request->validate(Address::$rules, Address::$messages);

        $address->update($request->all());

        return response(['status' => 'success', 'data' => $address]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Address  $address
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $address = Address::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response(['status' => 'error', 'message' => 'Endereço não encontrado'], 404);
        }

        $address->delete();

        return response(['status' => 'success', 'message' => 'Endereço excluído com sucesso'], 204);
    }
}

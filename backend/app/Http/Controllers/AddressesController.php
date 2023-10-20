<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use App\Models\Address;
use Illuminate\Validation\ValidationException;

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

        return response(['status' => 'success', 'data' => $addresses]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // Valida se os campos obrigatórios foram informados
        try {
            $request->validate(Address::$rules, Address::$messages);
        } catch (ValidationException $e) {
            return response(['status' => 'error', 'message' => 'Erro de validação', 'errors' => $e->errors()], 422);
        }

        $address = Address::create($request->all());

        return response(['status' => 'success', 'data' => $address, 'message' => 'Endereço cadastrado com sucesso!'], 201);
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

        // Valida se o endereço existe na base de dados
        try {
            $address = Address::findOrFail($id);
        } catch (ModelNotFoundException $e) {
            return response(['status' => 'error', 'message' => 'Endereço não encontrado'], 404);
        }

        // Valida se os campos obrigatórios foram informados
        try {
            $request->validate(Address::$rules, Address::$messages);
        } catch (ValidationException $e) {
            return response(['status' => 'error', 'message' => 'Erro de validação', 'errors' => $e->errors()], 422);
        }

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

        return response(null, 204)->header('X-Status-Message', 'Endereço excluído com sucesso');
    }
}

<?php

namespace App\Http\Controllers;

use App\Actions\ViaCep\ViaCep;
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
     * Display a listing of the searched resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search_cep(Request $request, $cep)
    {
        $viacep = new ViaCep();

        // Verifica se o CEP possui o formato válido
        if (!preg_match('/^\d{5}-\d{3}$/', $cep)) {
            return response(['status' => 'error', 'message' => 'Formato de CEP inválido. Use conforme o exemplo: 83215-360'], 400);
        }

        // Tente encontrar o endereço localmente
        $localAddress = Address::where('zip_code', $cep)->first();

        if ($localAddress) {
            return response(['status' => 'success', 'data' => $localAddress], 200);
        } else {

            // Se não encontrado localmente, tente obter o endereço externo
            $externalAddress = $viacep->getAddress($cep);

            if ($externalAddress === false || isset($externalAddress->erro)) {
                return response(['status' => 'error', 'message' => 'Endereço não encontrado!'], 404);
            }

            // Cadastra o endereço normalizado localmente
            $address = new Address();
            $address->zip_code = $externalAddress->cep;
            $address->street = $externalAddress->logradouro;
            $address->complement = $externalAddress->complemento;
            $address->district = $externalAddress->bairro;
            $address->city = $externalAddress->localidade;
            $address->uf = $externalAddress->uf;
            $address->save();

            return response(['status' => 'success', 'data' => $address], 200);
        }
    }

    /**
     * Display a listing of the searched resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $term = $request->input('term');

        $results = Address::where(function ($query) use ($term) {
            $query->where('zip_code', 'LIKE', "%$term%")
                ->orWhere('street', 'LIKE', "%$term%")
                ->orWhere('number', 'LIKE', "%$term%")
                ->orWhere('complement', 'LIKE', "%$term%")
                ->orWhere('district', 'LIKE', "%$term%")
                ->orWhere('city', 'LIKE', "%$term%")
                ->orWhere('uf', 'LIKE', "%$term%");
        })->get();

        if ($results->isEmpty()) {
            return response(["status" => "error", "message" => "Nenhum resultado encontrado!"], 404);
        } else {
            return response(["status" => "success", "data" => $results], 200);
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

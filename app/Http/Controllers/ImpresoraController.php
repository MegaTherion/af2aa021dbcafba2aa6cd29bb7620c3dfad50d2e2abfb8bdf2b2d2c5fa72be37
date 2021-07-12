<?php

namespace App\Http\Controllers;

use App\Mapper\ImpresoraMapper;
use App\Models\Impresora;
use App\Repositories\ImpresoraRepository;
use App\Repositories\ImpresoraRepositoryInterface;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ImpresoraController extends Controller
{
    private ImpresoraRepository $impresoraRepository;

    public function __construct(ImpresoraRepositoryInterface $impresoraRepository)
    {
        $this->impresoraRepository = $impresoraRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json([
            'success' => true,
            'data' => ImpresoraMapper::collectionToImpresorasResponse($this->impresoraRepository->all())
        ]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Impresora  $impresora
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $impresora = $this->impresoraRepository->find($id);
            $impresora = $this->impresoraRepository->consultar($impresora);
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Modelo no encontrado']);
        }
        // dd($impresora);
        return response()->json(ImpresoraMapper::modelToImpresoraResponse($impresora));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Impresora  $impresora
     * @return \Illuminate\Http\Response
     */
    public function edit(Impresora $impresora)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Impresora  $impresora
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Impresora $impresora)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Impresora  $impresora
     * @return \Illuminate\Http\Response
     */
    public function destroy(Impresora $impresora)
    {
        //
    }

    public function terminarProduccion($id)
    {
        try {
            $impresora = $this->impresoraRepository->find($id);
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Modelo no encontrado']);
        }
        $impresora = $this->impresoraRepository->terminarProduccion($impresora);
        return response()->json(ImpresoraMapper::modelToImpresoraResponse($impresora));
    }
    
}

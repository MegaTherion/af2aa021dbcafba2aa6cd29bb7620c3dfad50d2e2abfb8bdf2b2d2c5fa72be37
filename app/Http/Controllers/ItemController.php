<?php

namespace App\Http\Controllers;

use App\Helpers\DataConverter;
use App\Http\Resources\ItemResource;
use App\Mapper\ImpresoraMapper;
use App\Mapper\ItemMapper;
use App\Models\Item;
use App\Repositories\ItemRepository;
use App\Repositories\ItemRepositoryInterface;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    private ItemRepository $itemRepository;

    public function __construct(ItemRepositoryInterface $itemRepository)
    {
        $this->itemRepository = $itemRepository;
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
            'data' => ItemMapper::collectionToItemsResponse($this->itemRepository->all())
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
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $item = $this->itemRepository->find($id);
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Modelo no encontrado']);
        }
        return response()->json(ItemMapper::modelToItemResponse($item, true));
        // $r = new ItemResource($item);
        // $r['success'] = true;
        // return response()->json($r);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function edit(Item $item)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Item  $item
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item)
    {
        //
    }

    public function bulkLoad(Request $request)
    {
        // $request->fil
        $this->itemRepository->loadItems(
            DataConverter::fromCsvToArray($request->file('archivo'))
        );
        return response()->json(['ok'=>'listo!']);
    }

    public function calculateTimeProduction($id)
    {
        try {
            $item = $this->itemRepository->find($id);
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Modelo no encontrado']);
        }
        $data = $this->itemRepository->calculateTimeProductionAndItems($item, 1);
        return response()->json([
            'success'=>true,
            'data' => $data,
        ]);
    }

    public function executeProduction($id) 
    {
        try {
            $item = $this->itemRepository->find($id);
        }
        catch (ModelNotFoundException $e) {
            return response()->json(['success' => false, 'message' => 'Modelo no encontrado']);
        }
        try {
            $impresora = $this->itemRepository->executeProduction($item);
        }
        catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
        return response()->json([
            'success'=>true,
            'data' => ImpresoraMapper::modelToImpresoraResponse($impresora)
        ]);
    }
}

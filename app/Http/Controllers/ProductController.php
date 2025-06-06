<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Services\JSONStorageService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class ProductController extends Controller
{

    public $storageService;
    public const FILENAME = "products";
    public function __construct(JSONStorageService $storageService)
    {
        $this->storageService =  $storageService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $products = $this->storageService->read(self::FILENAME);
        $totalValue = collect($products)->sum(fn($p) => $p['price'] * $p['quantity']);

        return view('products.index', compact('products', 'totalValue'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductRequest $request)
    {
        $products = $this->storageService->save($request->validated(), self::FILENAME);
        $totalValue = collect($products)->sum(fn($p) => $p['price'] * $p['quantity']);
        $status = Response::HTTP_OK;
        $response = compact('products', 'totalValue', 'status');

        $view =  view('products.index', $response);
        return $this->getResponse($request, $response, $view);
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
        //
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
        //
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

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Repositories\ItemRepository;
use App\Http\Resources\ItemResource;

class ItemController extends Controller
{
    /**
     * Service contrainers.
     */
    protected $item;

    /**
     * Create a new controller instance.
     * 
     * @param App\Repositories\ItemRepository  $item
     *
     * @return void
     */
    public function __construct(ItemRepository  $item)
    {
        $this->item = $item;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return ItemResource::collection($this->item->list());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return new ItemResource($this->item->create(
            $request->input('name'),
            $request->input('color'),
            $request->input('length')
        ));
    }

    /**
     * Display the specified resource.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     * 
     */
    public function show($id)
    {
        $res = $this->item->get($id);
        return $res ? new ItemResource($res) : response()->json([ 'status' => FALSE ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  $id
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return response()->json([
            'status' => $this->item->update(
                $id,
                $request->input('name'),
                $request->input('color'),
                $request->input('length')
            ),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json([
            'status' => $this->item->delete($id),
        ]);
    }
}

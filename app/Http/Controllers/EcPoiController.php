<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEcPoiRequest;
use App\Http\Requests\UpdateEcPoiRequest;
use App\Models\EcPoi;

class EcPoiController extends Controller
{
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreEcPoiRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEcPoiRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EcPoi  $ecPoi
     * @return \Illuminate\Http\Response
     */
    public function show(EcPoi $ecPoi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EcPoi  $ecPoi
     * @return \Illuminate\Http\Response
     */
    public function edit(EcPoi $ecPoi)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEcPoiRequest  $request
     * @param  \App\Models\EcPoi  $ecPoi
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEcPoiRequest $request, EcPoi $ecPoi)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EcPoi  $ecPoi
     * @return \Illuminate\Http\Response
     */
    public function destroy(EcPoi $ecPoi)
    {
        //
    }
}

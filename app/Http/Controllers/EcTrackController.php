<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEcTrackRequest;
use App\Http\Requests\UpdateEcTrackRequest;
use App\Models\EcTrack;

class EcTrackController extends Controller
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
     * @param  \App\Http\Requests\StoreEcTrackRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEcTrackRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EcTrack  $ecTrack
     * @return \Illuminate\Http\Response
     */
    public function show(EcTrack $ecTrack)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EcTrack  $ecTrack
     * @return \Illuminate\Http\Response
     */
    public function edit(EcTrack $ecTrack)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateEcTrackRequest  $request
     * @param  \App\Models\EcTrack  $ecTrack
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateEcTrackRequest $request, EcTrack $ecTrack)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EcTrack  $ecTrack
     * @return \Illuminate\Http\Response
     */
    public function destroy(EcTrack $ecTrack)
    {
        //
    }
}

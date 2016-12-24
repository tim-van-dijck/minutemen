<?php

namespace App\Http\Controllers;

use App\Organisation;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.organisations')->with([
            'organisations' => Organisation::where('trusted',0)->orderBy('created_at')->limit(10)->get()
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $organisation_id
     * @return \Illuminate\Http\Response
     */
    public function trust($organisation_id)
    {
        if (Auth::user()->isAdmin()) {
            $org = Organisation::find($organisation_id);

            $org->trusted = 1;
            $org->save();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $organisation_id
     * @return \Illuminate\Http\Response
     */
    public function revokeTrust($organisation_id)
    {
        if (Auth::user()->isAdmin()) {
            $org = Organisation::find($organisation_id);

            $org->trusted = 0;
            $org->save();
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $organisation_id
     * @return \Illuminate\Http\Response
     */
    public function destroy($organisation_id)
    {
        Organisation::destroy($organisation_id);
    }
}

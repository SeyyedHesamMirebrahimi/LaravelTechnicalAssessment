<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Http\Requests\Panel\LabelStoreRequest;
use App\Model\Label;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('panel.label.index' , [
            'labels' => Label::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Application|Factory|View
     */
    public function create()
    {
        return view('panel.label.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param LabelStoreRequest $request
     * @return Application|RedirectResponse|Redirector
     */
    public function store(LabelStoreRequest $request)
    {
        Label::create($request->validated());
        return redirect(route('label.index'))->with('success', 'New Label Added');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function show($id)
    {
        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return Application|Factory|View
     */
    public function edit(int $id)
    {
        return view('panel.label.edit', ['label' => Label::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param  int  $id
     * @return Application|RedirectResponse|Response|Redirector
     */
    public function update(LabelStoreRequest $request, $id)
    {
        Label::findOrFail($id)->update($request->validated());
        return redirect(route('label.index'))->with('success', 'New Label Updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return RedirectResponse
     */
    public function destroy($id)
    {
        Label::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'New Label Deleted');
    }
}

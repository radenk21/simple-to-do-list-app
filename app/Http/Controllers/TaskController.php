<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tasks = Task::all();
        return view('index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Task::create($request->validate([
            'list' => 'required'
        ]));
        return back()->with('success-store', 'Berhasil memasukkan task!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        Task::find($id)->update($request->validate([
            'list' => 'required',
        ]));

        return redirect()->back()->with('success', 'Berhasil mengedit task!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Task::find($id)->delete();

        return redirect()->back()->with('success', 'Berhasil menghapus task!');
    }
    public function updateStatus(Request $request, $id)
    {
        // dd('berhasil');
        $task = Task::findOrFail($id);
        $task->status = $request->input('status');
        $task->save();

        return response()->json(['status' => $task->status]);
    }
}

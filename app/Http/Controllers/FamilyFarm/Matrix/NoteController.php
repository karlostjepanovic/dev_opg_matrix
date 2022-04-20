<?php

namespace App\Http\Controllers\FamilyFarm\Matrix;

use App\Http\Controllers\Controller;
use App\Models\App\FamilyFarm;
use App\Models\FamilyFarm\Matrix;
use App\Models\FamilyFarm\Matrix\Note;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function createNote(Request $request)
    {
        $request->validate([
            'date'  => 'required|date_format:d.m.Y.',
            'note'  => 'required',
        ]);
        if(strtotime($request->date) > strtotime("today")){
            return response()->json([
                'message' => 'Datum bilješke mora biti manji ili jednak današnjem datumu.'
            ], 422);
        }
        try {
            Note::create([
                'date' => date_format(date_create($request->date), "Y-m-d"),
                'note' => $request->note,
                'matrix_id' => session('matrix')['id'],
                'user_id' => Auth::id()
            ]);
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        return response()->json([
            'success' => 'Bilješka je uspješno spremljena.'
        ], 200);
    }

    public function editNote($id, Request $request)
    {
        $request->validate([
            'date'  => 'required|date_format:d.m.Y.',
            'note'  => 'required',
        ]);
        if(strtotime($request->date) > strtotime("today")){
            return response()->json([
                'message' => 'Datum bilješke mora biti manji ili jednak današnjem datumu.'
            ], 422);
        }
        if(!Matrix::find(session('matrix')['id'])->notes->contains($id)){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        try {
            $note = Note::find($id);
            $note->update([
                'date' => date_format(date_create($request->date), "Y-m-d"),
                'note' => $request->note,
                'user_id' => Auth::id()
            ]);
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        return response()->json([
            'success' => 'Promjene su uspješno spremljene.'
        ], 200);
    }

    public function deleteNote($id, Request $request)
    {
        if(!Matrix::find(session('matrix')['id'])->notes->contains($id)){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        try {
            Note::destroy($id);
        }catch (QueryException $e){
            return response()->json([
                'message' => 'Dogodila se greška.'
            ], 422);
        }
        return response()->json([
            'success' => 'Bilješka je uspješno obrisana!'
        ], 200);
    }
}

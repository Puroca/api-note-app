<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    // Create a Note

    public function create(Request $request)
    {
        $validData = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string',
            'authorId' => 'required|integer'
        ]);

        if (!$validData) {
            response()->json([
                'message' => 'Invalid values.'
            ], 422);
        }

        $note = new Note();

        $note->title = $validData["title"];
        $note->content = $validData["content"];
        $note->user_id = auth()->id();

        $note->save();

        if (!$note) {
            return response()->json([
                'message' => 'Création de note échouée.'
            ], 400);
        }

        return response()->json([
            'message' => 'Note créée avec succès.'
        ], 201);
    }

    public function getOne($id)
    {
        $userId = auth()->id();
        $note = Note::where('id', $id)
                    ->where('user_id', $userId)
                    ->first();

        if (!$note) {
            return
                response()->json([
                    "message" => "Note doesn't exist."
                ], 404);
        }

        return
            response()->json([
                "message" => "Note successfully gotten",
                "note" => $note,
            ], 200);
    }

    public function getAll()
    {
        $userId = auth()->id();
        $notes = Note::where('user_id', $userId)->get();

        if (count($notes) <= 0
        ) {
            return
                response()->json([
                    "message" => "Notes don't exist."
                ], 404);
        }

        return
            response()->json([
                "message" => "Notes successfully gotten",
                "notes" => $notes,
            ], 200);
    }

    public function update(Request $request, $id)
    {
        $validData = $request->validate([
            'title' => 'required|string',
            'content' => 'required|string'
        ]);

        if (!$validData) {
            return response()->json([
                'message' => 'Invalid values.'
            ], 422);
        }

        $userId = auth()->id();

        $note = Note::where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$note) {
            return
                response()->json([
                    'message' => "This note doesn't exist."
                ], 404);
        }

        $note->title = $validData["title"];
        $note->content = $validData["content"];

        $note->save();

        return
            response()->json([
                'message' => "Note updated successfully.",
                'note' =>$note
            ], 200);
    }

    public function delete ($id){
        $userId = auth()->id();
        $note = Note::where('id', $id)
                    ->where('user_id', $userId)
                    ->first();
        
        if(!$note){
            return response()->json([
                'message'=>"Cette note n'existe pas.",
            ], 404);
        }

        $note->delete();
        return response()->json([
            'message' => "Note supprimée avec succès.",
        ], 200);
    }
}

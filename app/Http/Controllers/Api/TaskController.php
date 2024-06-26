<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;




class TaskController extends Controller
{
//========================================================================================
        function index()
    {
        $totalTasks = Task::all();

        return response()->json([
            'Tasks' => $totalTasks,
            'status' => 200,
            'message' => 'Tout les Taches ont été recupérés'
        ]);
    }
//========================================================================================
        function show($id){
        return response()->json([
            'Task'=> Task::find($id),
            'status'=> 200,
            'message'=> 'Tâche récupérée avec succès'
        ]);
    }
//========================================================================================

public function store(StoreTaskRequest $request)
{
    $task = new Task();
    $task->titre = $request->titre;
    $task->description = $request->description ?? null;
    $task->user_id = auth()->user()->id;

    $task->statut = $request->statut ?? 'en attente';
    $task->date_echeance = $request->date_echeance;
    $task->save();

    return response()->json([
        'task' => $task,
        'status' => 200,
        'msg' => 'Tâche insérée avec succès',
        'date_d_ajout' => $task->created_at->format('Y-m-d H:i:s')
    ]);
}


//========================================================================================
public function update(UpdateTaskRequest $request, $id)
{
    // trouver la tache by ID
    $task = Task::find($id);

    // verifier que task exists
    if (!$task) {
        return response()->json([
            'status' => 404,
            'msg' => 'Tâche non trouvée - non existante'
        ], 404);
    }

    // Update 
    $task->titre = $request->titre;
    $task->description = $request->description ?? $task->description;
    $task->statut = $request->statut ?? $task->statut;
    $task->date_echeance = $request->date_echeance;
    $task->save();

    return response()->json([
        'task' => $task,
        'status' => 200,
        'msg' => 'Tâche mise à jour avec succès',
        'date_d_ajout' => $task->updated_at->format('Y-m-d H:i:s') 
    ]);
}
//========================================================================================
public function destroy($id)
{
    // trouver la tache by ID
    $task = Task::find($id);

    // Check if task exists
    if (!$task) {
        return response()->json([
            'status' => 404,
            'msg' => 'Tâche non trouvée - non existante'
        ], 404);
    }

    // Soft delete \
    $task->delete();

    return response()->json([
        'status' => 200,
        'msg' => 'Tâche supprimée avec succès'
    ]);
}
//========================================================================================
public function getDeletedTasks()
{
    // Récupérer toutes les tâches supprimées (soft deleted)
    $deletedTasks = Task::onlyTrashed()->get();

    if ($deletedTasks->isEmpty()) {
        return response()->json([
            'status' => 404,
            'msg' => 'Aucune tâche supprimée trouvée'
        ], 404);
    }

    return response()->json([
        'deleted_tasks' => $deletedTasks,
        'status' => 200,
        'msg' => 'Liste des tâches supprimées récupérée avec succès'
    ]);
}
}

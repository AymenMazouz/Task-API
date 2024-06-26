<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Support\Facades\Auth;




class TaskController extends Controller
{

//=============================================================================================================================================        
public function index()
{
    // Check if the authenticated user is an admin
    if (Auth::user()->role === 'admin') {
        // For admins: Retrieve all tasks, including soft deleted ones
        $tasks = Task::withTrashed()->get();
    } else {
        // For regular users: Retrieve non-deleted tasks
        $tasks = Task::whereNull('deleted_at')->get();
    }

    return response()->json([
        'Tasks' => $tasks,
        'status' => 200,
        'message' => 'Toutes les tâches ont été récupérées'
    ]);
}




//=============================================================================================================================================
public function show($id)
{
    $task = Task::where('id', $id)->whereNull('deleted_at')->first();

    if (!$task) {
        return response()->json([
            'status' => 404,
            'msg' => 'Tâche non trouvée ou a été supprimée'
        ]);
    }

    if ($task->user_id == auth()->user()->id || auth()->user()->role == 'admin') {
        return response()->json([
            'Task' => $task,
            'status' => 200,
            'message' => 'Tâche récupérée avec succès'
        ]);
    } else {
        return response()->json([
            'status' => 403,
            'msg' => 'Vous n\'êtes pas le propriétaire de cette tâche ou n\'avez pas les permissions nécessaires'
        ]);
    }
}




//=============================================================================================================================================
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




//=============================================================================================================================================
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

   if ($task->user_id == auth()->user()->id || auth()->user()->role == 'admin') {
    $task->save();
} else {
    return response()->json([
        'status' => 403,
        'msg' => 'Vous n\'êtes pas le propriétaire de cette tâche ou n\'avez pas les permissions nécessaires',
    ]);
}


    return response()->json([
        'task' => $task,
        'status' => 200,
        'msg' => 'Tâche mise à jour avec succès',
        'date_d_ajout' => $task->updated_at->format('Y-m-d H:i:s') 
    ]);
}



//=============================================================================================================================================
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
if ($task->user_id == auth()->user()->id || auth()->user()->role == 'admin') {


    // Soft delete \
    $task->delete();

} else {
    return response()->json([
        'status' => 403,
        'msg' => 'Vous n\'êtes pas le propriétaire de cette tâche ou n\'avez pas les permissions nécessaires',
    ]);
}
    return response()->json([
        'status' => 200,
        'msg' => 'Tâche supprimée avec succès'
    ]);
}



//=============================================================================================================================================
public function getDeletedTasks()
{
    // Check if the authenticated user is an admin
    if (auth()->user()->role == 'admin') {
        // Retrieve all soft deleted tasks
        $deletedTasks = Task::onlyTrashed()->get();

        if ($deletedTasks->isEmpty()) {
            return response()->json([
                'status' => 200,
                'msg' => 'Aucune tâche supprimée trouvée'
            ], 200);
        }

        return response()->json([
            'deleted_tasks' => $deletedTasks,
            'status' => 200,
            'msg' => 'Liste des tâches supprimées récupérée avec succès'
        ]);
    } else {
        // Return forbidden status for non-admin users
        return response()->json([
            'status' => 403,
            'msg' => 'Vous n\'avez pas les permissions nécessaires',
        ], 403);
    }
}

}

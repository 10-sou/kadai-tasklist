<?php

namespace App\Http\Controllers;
use App\Models\Task;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\TasksControllers;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
       if (\Auth::check()) {
        
        $user = \Auth::user(); // 認証されたユーザーを取得
        
        // ユーザーのタスクを作成日時の降順で取得
        $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
        
        // データ配列にユーザー情報とタスクを格納
        $data = [
            'user' => $user,
            'tasks' => $tasks,
        ];
       
    
    //Message::all(); で $messages に入ったデータをViewに渡すため
    // dashboardビューでそれらを表示
    return view('tasks.index', $data);
    }
    
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $task = new Task;
        $user = \Auth::user();

    return view('tasks.create', [
        'task' => $task,
        'user' => $user,
    ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // バリデーション
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255',
        ]);

        // タスクを作成
        $task = new Task;
        $task->status = $request->status;
        $task->content = $request->content;
        $task->user_id = auth()->id(); // ログイン中のユーザーIDを設定
        $task->save();

        // トップページへリダイレクト
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $task = Task::findOrFail($id);
    
        // 他人のタスクにアクセスしようとした場合
        if ($task->user_id !== auth()->id()) {
            return redirect()->route('tasks.index');
        }
        
        $user = auth()->user(); 
        
        // 自分のタスクの場合、詳細を表示
        return view('tasks.show', [
            'task' => $task,
            'user' => $user,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $task = Task::findOrFail($id);
        
        // 他人のタスクにアクセスしようとした場合
        if ($task->user_id !== auth()->id()) {
            return redirect()->route('tasks.index');
        }
        
        $user = \Auth::user();
        
        return view('tasks.edit', [
            'task' => $task,
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255'
        ]);
        
        
        $task = Task::findOrFail($id);
        
        // 他人のタスクにアクセスしようとした場合
        if ($task->user_id !== auth()->id()) {
            return redirect()->route('tasks.index');
        }
        
        $task->status = $request->status;
        $task->content = $request->content;
        $task->save();

        
        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);
        
       // 他人のタスクにアクセスしようとした場合
        if ($task->user_id !== auth()->id()) {
            return redirect()->route('tasks.index');
        }
        
        $task->delete();

        return redirect()->route('tasks.index');
    }
}

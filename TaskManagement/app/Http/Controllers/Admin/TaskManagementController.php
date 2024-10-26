<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;
use Illuminate\Container\Attributes\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;

class TaskManagementController extends Controller
{
    public function index(Request  $request)
    {
        $data['users'] = User::where('role', '!=', 'admin')->get();
        $data['lists'] = $this->generateQuery($request)->orderBy('created_at', 'desc')->paginate(20);

        return view('admin.pages.list', $data);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'due_date' => 'required|date|after_or_equal:' . now()->toDateString(),
            'priority' => 'required|in:high,medium,low',
            'status' => 'required|in:pending,in_progress,completed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        try {
            $userId = auth()->id();
            $data['created_by'] = $userId;
            $task = Task::create($data);
            if (!empty($data['assigned_to'])) {
                $assignedUser = User::find($data['assigned_to']);
                if ($assignedUser) {
                    $assignedUser->notify(new TaskAssigned($task));
                }
            }
            return redirect()->back()->with('success', 'Task created successfully');
        } catch (\Exception $e) {
            return back()->with('error', handleExceptionMessage($e))->withInput();
        }
    }

    public function edit($id)
    {

        try {
            $data['detail'] = Task::with('assignee')->findOrFail($id);
            $data['users'] = User::where('role', '!=', 'admin')->get();

            $response['value'] = (string)View::make('admin.pages.edit', $data);
            $response['code'] = 200;
            $response['success'] = true;
            $response['msg'] = 'task detail received';
        } catch (\Exception $e) {
            $response['code'] = 400;
            $response['success'] = false;
            $response['msg'] = handleExceptionMessage($e);
        }
        return response()->json($response);
    }
    public function update(Request $request, $id)
    {
        // printData($request->toArray());
        // die();
        $data = $request->validate([
            'title' => 'required|max:255',
            'description' => 'required',
            'due_date' => 'required|date|after_or_equal:' . now()->toDateString(),
            'priority' => 'required|in:high,medium,low',
            'status' => 'required|in:pending,in_progress,completed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        try {
            $task = Task::findOrFail($id);

            $task->update($data);
            $assignedUser = User::find($data['assigned_to']);
            if ($assignedUser) {
                $assignedUser->notify(new TaskAssigned($task));
            }

            return redirect()->back()->with('success', 'Task Updated successfully');
        } catch (\Exception $e) {
            return back()->with('error', handleExceptionMessage($e))->withInput();
        }
    }



    public function taskUpdate(Request $request, $id)
    {
        $data = $request->validate([
            'due_date' => 'sometimes|date|after_or_equal:' . now()->toDateString(),
            'priority' => 'sometimes|in:high,medium,low',
            'status' => 'sometimes|in:pending,in_progress,completed',
            'assigned_to' => 'nullable|exists:users,id',
        ]);

        try {
            $task = Task::findOrFail($id);

            if ($request->has('priority')) {
                $task->priority = $data['priority'];
            }
            if ($request->has('status')) {
                $task->status = $data['status'];
            }
            if ($request->has('due_date')) {
                $task->due_date = $data['due_date'];
            }
            if ($request->has('assigned_to')) {
                $task->assigned_to = $data['assigned_to'];
                if (!empty($data['assigned_to'])) {
                    $assignedUser = User::find($data['assigned_to']);
                    if ($assignedUser) {
                        $assignedUser->notify(new TaskAssigned($task));
                    }
                }
            }

            $task->save();

            return response()->json(['message' => 'Success']);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Error', 'error' => $e->getMessage()], 500);
        }
    }


    public function destroy($id)
    {
        try {
            Task::where([
                ['id', $id],
            ])->delete();
            return redirect()->back()->with('success', "Task deleted successfully.");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', handleExceptionMessage($e));
        }
    }
    private function generateQuery($request)
    {
        $query = Task::with('creator', 'assignee');

        if ($request->filled('keyword')) {
            $keyword = $request->get('keyword');
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', '%' . $keyword . '%');
            });
        }

        if ($request->filled('status') && in_array($request->get('status'), ['pending', 'in_progress', 'completed'])) {
            $query->where('status', $request->get('status'));
        }


        if ($request->filled('due_date')) {
            $dueDate = $request->get('due_date');
            switch ($dueDate) {
                case 'recent':
                    $query->whereDate('due_date', '>=', Carbon::now()->subWeek());
                    break;
                case 'last_two_weeks':
                    $query->whereBetween('due_date', [Carbon::now()->subWeeks(2), Carbon::now()]);
                    break;
                case 'last_month':
                    $query->whereBetween('due_date', [Carbon::now()->subMonth(), Carbon::now()]);
                    break;
                case 'last_three_months':
                    $query->whereBetween('due_date', [Carbon::now()->subMonths(3), Carbon::now()]);
                    break;
                case 'last_six_months':
                    $query->whereBetween('due_date', [Carbon::now()->subMonths(6), Carbon::now()]);
                    break;
                default:
                    $query->whereDate('due_date', $dueDate);
                    break;
            }
        }

        if ($request->filled('priority') && in_array($request->get('priority'), ['high', 'medium', 'low'])) {
            $query->where('priority', $request->get('priority'));
        }

        return $query;
    }
}

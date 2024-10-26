<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request  $request)
    {
        $data['loggedInUserId'] = auth()->user()->id;

        $data['users'] = User::where('role', '!=', 'admin')->get();
        $data['lists'] = $this->generateQuery($request)->orderBy('created_at', 'desc')->paginate(20);
    
        return view('home', $data);
    }
    
    public function delete($id)
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

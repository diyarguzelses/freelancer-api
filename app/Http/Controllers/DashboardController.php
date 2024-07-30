<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function getMonthlyJobVolume()
    {
        $data = DB::table('tasks')
            ->select(DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'), DB::raw('COUNT(*) as total_volume'))
            ->where('created_at', '>=', DB::raw('DATE_SUB(CURDATE(), INTERVAL 12 MONTH)'))
            ->groupBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->orderBy(DB::raw('DATE_FORMAT(created_at, "%Y-%m")'))
            ->get();

        return response()->json(['data' => $data], 200);

    }
    public function getTopFreelancerLastMonth()
    {
        $topFreelancer = DB::table('tasks')
            ->select('user_id', DB::raw('COUNT(*) as total_volume'))
            ->whereBetween('created_at', [
                DB::raw('DATE_FORMAT(CURDATE() - INTERVAL 1 MONTH, "%Y-%m-01")'),
                DB::raw('DATE_FORMAT(CURDATE(), "%Y-%m-01")')
            ])
            ->groupBy('user_id')
            ->orderBy('total_volume', 'DESC')
            ->first();

        if (!$topFreelancer) {
            return response()->json(['error' => 'No tasks found for the last month'], 404);
        }

        $user = User::find($topFreelancer->user_id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        return response()->json([
            'freelancer' => $user,
            'total_volume' => $topFreelancer->total_volume
        ], 200);
    }
}

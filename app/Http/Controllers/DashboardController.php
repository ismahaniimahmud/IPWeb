<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $actions = Action::query()->orderBy('action_code')->get();

        return view('dashboard', [
            'actions' => $actions,
            'recentActions' => $actions->take(6),
            'statusCounts' => $actions->countBy('status'),
            'priorityCounts' => $actions->countBy('priority'),
            'themeCounts' => $actions->countBy('theme')->sortDesc()->take(5),
            'openThemeCounts' => $actions->where('status', '!=', 'Selesai')->countBy('theme')->sortDesc()->take(6),
            'meetingCounts' => $actions->countBy(fn (Action $action): string => $action->meeting_date
                ? Carbon::parse($action->getRawOriginal('meeting_date'))->format('d M')
                : 'Tiada tarikh'),
        ]);
    }
}

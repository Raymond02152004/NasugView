<?php

namespace App\Http\Controllers\Negosyo;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Signup;
use App\Models\BusinessOwnerAccount;
use App\Models\Form;
use App\Models\Response;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $consumerCount = Signup::where('role', 'consumer')->count();
        $businessOwnerCount = BusinessOwnerAccount::count();

        $businessTypeCounts = BusinessOwnerAccount::select('business_type', DB::raw('count(*) as count'))
            ->groupBy('business_type')
            ->pluck('count', 'business_type');

        // Filters
        $month = $request->query('month');
        $title = $request->query('title');
        $selectedYear = $request->query('year') ?? now()->year;

        // Event Count
        $eventQuery = Form::query();
        if ($month) {
            $eventQuery->whereMonth('created_at', $month);
        }
        $eventCount = $eventQuery->count();

        // Attendee Count
        $attendeeQuery = Response::query();
        if ($title) {
            $attendeeQuery->whereHas('form', function ($query) use ($title) {
                $query->whereRaw('LOWER(title) = ?', [strtolower($title)]);
            });
        }
        $attendeeCount = $attendeeQuery->count();

        // Monthly Event Data (Line Chart) by Year
        $monthlyEvents = Form::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', $selectedYear)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month');

        $eventMonthlyData = collect(range(1, 12))->mapWithKeys(function ($m) use ($monthlyEvents) {
            return [$m => $monthlyEvents->get($m, 0)];
        });

        // Year options for year dropdown
        $availableYears = Form::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view('negosyo.dashboard', compact(
            'consumerCount',
            'businessOwnerCount',
            'businessTypeCounts',
            'eventCount',
            'attendeeCount',
            'eventMonthlyData',
            'selectedYear',
            'availableYears'
        ));
    }

    // AJAX: /negosyo/events/filter?month=07
    public function filterEvents(Request $request)
    {
        $month = $request->query('month');

        $eventCount = Form::when($month, function ($query) use ($month) {
            $query->whereMonth('created_at', $month);
        })->count();

        return response()->json(['count' => $eventCount]);
    }

    // AJAX: /negosyo/attendees/filter?title=EventTitle
    public function filterAttendees(Request $request)
    {
        $title = $request->query('title');

        $attendeeCount = Response::when($title, function ($query) use ($title) {
            $query->whereHas('form', function ($formQuery) use ($title) {
                $formQuery->whereRaw('LOWER(title) = ?', [strtolower($title)]);
            });
        })->count();

        return response()->json(['count' => $attendeeCount]);
    }
}

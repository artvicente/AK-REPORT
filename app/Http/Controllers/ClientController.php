<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB; // Para sa DB query
use App\Models\ProjectStat; // Para sa ProjectStats
use App\Models\TotalMembers; // (Opsyonal, kung gagamit ka ng Eloquent)
use Illuminate\Http\Request;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // Client Dashboard (View Only)
    public function index()
    {

        // 1. KUNIN ANG PROJECT STATS (Project Stats table)
        $projectStats = ProjectStat::all();

        // 2. KUNIN ANG DASHBOARD DATA (Total Members table)
        $membersData = DB::table('total_members')
                            ->orderBy('year', 'desc') // Para 2025 muna bago 2022
                            ->get();

        // 3. I-group ang data ayon sa 'year' at i-compute ang 'grand totals'
        $stats = $membersData->groupBy('year')->map(function ($yearGroup) {
            // I-initialize ang totals para sa bawat taon
            $yearlyTotals = [
                'year' => $yearGroup->first()->year,
                'total_bkk' => 0,
                'total_akm' => 0,
                'total_ako' => 0,
            ];

            // I-loop ang mga row sa loob ng taon (e.g., BKK, AKM, AKO entries ng 2025)
            foreach ($yearGroup as $member) {
                if ($member->type === 'BKK') {
                    $yearlyTotals['total_bkk'] = $member->total;
                } elseif ($member->type === 'AKM') {
                    $yearlyTotals['total_akm'] = $member->total;
                } elseif ($member->type === 'AKO') {
                    $yearlyTotals['total_ako'] = $member->total;
                }
            }
            return (object) $yearlyTotals; // Gawing object para gumana sa ->year, ->total_bkk, etc.
        });

        // 4. I-compute ang Grand Totals across all years
        $grandTotals = [
            'bkk' => $stats->sum('total_bkk'),
            'akm' => $stats->sum('total_akm'),
            'ako' => $stats->sum('total_ako'),
        ];

        // Siguraduhin na ang $stats ay isang collection ng objects, at hindi collection ng groups.
        $stats = $stats->values();

        // 5. Ibalik ang view kasama ang lahat ng data
        return view('client.dashboard', [
            'projectStats' => $projectStats,
            'stats' => $stats, // Ito ang members database data na naka-format na
            // 'membersDatabase' => $rawData, // Ito ang data sa loob ng table
            'grandTotals' => $grandTotals, // Ito ang kabuuan
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProjectStat;
use App\Models\TotalMembers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ProjectStatController extends Controller
{
    // Tiyakin na Admin lang ang makakapasok
    public function __construct()
    {
        $this->middleware('auth');
        // Pwede kang gumawa ng sarili mong AdminMiddleware, pero sa ngayon, i-assume natin na ang route group ang hahawak nito.
    }

    // Display Admin Dashboard/List
    public function index()
    {
        $projectStats = ProjectStat::all(); // Gagamitin para sa Table 1

        // 2. KUNIN ANG DASHBOARD DATA (Total Members table)
        // Ito ang aggregated data na lalabas sa Table 2 sa dashboard
        $aggregatedMembersData = DB::table('total_members')
            // I-group ang resulta ayon sa taon
            ->groupBy('year')
            ->select('year')
            // Kunin ang SUM ng 'total' para sa bawat 'type'
            ->selectRaw('SUM(CASE WHEN type = "BKK" THEN total ELSE 0 END) as total_bkk')
            ->selectRaw('SUM(CASE WHEN type = "AKM" THEN total ELSE 0 END) as total_akm')
            ->selectRaw('SUM(CASE WHEN type = "AKO" THEN total ELSE 0 END) as total_ako')
            ->orderBy('year', 'desc')
            ->get();

        // **KRITIKAL:** Siguraduhin na ang $rawData ay ginagamit para sa Grand Totals,
        // o palitan ito ng dynamic calculation.
        // Hahayaan ko munang hardcoded ang $rawData/grandTotals mo,
        // pero ang $aggregatedMembersData ang gagamitin natin sa Table 2.

        $rawData = [
            2025 => ['bkk' => 23559, 'akm' => 169662, 'ako' => 2160],
            2022 => ['bkk' => 733304, 'akm' => 642941, 'ako' => 2935],
        ];

        // Initialize ang Grand Totals
        $grandTotals = [
            'bkk' => 0,
            'akm' => 0,
            'ako' => 0,
        ];

        // Kalkulahin ang Grand Total para sa bawat kategorya
        foreach ($rawData as $year => $totals) {
            $grandTotals['bkk'] += $totals['bkk'];
            $grandTotals['akm'] += $totals['akm'];
            $grandTotals['ako'] += $totals['ako'];
        }

        // 3. ISANG RETURN VIEW LANG: Ibigay ang dalawang variable sa view
        return view('admin.dashboard', [
            'projectStats' => $projectStats,
            // Palitan ang 'stats' ng mas malinaw na pangalan tulad ng 'membersStats'
            'membersStats' => $aggregatedMembersData, // ITO ang data para sa Table 2
            'membersDatabase' => $rawData,
            'grandTotals' => $grandTotals
        ]);
    }

    // Display Edit Form
   public function edit(ProjectStat $projectStat)
    {
        // 1. KUNIN AT I-FORMAT ANG MEMBERS DATA
        // Ito ang kailangan para i-populate ang Table 2 form sa edit.blade.php
        $membersData = DB::table('total_members')
                            ->orderBy('year', 'desc')
                            ->get();

        $stats = $membersData->groupBy('year')->map(function ($yearGroup) {
            $yearlyTotals = [
                'year' => $yearGroup->first()->year,
                'total_bkk' => 0,
                'total_akm' => 0,
                'total_ako' => 0,
            ];

            foreach ($yearGroup as $member) {
                if ($member->type === 'BKK') {
                    $yearlyTotals['total_bkk'] = $member->total;
                } elseif ($member->type === 'AKM') {
                    $yearlyTotals['total_akm'] = $member->total;
                } elseif ($member->type === 'AKO') {
                    $yearlyTotals['total_ako'] = $member->total;
                }
            }
            return (object) $yearlyTotals;
        })->values();

        // 2. IPASA ANG DALAWA (ProjectStat at Members Data)
        return view('admin.edit', [
            'projectStat' => $projectStat,
            'stats' => $stats // <== ITO ANG KRITIKAL NA IDINAGDAG
        ]);
    }

    // Handle Update
   public function update(Request $request)
{
    // Ating i-u-update ang 2025 at 2022 records (BKK, AKM, AKO)

    // 1. I-define ang mga taon at types na dapat i-update
    $updates = [
        // 2025 Updates
        ['year' => 2025, 'type' => 'BKK', 'input_name' => 'total_bkk_2025'],
        ['year' => 2025, 'type' => 'AKM', 'input_name' => 'total_akm_2025'],
        ['year' => 2025, 'type' => 'AKO', 'input_name' => 'total_ako_2025'],

        // 2022 Updates
        ['year' => 2022, 'type' => 'BKK', 'input_name' => 'total_bkk_2022'],
        ['year' => 2022, 'type' => 'AKM', 'input_name' => 'total_akm_2022'],
        ['year' => 2022, 'type' => 'AKO', 'input_name' => 'total_ako_2022'],
    ];

    DB::beginTransaction(); // Simulan ang transaction

    try {
        foreach ($updates as $item) {
            // Kunin ang value mula sa form request gamit ang input_name
            $newValue = $request->input($item['input_name']);

            // Tiyakin na ang value ay hindi null bago mag-update
            if ($newValue !== null) {
                // Hahanapin ang row gamit ang year AT type, tapos update ang 'total'
                DB::table('total_members')
                    ->where('year', $item['year']) // Hal: where year = 2025
                    ->where('type', $item['type']) // Hal: where type = 'BKK'
                    ->update(['total' => $newValue]);
            }
        }

        DB::commit(); // I-save ang lahat ng updates

        // Ibalik sa Dashboard at magpakita ng success message
        return redirect()->route('admin.dashboard')->with('success', 'Ang Members Database ay matagumpay na na-update!');

    } catch (\Exception $e) {
        DB::rollBack(); // Bawiin ang updates kung may error
        // Para sa debugging:
        // dd($e->getMessage());
        return back()->with('error', 'May naganap na error habang nag-u-update ng data. Subukang muli.')->withInput();
    }
}
    public function showMembersDatabase()
{
    // Kukunin ang lahat ng rows mula sa historical_stats table
    // (O ang table kung saan nakalagay ang summarized data per year)
    $stats = DB::table('total_members')
               ->select('id', 'year', 'total_bkk', 'total_akm', 'total_ako')
               ->orderBy('year', 'desc') // Para mauna ang 2025
               ->get();

    // I-PASS ang $stats collection sa view
    return view('admin.dashboard', [
        'stats' => $stats,
    ]);

}
public function updateProjectStats(Request $request)
{
    // 1. Validation (Dapat mayroon ito para safe)
    $validated = $request->validate([
        'row_key' => 'required|string',
        'bkk_value' => 'required|numeric',
        'akm_value' => 'required|numeric',
        'ako_value' => 'required|numeric',
        'bkk_id' => 'required|exists:project_stats,id', // Tiyakin na project_stats ang table name
        'akm_id' => 'required|exists:project_stats,id',
        'ako_id' => 'required|exists:project_stats,id',
    ]);

    $fieldToUpdate = $validated['row_key']; // Ito ang 'infosheets_received', 'images_captured', etc.

    // 2. I-update ang bawat record (BKK, AKM, AKO)
    // ProjectStat model ang gagamitin dito, I-IMPORT ITO SA TAAS: use App\Models\ProjectStat;

    ProjectStat::where('id', $validated['bkk_id'])->update([
        $fieldToUpdate => $validated['bkk_value']
    ]);

    ProjectStat::where('id', $validated['akm_id'])->update([
        $fieldToUpdate => $validated['akm_value']
    ]);

    ProjectStat::where('id', $validated['ako_id'])->update([
        $fieldToUpdate => $validated['ako_value']
    ]);

    // 3. Redirection (CRITICAL PARA MAG-REFRESH)
    return redirect()->route('admin.dashboard')->with('success', 'Project Stats updated successfully!');
}


    // --- Update Logic para sa Table 2: Members Database (Yearly data) ---
  public function updateMembersDatabase(Request $request, $year)
    {
        // 1. Validation
        $validated = $request->validate([
            'total_bkk' => 'required|integer|min:0',
            'total_akm' => 'required|integer|min:0',
            'total_ako' => 'required|integer|min:0',
        ]);

        // 2. I-update ang bawat record (BKK, AKM, AKO) gamit ang YEAR.

        // Update BKK
        $bkkRecord = TotalMembers::where('year', $year)->where('type', 'BKK')->first();
        if ($bkkRecord) {
            $bkkRecord->update(['total' => $validated['total_bkk']]);
        }

        // Update AKM
        $akmRecord = TotalMembers::where('year', $year)->where('type', 'AKM')->first();
        if ($akmRecord) {
            $akmRecord->update(['total' => $validated['total_akm']]);
        }

        // Update AKO
        $akoRecord = TotalMembers::where('year', $year)->where('type', 'AKO')->first();
        if ($akoRecord) {
            $akoRecord->update(['total' => $validated['total_ako']]);
        }

        // 3. Redirection (CRITICAL PARA MAG-REFRESH at kumuha ng bagong data)
        // Ito ang magiging sanhi upang muling patakbuhin ang index() at makuha ang bagong aggregated data
        return redirect()->route('admin.dashboard')->with('success', 'Members Database updated successfully!');
    }
}

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <strong>STAST AS OF {{ now()->setTimezone('Asia/Manila')->format('F d, Y h:i A') }}</strong></div>

                <div class="card-body">
                    <p class="alert alert-info">Ang data na ito ay para lamang sa view. Tanging ang Admin ang may karapatang mag-edit.</p>

            <table class="table table-bordered table-striped">
    <thead class="table-white">
        <tr>
            <th>Stats Category</th>
            <th>BKK</th>
            <th>AKM</th>
            <th>AKO</th>
    </thead>

    <tbody>
        <tr>
            <td><strong>Infosheets Received</strong></td>
            @foreach ($projectStats as $stat)
                <td>{{ number_format($stat->infosheets_received) }}</td>
            @endforeach
        </tr>

        <tr>
            <td><strong>Images Captured</strong></td>
            @foreach ($projectStats as $stat)
                <td>{{ number_format($stat->images_captured) }}</td>
            @endforeach
        </tr>

        <tr>
            <td><strong>Encoded</strong></td>
            @foreach ($projectStats as $stat)
                <td>{{ number_format($stat->encoded) }}</td>
            @endforeach
        </tr>

        <tr>
            <td><strong>For Review</strong></td>
            @foreach ($projectStats as $stat)
                <td>{{ number_format($stat->for_review) }}</td>
            @endforeach
        </tr>

        @empty ($stats)
            <tr>
                <td colspan="6" class="text-center">No project statistics found.</td>
            </tr>
        @endempty
    </tbody>
</table>



            <table class="table table-bordered table-hover">
    <thead class="table-white">
        <tr>
            {{-- Header/Title Column --}}
            <th>Members Database</th>

            {{-- Fixed Columns base sa iyong request --}}
            <th>Total BKK</th>
            <th>Total AKM</th>
            <th>Total AKO</th>
        </tr>
    </thead>

    <tbody>
        {{-- I-loop ang bawat row na galing sa na-process na data (bawat taon) --}}
        @forelse ($stats as $stat)
            <tr>
                {{-- Ang Unang Cell ay ang Taon (Year) --}}
                <td><strong>{{ $stat->year }}</strong></td>

                {{-- Ang mga sumunod na cells ay ang Totals --}}
                <td>{{ number_format($stat->total_bkk) }}</td>
                <td>{{ number_format($stat->total_akm) }}</td>
                <td>{{ number_format($stat->total_ako) }}</td>
            </tr>
        @empty
            {{-- Lalabas ito kung walang data ang $stats --}}
            <tr>
                <td colspan="4" class="text-center">Walang data na nakita.</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <td><strong>TOTAL</strong></td>
            <td><strong>{{ number_format($grandTotals['bkk']) }}</strong></td>
            <td><strong>{{ number_format($grandTotals['akm']) }}</strong></td>
            <td><strong>{{ number_format($grandTotals['ako']) }}</strong></td>
        </tr>
    </tfoot>
</table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

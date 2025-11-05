@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">Edit Stats for {{ $projectStat->city }}</div>

                <div class="card-body">
                    {{-- ITO ANG IYONG ORIGINAL FORM (PARA SA TABLE 1 / PROJECT STATS) --}}
                    <form method="POST" action="{{ route('admin.stats.edit', $projectStat) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="infosheets_received" class="form-label">Infosheets Received</label>
                            <input type="number" class="form-control" id="infosheets_received" name="infosheets_received" value="{{ old('infosheets_received', $projectStat->infosheets_received) }}" required>
                            @error('infosheets_received')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="images_captured" class="form-label">Images Captured</label>
                            <input type="number" class="form-control" id="images_captured" name="images_captured" value="{{ old('images_captured', $projectStat->images_captured) }}" required>
                            @error('images_captured')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="encoded" class="form-label">Encoded</label>
                            <input type="number" class="form-control" id="encoded" name="encoded" value="{{ old('encoded', $projectStat->encoded) }}" required>
                            @error('encoded')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <div class="mb-3">
                            <label for="for_review" class="form-label">For Review</label>
                            <input type="number" class="form-control" id="for_review" name="for_review" value="{{ old('for_review', $projectStat->for_review) }}" required>
                            @error('for_review')<span class="text-danger">{{ $message }}</span>@enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Update Stats</button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- ITO ANG BAGONG DUGTONG NA CARD PARA SA MEMBERS DATABASE (TABLE 2) --}}
    {{-- Siguraduhin na ipinapasa ng inyong edit() Controller method ang $stats variable dito! --}}
    <div class="row justify-content-center mt-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    **I-edit ang Members Database Totals (BKK, AKM, AKO)**
                </div>
                <div class="card-body">
                    <p class="alert alert-info">Paki-edit ang totals para sa bawat taon. Ang Grand Total ay mag-a-update sa Dashboard.</p>

                    {{-- HIWALAY NA FORM PARA SA MEMBERS DATABASE --}}
                    <form method="POST" action="{{ route('admin.members.update') }}">
                        @csrf
                        @method('PUT')

                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Taon</th>
                                    <th>Total BKK</th>
                                    <th>Total AKM</th>
                                    <th>Total AKO</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- I-loop ang $stats na galing sa Controller --}}
                                @forelse ($stats as $stat)
                                <tr>
                                    <td>**{{ $stat->year }}**</td>

                                    {{-- BKK Input --}}
                                    <td>
                                        <input type="number"
                                               name="total_bkk_{{ $stat->year }}"
                                               value="{{ old('total_bkk_' . $stat->year, $stat->total_bkk) }}"
                                               required class="form-control">
                                    </td>

                                    {{-- AKM Input --}}
                                    <td>
                                        <input type="number"
                                               name="total_akm_{{ $stat->year }}"
                                               value="{{ old('total_akm_' . $stat->year, $stat->total_akm) }}"
                                               required class="form-control">
                                    </td>

                                    {{-- AKO Input --}}
                                    <td>
                                        <input type="number"
                                               name="total_ako_{{ $stat->year }}"
                                               value="{{ old('total_ako_' . $stat->year, $stat->total_ako) }}"
                                               required class="form-control">
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center">Walang data na nakita para i-edit.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>

                        <button type="submit" class="btn btn-primary mt-3">💾 I-save ang Members Database Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

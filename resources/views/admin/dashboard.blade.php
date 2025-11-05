@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-primary text-white">Admin Dashboard - Project Stats</div>

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mt-2 mb-3">
                        <h5 class="mb-0">Project Stats by Category</h5>
                        @php
                            $is_admin_dashboard = Route::currentRouteName() == 'admin.dashboard';

                        @endphp

                        <a href="{{ $is_admin_dashboard ? route('client.dashboard') : route('admin.dashboard') }}"
                        class="btn {{ $is_admin_dashboard ? 'btn-info' : 'btn-primary' }} btn-sm"
                        style="width: 150px;">
                            @if ($is_admin_dashboard)
                                Go to Client View
                            @else
                                Go to Admin View
                            @endif
                        </a>
                        {{-- @endif --}}
                    </div>
                    <th button></th>
                    <table class="table table-bordered table-striped">
                        <thead class="table-white">
                            <tr>
                                <th>Stats Category</th>
                                <th>BKK</th>
                                <th>AKM</th>
                                <th>AKO</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @php

                                $rowKeyMap = [
                                    'Infosheets Received' => 'infosheets_received',
                                    'Images Captured' => 'images_captured',
                                    'Encoded' => 'encoded',
                                    'For Review' => 'for_review',
                                ];
                            @endphp

                            @foreach ($rowKeyMap as $label => $key)
                                <tr>
                                    <td><strong>{{ $label }}</strong></td>

                                    @foreach ($projectStats as $stat)
                                        <td>{{ number_format($stat->$key) }}</td>
                                    @endforeach

                                    <td>
                                        <button type="button"
                                                class="btn btn-sm btn-info edit-stats-btn"
                                                data-toggle="modal"
                                                data-target="#editProjectStatsModal"
                                                data-row-key="{{ $key }}"
                                                data-label="{{ $label }}"
                                                data-bkk-value="{{ optional($projectStats->firstWhere('category', 'BKK'))->$key }}"
                                                data-akm-value="{{ optional($projectStats->firstWhere('category', 'AKM'))->$key }}"
                                                data-ako-value="{{ optional($projectStats->firstWhere('category', 'AKO'))->$key }}"
                                                data-bkk-id="{{ optional($projectStats->firstWhere('category', 'BKK'))->id }}"
                                                data-akm-id="{{ optional($projectStats->firstWhere('category', 'AKM'))->id }}"
                                                data-ako-id="{{ optional($projectStats->firstWhere('category', 'AKO'))->id }}">
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            @endforeach

                            @empty ($projectStats)
                                <tr>
                                    <td colspan="5" class="text-center">No project statistics found.</td>
                                </tr>
                            @endempty
                        </tbody>
                    </table>


                    <h5 class="mt-2 mb-3">Members Database</h5>
                    <table class="table table-bordered table-hover">
                    <thead class="table-white">
                            <tr>
                                <th>Members Database</th>
                                <th>Total BKK</th>
                                <th>Total AKM</th>
                                <th>Total AKO</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            {{-- Pinalitan ang $stats ng $membersStats --}}
                            @forelse ($membersStats as $stat)
                                <tr>
                                    <td><strong>{{ $stat->year }}</strong></td>
                                    <td>{{ number_format($stat->total_bkk) }}</td>
                                    <td>{{ number_format($stat->total_akm) }}</td>
                                    <td>{{ number_format($stat->total_ako) }}</td>

                                    <td>
                                        <button type="button"
                                                class="btn btn-sm btn-info edit-members-btn"
                                                data-toggle="modal"
                                                data-target="#editMembersDatabaseModal"
                                                data-year="{{ $stat->year }}"
                                                data-bkk="{{ $stat->total_bkk }}"
                                                data-akm="{{ $stat->total_akm }}"
                                                data-ako="{{ $stat->total_ako }}">
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">Walang data na nakita.</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL 1: EDIT PROJECT STATS --}}
<div class="modal fade" id="editProjectStatsModal" tabindex="-1" role="dialog" aria-labelledby="editProjectStatsModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="editProjectStatsModalLabel">Edit Stats: <span id="stats-label"></span></h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
                    <form id="project-stats-form" method="POST" action="{{ route('admin.updateProjectStats') }}">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="row_key" id="modal-row-key">

                    <div class="modal-body">
                    <p>Updating **<span id="field-label"></span>** count for each category.</p>

                    <div class="form-group">
                        <label for="bkk_value">BKK Count:</label>
                        <input type="number" name="bkk_value" id="bkk_value" class="form-control" required>
                        <input type="hidden" name="bkk_id" id="bkk_id">
                    </div>

                    <div class="form-group">
                        <label for="akm_value">AKM Count:</label>
                        <input type="number" name="akm_value" id="akm_value" class="form-control" required>
                        <input type="hidden" name="akm_id" id="akm_id">
                    </div>

                    <div class="form-group">
                        <label for="ako_value">AKO Count:</label>
                        <input type="number" name="ako_value" id="ako_value" class="form-control" required>
                        <input type="hidden" name="ako_id" id="ako_id">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- MODAL 2: EDIT MEMBERS DATABASE --}}
<div class="modal fade" id="editMembersDatabaseModal" tabindex="-1" role="dialog" aria-labelledby="editMembersDatabaseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="editMembersDatabaseModalLabel">Edit Members Data (<span id="members-year"></span>)</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form id="members-database-form" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="id" id="members-id">

                <div class="modal-body">
                    <div class="form-group">
                        <label for="members-bkk">Total BKK:</label>
                        <input type="number" name="total_bkk" id="members-bkk" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="members-akm">Total AKM:</label>
                        <input type="number" name="total_akm" id="members-akm" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="members-ako">Total AKO:</label>
                        <input type="number" name="total_ako" id="members-ako" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-info">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


@push('scripts')
<script>
    // Ipalagay na ang Bootstrap at jQuery ay naka-load na.
    $(document).ready(function() {

        // --- Logic para sa Table 1: Project Stats (Infosheets, Images, etc.) ---
        $('.edit-stats-btn').on('click', function() {
            // Kunin ang data mula sa data-attributes
            const rowKey = $(this).data('row-key');
            const label = $(this).data('label');

            // Data values (ang mismong numero)
            const bkkValue = $(this).data('bkk-value');
            const akmValue = $(this).data('akm-value');
            const akoValue = $(this).data('ako-value');

            // ID values (ang primary key ng record)
            const bkkId = $(this).data('bkk-id');
            const akmId = $(this).data('akm-id');
            const akoId = $(this).data('ako-id');

            // I-populate ang modal header at hidden fields
            $('#stats-label').text(label);
            $('#modal-row-key').val(rowKey);
            $('#field-label').text(label); // I-update ang label sa loob ng body

            // I-populate ang form fields
            $('#bkk_value').val(bkkValue);
            $('#akm_value').val(akmValue);
            $('#ako_value').val(akoValue);

            // I-populate ang hidden ID fields (KRITIKAL PARA SA UPDATE)
            $('#bkk_id').val(bkkId);
            $('#akm_id').val(akmId);
            $('#ako_id').val(akoId);
        });


        // --- Logic para sa Table 2: Members Database (Yearly data) ---
        $('.edit-members-btn').on('click', function() {
            // TANDAAN: Sa Table 2, gagamitin natin ang YEAR bilang identifier sa ruta,
            // at I-assume na ang $stat->year ang pinapasa.
            const year = $(this).data('year');
            const bkk = $(this).data('bkk');
            const akm = $(this).data('akm');
            const ako = $(this).data('ako');

            // I-populate ang modal
            $('#members-year').text(year);
            $('#members-id').val(year); // Gagamitin ang YEAR bilang identifier sa hidden field
            $('#members-bkk').val(bkk);
            $('#members-akm').val(akm);
            $('#members-ako').val(ako);

            // Ayusin ang form action URL para sa specific record ID (dito ay Year)
            // Route: Route::put('/members/{id}') na ngayon ay magiging /admin/members/{year}
            const formAction = `/admin/members/${year}`;
            $('#members-database-form').attr('action', formAction);
        });
    });
</script>
@endpush
@endsection

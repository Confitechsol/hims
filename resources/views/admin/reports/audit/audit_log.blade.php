@extends('layouts.app')

@section('content')
<div class="page-wrapper">
    <div class="content">
        <div class="page-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="page-title">Audit Trail</h3>
                    <p class="text-muted mb-0">Superadmin log of IPD / OPD / discharge / billing changes</p>
                </div>
                <div class="col-auto">
                    <a class="btn btn-success"
                       href="{{ route('reports.audit-log.excel', request()->query()) }}">
                        <i class="fas fa-file-excel"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-body">
                <form method="GET" action="{{ route('reports.audit-log') }}" class="row g-2">
                    <div class="col-md-2">
                        <label class="form-label">From</label>
                        <input type="date" name="date_from" class="form-control" value="{{ $filters['date_from'] }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">To</label>
                        <input type="date" name="date_to" class="form-control" value="{{ $filters['date_to'] }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Module</label>
                        <select name="module" class="form-select">
                            <option value="">All</option>
                            @foreach($modules as $m)
                                <option value="{{ $m }}" @selected(($filters['module'] ?? '') === $m)>{{ strtoupper($m) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Action</label>
                        <select name="action" class="form-select">
                            <option value="">All</option>
                            @foreach($actions as $a)
                                <option value="{{ $a }}" @selected(($filters['action'] ?? '') === $a)>{{ $a }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">User</label>
                        <select name="user_id" class="form-select">
                            <option value="">All</option>
                            @foreach($users as $u)
                                <option value="{{ $u->id }}" @selected((string)($filters['user_id'] ?? '') === (string)$u->id)>
                                    {{ $u->username ?? $u->id }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">IPD / Case No</label>
                        <input type="text" name="case_no" class="form-control" value="{{ $filters['case_no'] }}" placeholder="IPDN...">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="has_reason" value="1" id="has_reason" @checked(!empty($filters['has_reason']))>
                            <label class="form-check-label" for="has_reason">Has reason</label>
                        </div>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button class="btn btn-primary w-100" type="submit">Filter</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-body table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>When</th>
                            <th>User</th>
                            <th>Module</th>
                            <th>Action</th>
                            <th>Case</th>
                            <th>Entity</th>
                            <th>Reason</th>
                            <th>Diff</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                            <tr>
                                <td>{{ optional($event->occurred_at)->format('d/m/Y H:i:s') }}</td>
                                <td>{{ $event->user->username ?? $event->user_id ?? '-' }}</td>
                                <td>{{ $event->module }}</td>
                                <td><code>{{ $event->action }}</code></td>
                                <td>{{ $event->case_no ?? '-' }}</td>
                                <td>{{ $event->entity_type }} #{{ $event->entity_id }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($event->reason, 80) }}</td>
                                <td>
                                    @if(!empty($event->old_values) || !empty($event->new_values))
                                        <details>
                                            <summary>View</summary>
                                            <pre class="mb-0 small" style="max-width:320px;white-space:pre-wrap;">OLD: {{ json_encode($event->old_values, JSON_PRETTY_PRINT) }}
NEW: {{ json_encode($event->new_values, JSON_PRETTY_PRINT) }}</pre>
                                        </details>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">No audit events for selected filters.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                {{ $events->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

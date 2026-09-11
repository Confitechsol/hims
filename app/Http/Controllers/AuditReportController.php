<?php

namespace App\Http\Controllers;

use App\Models\AuditEvent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AuditReportController extends Controller
{
    public function index(Request $request)
    {
        if (function_exists('isSuperAdmin') && ! isSuperAdmin()) {
            abort(403, 'Audit trail is available to admin / superadmin only.');
        }

        $filters = $this->validatedFilters($request);
        $query = $this->baseQuery($filters);
        $events = (clone $query)->orderByDesc('occurred_at')->orderByDesc('id')->paginate(50)->withQueryString();

        $users = User::query()->orderBy('username')->get(['id', 'username']);

        return view('admin.reports.audit.audit_log', [
            'events' => $events,
            'filters' => $filters,
            'users' => $users,
            'modules' => ['ipd', 'opd', 'billing', 'discharge', 'pathology', 'radiology', 'system'],
            'actions' => [
                'created', 'updated', 'deleted', 'discharge_reopened', 'discharge_re_finalized',
                'final_bill_generated', 'bed_assigned', 'bed_history_updated', 'visibility_changed', 'discount_updated',
            ],
        ]);
    }

    public function exportExcel(Request $request)
    {
        if (function_exists('isSuperAdmin') && ! isSuperAdmin()) {
            abort(403);
        }

        $filters = $this->validatedFilters($request);
        $rows = $this->baseQuery($filters)->orderByDesc('occurred_at')->orderByDesc('id')->limit(10000)->get();

        $sheet = new Spreadsheet();
        $ws = $sheet->getActiveSheet();
        $ws->setTitle('Audit Trail');
        $headers = ['When', 'User', 'Module', 'Action', 'Case No', 'Entity', 'Reason', 'IP'];
        $cols = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        foreach ($headers as $i => $h) {
            $ws->setCellValue($cols[$i] . '1', $h);
        }
        $ws->getStyle('A1:H1')->getFont()->setBold(true);
        $ws->getStyle('A1:H1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $r = 2;
        foreach ($rows as $event) {
            $ws->setCellValue('A' . $r, optional($event->occurred_at)?->format('d/m/Y H:i:s'));
            $ws->setCellValue('B' . $r, $event->user->username ?? $event->user_id ?? '-');
            $ws->setCellValue('C' . $r, $event->module);
            $ws->setCellValue('D' . $r, $event->action);
            $ws->setCellValue('E' . $r, $event->case_no ?? '-');
            $ws->setCellValue('F' . $r, trim(($event->entity_type ?? '') . ' #' . ($event->entity_id ?? '')));
            $ws->setCellValue('G' . $r, $event->reason ?? '');
            $ws->setCellValue('H' . $r, $event->ip_address ?? '');
            $r++;
        }

        $writer = new Xlsx($sheet);
        $filename = 'audit_trail_' . now()->format('Ymd_His') . '.xlsx';
        $temp = tempnam(sys_get_temp_dir(), 'audit');
        $writer->save($temp);

        return response()->download($temp, $filename)->deleteFileAfterSend(true);
    }

    protected function validatedFilters(Request $request): array
    {
        $dateFrom = $request->input('date_from', Carbon::now()->subDays(7)->format('Y-m-d'));
        $dateTo = $request->input('date_to', Carbon::now()->format('Y-m-d'));

        return [
            'date_from' => $dateFrom,
            'date_to' => $dateTo,
            'module' => $request->input('module'),
            'action' => $request->input('action'),
            'user_id' => $request->input('user_id'),
            'case_no' => trim((string) $request->input('case_no', '')),
            'has_reason' => $request->boolean('has_reason'),
        ];
    }

    protected function baseQuery(array $filters)
    {
        return AuditEvent::with(['user'])
            ->whereBetween('occurred_at', [
                $filters['date_from'] . ' 00:00:00',
                $filters['date_to'] . ' 23:59:59',
            ])
            ->when(! empty($filters['module']), fn ($q) => $q->where('module', $filters['module']))
            ->when(! empty($filters['action']), fn ($q) => $q->where('action', $filters['action']))
            ->when(! empty($filters['user_id']), fn ($q) => $q->where('user_id', $filters['user_id']))
            ->when($filters['case_no'] !== '', fn ($q) => $q->where('case_no', 'like', '%' . $filters['case_no'] . '%'))
            ->when(! empty($filters['has_reason']), fn ($q) => $q->whereNotNull('reason')->where('reason', '!=', ''));
    }
}

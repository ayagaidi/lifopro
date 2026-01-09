<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityLog;
use App\Models\ApiLog;
use Yajra\DataTables\DataTables;

class LogsController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function activityLogs(Request $request)
    {
        if ($request->ajax()) {
            $query = ActivityLog::query()->whereNotNull('target_user');

            // Apply filters
            if ($request->has('user_name') && !empty($request->user_name)) {
                $query->where('user_name', 'like', '%' . $request->user_name . '%');
            }
            if ($request->has('performed_by') && !empty($request->performed_by)) {
                $query->where('performed_by', 'like', '%' . $request->performed_by . '%');
            }
            if ($request->has('target_user') && !empty($request->target_user)) {
                $query->where('target_user', 'like', '%' . $request->target_user . '%');
            }
            if ($request->has('activity_type') && !empty($request->activity_type)) {
                $query->where('activity_type', 'like', '%' . $request->activity_type . '%');
            }
            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }
            if ($request->has('start_date') && !empty($request->start_date)) {
                $query->whereDate('activity_date', '>=', $request->start_date);
            }
            if ($request->has('end_date') && !empty($request->end_date)) {
                $query->whereDate('activity_date', '<=', $request->end_date);
            }
            if ($request->has('company_name') && !empty($request->company_name)) {
                $query->where('company_name', 'like', '%' . $request->company_name . '%');
            }
            if ($request->has('office_name') && !empty($request->office_name)) {
                $query->where('office_name', 'like', '%' . $request->office_name . '%');
            }

            return DataTables::of($query->orderBy('activity_date', 'desc')->limit(1))
                ->addIndexColumn()
                ->editColumn('activity_date', function ($log) {
                    return $log->activity_date->format('Y-m-d H:i:s');
                })
                ->editColumn('detailed_description', function ($log) {
                    return $log->detailed_description ?? $log->activity_type;
                })
                ->editColumn('status', function ($log) {
                    if ($log->status == 'success') {
                        return '<span class="badge" style="background-color:#28a745; color:white;">نجح</span>';
                    } else {
                        return '<span class="badge" style="background-color:#dc3545; color:white;">فشل</span>';
                    }
                })
                ->rawColumns(['status'])
                ->make(true);
        }

        return view('dashbord.logs.activity_logs');
    }

    public function apiLogs(Request $request)
    {
        if ($request->ajax()) {
            $query = ApiLog::query();

            // Apply filters
            if ($request->has('user_name') && !empty($request->user_name)) {
                $query->where('user_name', 'like', '%' . $request->user_name . '%');
            }
            if ($request->has('company_name') && !empty($request->company_name)) {
                $query->where('company_name', 'like', '%' . $request->company_name . '%');
            }
            if ($request->has('office_name') && !empty($request->office_name)) {
                $query->where('office_name', 'like', '%' . $request->office_name . '%');
            }
            if ($request->has('operation_type') && !empty($request->operation_type)) {
                $query->where('operation_type', 'like', '%' . $request->operation_type . '%');
            }
            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }
            if ($request->has('start_date') && !empty($request->start_date)) {
                $query->whereDate('execution_date', '>=', $request->start_date);
            }
            if ($request->has('end_date') && !empty($request->end_date)) {
                $query->whereDate('execution_date', '<=', $request->end_date);
            }

            return DataTables::of($query->select(['user_name', 'company_name', 'office_name', 'office_user_name', 'operation_type', 'execution_date', 'status', 'sent_data', 'received_data', 'related_link'])->limit(10000)->orderBy('execution_date', 'desc'))
                ->addIndexColumn()
                ->editColumn('execution_date', function ($log) {
                    return $log->execution_date->format('Y-m-d H:i:s');
                })
                ->editColumn('status', function ($log) {
                    return '<span class="badge badge-' . ($log->status == 'success' ? 'success' : 'danger') . '">' . ($log->status == 'success' ? 'نجح' : 'فشل') . '</span>';
                })
                ->editColumn('sent_data', function ($log) {
                    return '<pre style="max-width: 300px; overflow: auto;">' . json_encode($log->sent_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . '</pre>';
                })
                ->editColumn('received_data', function ($log) {
                    return '<pre style="max-width: 300px; overflow: auto;">' . (is_array($log->received_data) ? json_encode($log->received_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) : $log->received_data) . '</pre>';
                })
                ->editColumn('related_link', function ($log) {
                    return $log->related_link ? '<a href="' . $log->related_link . '" target="_blank" >'.$log->related_link .'</a>' : '-';
                })
                ->rawColumns(['status', 'sent_data', 'received_data', 'related_link'])
                ->make(true);
        }

        return view('dashbord.logs.api_logs');
    }
}

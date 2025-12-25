<?php

/*
 * مثال على كيفية استخدام النظام المحسن لسجلات النشاط وواجهة برمجة التطبيقات
 * Example of how to use the enhanced logging system for Activity and API logs
 */

// مثال على إنشاء سجل نشاط محسن
// Example of creating enhanced activity log
use App\Models\ActivityLog;
use App\Models\ApiLog;

// 1. سجل نشاط تغيير كلمة المرور مع تفاصيل أكثر
// 1. Activity log for password change with more details
ActivityLog::create([
    'activity_type' => 'تغيير كلمة المرور',
    'detailed_description' => 'تم تغيير كلمة المرور للمستخدم بواسطة الأدمن',
    'user_name' => 'aya',
    'performed_by' => 'أدمين التكافل', // من قام بالعملية
    'target_user' => 'aya', // المستخدم المستهدف
    'activity_date' => now(),
    'status' => 'success',
    'reason' => 'طلب تغيير كلمة المرور من المستخدم'
]);

// 2. سجل API محسن مع معلومات الشركة والمكتب
// 2. Enhanced API log with company and office information
ApiLog::create([
    'user_name' => 'api_user_123',
    'company_name' => 'شركة التأمين الليبية',
    'office_name' => 'مكتب طرابلس الرئيسي',
    'operation_type' => 'إصدار بطاقة تأمين',
    'execution_date' => now(),
    'status' => 'success',
    'sent_data' => [
        'request_id' => 'REQ-2025-001',
        'policy_number' => 'POL-123456',
        'user_data' => [
            'name' => 'أحمد محمد',
            'national_id' => '123456789'
        ]
    ],
    'received_data' => [
        'card_number' => 'CARD-987654',
        'status' => 'issued',
        'issue_date' => now()->toISOString()
    ],
    'related_link' => 'http://127.0.0.1:8000/requests/REQ-2025-001' // رابط متعلق بالعملية
]);

// 3. مثال على البحث باستخدام المرشحات الجديدة
// 3. Example of searching using new filters
function searchActivityLogs($performedBy = null, $targetUser = null) {
    $query = ActivityLog::query();
    
    if ($performedBy) {
        $query->where('performed_by', 'like', '%' . $performedBy . '%');
    }
    
    if ($targetUser) {
        $query->where('target_user', 'like', '%' . $targetUser . '%');
    }
    
    return $query->orderBy('activity_date', 'desc')->get();
}

function searchApiLogs($companyName = null, $officeName = null) {
    $query = ApiLog::query();
    
    if ($companyName) {
        $query->where('company_name', 'like', '%' . $companyName . '%');
    }
    
    if ($officeName) {
        $query->where('office_name', 'like', '%' . $officeName . '%');
    }
    
    return $query->orderBy('execution_date', 'desc')->get();
}

// أمثلة على الاستعلامات
// Query examples

// البحث عن جميع العمليات التي قام بها "أدمين التكافل"
// Search for all operations performed by "أدمين التكافل"
$adminActivities = searchActivityLogs('أدمين التكافل');

// البحث عن جميع العمليات المتعلقة بالمستخدم "aya"
// Search for all operations related to user "aya"
$userActivities = searchActivityLogs(null, 'aya');

// البحث عن جميع عمليات API لشركة معينة
// Search for all API operations for a specific company
$companyApiLogs = searchApiLogs('شركة التأمين الليبية');

// البحث عن جميع عمليات API في مكتب معين
// Search for all API operations in a specific office
$officeApiLogs = searchApiLogs(null, 'مكتب طرابلس الرئيسي');

echo "تم إنشاء أمثلة الاستخدام بنجاح!\n";
echo "Usage examples created successfully!\n";
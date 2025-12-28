<?php

namespace App\Http\Controllers\Dashbord;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\User;
use App\Models\UserType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use jeremykenedy\LaravelLogger\App\Http\Traits\ActivityLogger;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use jeremykenedy\LaravelLogger\App\Http\Traits\IpAddressDetails;
use jeremykenedy\LaravelLogger\App\Http\Traits\UserAgentDetails;
use Spatie\Permission\Models\Role;
use App\Models\ActivityLog;

class UserController extends Controller
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use IpAddressDetails;
    use UserAgentDetails;
    use ValidatesRequests;
    private $_rolesEnabled;
    private $_rolesMiddlware;
    public function __construct()
    {
        $this->middleware('auth');
    }



    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {


        ActivityLogger::activity(trans('users.loggerofshowall'));


        return view('dashbord.users.index');
    }

    public function create()
    {


        ActivityLogger::activity(trans('users.loggerofCreatuserspage'));

        $Cities = City::all();
        $roles = Role::get();
        return view('dashbord.users.create')
        ->with('roles', $roles)
        ->with('Cities', $Cities);
    }

    public function store(Request $request)
    {
        $messages = [
            'fullname.required' =>"ادخل الاسم بالكامل",
            'username.required' => trans('users.username_R'),
            'password.required' => trans('users.password_R'),
            'email.required' => trans('users.email_R'),
            'phonenumber.required' => trans('users.phonenumber_R'),
            'password_confirmation.required' => "ادخل تاكيد كلمة المرور",
            'roles_id.required' =>"يجب ان تختار الصلاحية",

        ];
        $this->validate($request, [
            'fullname' => ['required', 'string'],
            'username' => ['required', 'string', 'max:50', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:8'],
            'phonenumber' => 'required|digits_between:9,9|numeric|starts_with:91,92,94,21|unique:users',
            'password_confirmation' => 'required_with:password|same:password|min:8',
            'roles_id' => ['required'],

        ], $messages);
        try {
            DB::transaction(function () use ($request) {

                $user = new User();
                $user->fullname = $request->fullname;
                $user->username = $request->username;

                $user->phonenumber = $request->phonenumber;

                $user->email = $request->email;
                $user->password = Hash::make($request->password);
                $user->active = 1;
                $user->assignRole([decrypt($request->roles_id)]);

                $user->save();
            });

            Alert::success(trans('users.successusersadd'));
            ActivityLogger::activity($request->email . trans('users.logeeraddusersfaul'));

            return redirect()->route('users');
        } catch (\Exception $e) {

            Alert::warning($e->getMessage());
            ActivityLogger::activity($request->email . trans('users.logeeraddusersfaul'));

            return redirect()->route('users');
        }
    }
    public function users()
    {


        $user = User::select('*')->where('id', '!=', auth()->id())->where('id', '!=', 1)->orderBy('created_at', 'DESC');
        return datatables()->of($user)
        ->addColumn('role', function ($user) {
            $role = DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('model_id', $user->id)->first();
            if( $role){

                $rolename=$role->name;

            }else{
                $rolename="لايوجد صلاحيات" ;   
            }

            return  $rolename ;
        })
            ->addColumn('changeStatus', function ($user) {
                $user_id = encrypt($user->id);

                return '<a href="' . route('users/changeStatus', $user_id) . '"><i  class="fa  fa-refresh"> </i></a>';
            })
            ->addColumn('edit', function ($user) {
                $user_id = encrypt($user->id);

                return '<a style="color: #f97424;" href="' . route('users/edit', $user_id) . '"><i  class="fa  fa-edit"> </i></a>';
            })
            ->addColumn('changepassord', function ($user) {
                $user_id = encrypt($user->id);

                return '<a href="' . route('users/changepassord', $user_id) . '"><i  class="fa  fa-lock"> </i></a>';
            })
            
            ->rawColumns(['changeStatus','changepassord', 'edit','role'])

            ->make(true);
    }


    public function edit($id)
    {

        $user_id = decrypt($id);
        $user = User::find($user_id);
        ActivityLogger::activity($user->email . trans('users.loggerofedituserspage'));
        $roles = Role::all();
        $ro = DB::table('model_has_roles')
        ->where('model_id', $user_id)->first();
        if($ro)
        {
            $userrole= $ro->role_id;

        }else{
            $userrole= 0;

        }

        return view('dashbord.users.edit')
        ->with('userrole', $userrole)
        ->with('roles', $roles)
            ->with('user', $user);
    }
    public function update(Request $request, $id)
    {

        $user_id = decrypt($id);

        $messages = [
            'fullname.required' =>"ادخل الاسم بالكامل",
            'username.required' => trans('users.username_R'),
            'email.required' => trans('users.email_R'),
            'phonenumber.required' => trans('users.phonenumber_R'),
            'roles_id.required' => "يجب ان تختار الصلاحية",

        ];
        $this->validate($request, [
            'fullname' => ['required', 'string'],
            'username' => ['required', 'string', 'max:50'],
            'phonenumber' => 'required|digits_between:9,9|numeric|starts_with:91,92,94,21 ',
            'email' => 'required|email|max:50|string|unique:users,email,' . $user_id,
            'roles_id' => ['required'],

        ], $messages);
        try {
            DB::transaction(function () use ($request, $id) {
                $user_id = decrypt($id);
                $user = User::find($user_id);
                $user->fullname = $request->fullname;
                $user->username = $request->username;
                $user->phonenumber = $request->phonenumber;
                $user->email = $request->email;

                $user->save();
                $user->assignRole([decrypt($request->roles_id)]);

                ActivityLogger::activity($user->email . trans('users.logeeredituserseccess'));
            });
            Alert::success(trans('users.successuseredit'));

            return redirect()->route('users');
        } catch (\Exception $e) {

            Alert::warning($e->getMessage());
            ActivityLogger::activity($request->email . trans('users.logeeredituserfaulss'));

            return redirect()->route('users');
        }
    }
    public function show($id)
    {
        $user_id = decrypt($id);
        $user = User::find($user_id);
        ActivityLogger::activity(trans('users.profilelogger'));

        return view('dashbord.users.profile')->with('user', $user);
    }


    public function showChangePasswordForm()
    {
        ActivityLogger::activity(trans('users.changepasslogger'));

        return view('dashbord.users.change_form');
    }

    public function changePassword(Request $request)
    {
        $messages = [

            'current-password.required' => trans('users.current-password_r'),
            'new-password.required' => trans('users.new-password_r'),
            'new-password-confirm.required' => trans('users.new-password-confirm'),
        ];

        $this->validate($request, [
            'current-password' => ['required', 'string', 'min:6'],
            'new-password' => ['required', 'string', 'min:6'],
            'new-password-confirm' => ['required', 'same:new-password', 'string', 'min:6'],
        ], $messages);
        if (!(Hash::check($request->input('current-password'), Auth::user()->password))) {
            ActivityLogger::activity(trans('users.changefailloogger'));
            Alert::warning(trans('users.passwordnotmatcheing'));
            return redirect()->back();
        }
        //Change Password
        $user = Auth::user();
        $user->password = Hash::make($request->input('new-password'));
        $user->save();

        // Log password change
        ActivityLog::create([
            'activity_type' => 'تغيير كلمة المرور',
            'detailed_description' => 'تم تغيير كلمة المرور للمستخدم',
            'user_name' => $user->name ?? $user->username,
            'performed_by' => Auth::user()->username ?? Auth::user()->username,
            'target_user' => $user->name ?? $user->username,
            'company_name' => null, // Regular users don't belong to a company
            'office_name' => null, // Regular users don't belong to an office
            'activity_date' => now(),
            'status' => 'success',
            'reason' => 'تحديث أمني',
        ]);

        ActivityLogger::activity(trans('users.changesecclogger'));
        Alert::success(trans('users.changesecc'));
        return redirect()->route('users');
    }

    public function showChangePasswordForms($id)
    {
        ActivityLogger::activity(trans('users.changepasslogger'));

        return view('dashbord.users.change_formss');
    }

    public function changePasswords(Request $request,$id)
    { $user_id = decrypt($id);
        $user = User::find($user_id);


        $messages = [

            'new-password.required' => trans('users.new-password_r'),
            'new-password-confirm.required' => trans('users.new-password-confirm'),
        ];

        $this->validate($request, [
            'new-password' => ['required', 'string', 'min:6'],
            'new-password-confirm' => ['required', 'same:new-password', 'string', 'min:6'],
        ], $messages);
       
        //Change Password
        $user = $user;
        $user->password = Hash::make($request->input('new-password'));
        $user->save();

        // Log password change
        ActivityLog::create([
            'activity_type' => 'تغيير كلمة المرور',
            'detailed_description' => 'تم تغيير كلمة المرور للمستخدم',
            'user_name' => $user->name ?? $user->username,
            'performed_by' => Auth::user()->username ?? Auth::user()->username,
            'target_user' => $user->name ?? $user->username,
            'company_name' => null, // Regular users don't belong to a company
            'office_name' => null, // Regular users don't belong to an office
            'activity_date' => now(),
            'status' => 'success',
            'reason' => 'تحديث أمني',
        ]);

        ActivityLogger::activity(trans('users.changesecclogger'));
        Alert::success(trans('users.changesecc'));
        return redirect()->back();
    }
    public function changeStatus(Request $request, $id)

    {
        $user_id = decrypt($id);
        $user = User::find($user_id);

        try {
            DB::transaction(function () use ($request, $id) {
                $user_id = decrypt($id);
                $user = User::find($user_id);
                if ($user->active == 1) {
                    $active = 0;
                } else {
                    $active = 1;
                }

                $user->active = $active;
                $user->save();
            });
            ActivityLogger::activity($user->email . trans('users.changestatueslogger'));
            Alert::success(trans('users.changestatuesalert'));

            return redirect('users');
        } catch (\Exception $e) {

            Alert::warning($e->getMessage());
            ActivityLogger::activity($user->email . trans('users.changestatuesloggerfail'));

            return redirect('users');
        }
    }


    public function myactivity(Request $request)
    {

        ActivityLogger::activity("عرض سجلاتي");
        if (config('LaravelLogger.loggerPaginationEnabled')) {
            $activities = config('LaravelLogger.defaultActivityModel')::where('userId', auth::user()->id)->orderBy('created_at', 'desc');
            if (config('LaravelLogger.enableSearch')) {
                $activities = $this->searchActivityLog($activities, $request);
            }
            $activities = $activities->paginate(config('LaravelLogger.loggerPaginationPerPage'))->withQueryString();
            $totalActivities = $activities->total();
        } else {
            $activities = config('LaravelLogger.defaultActivityModel')::orderBy('created_at', 'desc');

            if (config('LaravelLogger.enableSearch')) {
                $activities = $this->searchActivityLog($activities, $request);
            }
            $activities = $activities->get();
            $totalActivities = $activities->count();
        }

        self::mapAdditionalDetails($activities);

        if (config('LaravelLogger.enableLiveSearch')) {
            // We are querying only the paginated userIds because in a big application querying all user data is performance heavy
            $user_ids = array_unique($activities->pluck('userId')->toArray());
            $users = config('LaravelLogger.defaultUserModel')::whereIn(config('LaravelLogger.defaultUserIDField'), $user_ids)->get();
        } else {
            $users = config('LaravelLogger.defaultUserModel')::all();
        }

        $data = [
            'activities'        => $activities,
            'totalActivities'   => $totalActivities,
            'users'             => $users,
        ];
        return view('dashbord.users.myactivity', $data);
    }

    public function searchActivityLog($query, $request)
    {
        if (in_array('description', explode(',', config('LaravelLogger.searchFields'))) && $request->get('description')) {
            $query->where('description', 'like', '%' . $request->get('description') . '%');
        }

        if (in_array('user', explode(',', config('LaravelLogger.searchFields'))) && (int) $request->get('user')) {
            $query->where('userId', '=', (int) $request->get('user'));
        }

        if (in_array('method', explode(',', config('LaravelLogger.searchFields'))) && $request->get('method')) {
            $query->where('methodType', '=', $request->get('method'));
        }

        if (in_array('route', explode(',', config('LaravelLogger.searchFields'))) && $request->get('route')) {
            $query->where('route', 'like', '%' . $request->get('route') . '%');
        }

        if (in_array('ip', explode(',', config('LaravelLogger.searchFields'))) && $request->get('ip_address')) {
            $query->where('ipAddress', 'like', '%' . $request->get('ip_address') . '%');
        }

        return $query;
    }
    private function mapAdditionalDetails($collectionItems)
    {
        $collectionItems->map(function ($collectionItem) {
            $eventTime = Carbon::parse($collectionItem->updated_at);
            $collectionItem['timePassed'] = $eventTime->diffForHumans();
            $collectionItem['userAgentDetails'] = UserAgentDetails::details($collectionItem->userAgent);
            $collectionItem['langDetails'] = UserAgentDetails::localeLang($collectionItem->locale);
            $collectionItem['userDetails'] = config('LaravelLogger.defaultUserModel')::find($collectionItem->userId);

            return $collectionItem;
        });

        return $collectionItems;
    }
}

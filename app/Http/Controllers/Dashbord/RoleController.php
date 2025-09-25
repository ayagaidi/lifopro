<?php

namespace App\Http\Controllers\Dashbord;


use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use DB;
use RealRashid\SweetAlert\Facades\Alert;
class RoleController extends Controller

{

    /**

     * Display a listing of the resource.

     *

     * @return IlluminateHttpResponse

     */

    function __construct()
    {

        //  $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index','store']]);

        //  $this->middleware('permission:role-create', ['only' => ['create','store']]);

        //  $this->middleware('permission:role-edit', ['only' => ['edit','update']]);

        //  $this->middleware('permission:role-delete', ['only' => ['destroy']]);

    }

    /**

     * Display a listing of the resource.

     *

     * @return IlluminateHttpResponse

     */

    public function index(Request $request)

    {

        $roles = Role::orderBy('id','DESC')->get();

        return view('dashbord.roles.index',compact('roles'))

       ;

    }

    /**

     * Show the form for creating a new resource.

     *

     * @return IlluminateHttpResponse

     */

    public function create()

    {

        $permission = Permission::get();

        return view('dashbord.roles.create',compact('permission'));

    }

    /**

     * Store a newly created resource in storage.

     *

     * @param  IlluminateHttpRequest  $request

     * @return IlluminateHttpResponse

     */

    public function store(Request $request)

    {

        $this->validate($request, [

            'name' => 'required|unique:roles,name',

            'permission' => 'required',

        ]);

    
        $role = Role::create(['name' => $request->input('name')]);

        $role->syncPermissions($request->input('permission'));
        Alert::success('تمت عملية حفظ الصلاحية   بنجاح');

        return redirect()->route('roles/index')

                        ->with('success','Role created successfully');

    }

    /**

     * Display the specified resource.

     *

     * @param  int  $id

     * @return IlluminateHttpResponse

     */

    public function show($id)

    {

        $role = Role::find($id);

        $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")

            ->where("role_has_permissions.role_id",$id)

            ->get();

        return view('dashbord.roles.show',compact('role','rolePermissions'));

    }

    /**

     * Show the form for editing the specified resource.

     *

     * @param  int  $id

     * @return IlluminateHttpResponse

     */

    public function edit($id)

    {

        $role = Role::find($id);

        $permission = Permission::get();

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id",$id)

            ->pluck('role_has_permissions.permission_id','role_has_permissions.permission_id')

            ->all();

        return view('dashbord.roles.edit',compact('role','permission','rolePermissions'));

    }

    /**

     * Update the specified resource in storage.

     *

     * @param  IlluminateHttpRequest  $request

     * @param  int  $id

     * @return IlluminateHttpResponse

     */

    public function update(Request $request, $id)

    {

        $this->validate($request, [

            'name' => 'required',

            'permission' => 'required',

        ]);

        $role = Role::find($id);

        $role->name = $request->input('name');

        $role->save();
        
        $role->syncPermissions($request->input('permission'));
        
        Alert::success('تمت عملية تعديل الصلاحية   بنجاح');

        return redirect()->route('roles/index')

                        ->with('success','Role updated successfully');

    }

    /**

     * Remove the specified resource from storage.

     *

     * @param  int  $id

     * @return IlluminateHttpResponse

     */

   

     public function destroy($id)
     {
        
        // $id = decrypt($id);

        if (
            DB::table('model_has_roles')
            ->join('roles', 'model_has_roles.role_id', '=', 'roles.id')
            ->where('id', $id)->first()
        ) {

            // Alert::warning('لا يمكن حدف الفرع لان تم استخدامه في جدول اخر');
            // ActivityLogger::activity("فشل حذف  الفرع ");
            // return redirect()->back();
            return response()->json([
                'code' => 0,
            ]);
        } else {
            DB::table("roles")->where('id',$id)->delete();
        Alert::success('تمت عملية حذف الصلاحية    بنجاح');

        // return redirect()->route('roles/index')

        //                 ->with('success','Role deleted successfully');

            return response()->json([
                'code' => 1,
                'id' => $id
            ]);
            // Alert::success('تمت عملية حذف  الفرع   بنجاح');
            // ActivityLogger::activity("حذف  الفرع ");
            // return redirect()->back();
        }
    }
    

}
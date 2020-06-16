<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Role;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function datatable()
    {
        $roles = Role::where('name' , '!=' ,'ROLE_SUPERADMIN')->orderBy('updated_at','desc')->get();
        return DataTables::of($roles)
            ->editColumn('created_at', function (Role $role){
                return $role->created_at;
            })
            ->editColumn('updated_at', function (Role $role){
                return $role->updated_at;
            })
            ->addColumn('actions', function (Role $role) {
                $edit = '<a class="btn btn-primary" href="'.route('assignModules',$role->id).'">Assign Modules</a>';
                $delete = ' <a class="btn btn-sm btn-danger" id="DeleteBtn" data-toggle="modal" data-target="#DeleteModal" data-href="'.route('RoleDelete',$role->id).'"><i style="color: white" class="fa fa-trash"></i></a>';
                return $edit . $delete;
            })
            ->rawColumns(['actions'])
            ->toJson();
    }

    public function index()
    {
        return view('superadmin.roles');
    }

    public function store(Request $request)
    {
        try {

            DB::BeginTransaction();
            Role::create([
                'name' => $request->name,
                'description' => $request->description
            ]);
            DB::commit();
            return response()->json(['status' => 200,'message' => 'Successfully Added a new role']);
        }catch (\Exception $e)
        {
            DB::rollback();
            return response()->json(['status' => 1, 'message' => $e->getMessage()]);
        }
    }

    public function Delete($id)
    {
        try{
            DB::BeginTransaction();
            $role = Role::findOrFail($id);
            $module = Module::where('role_id',$role->id);
            $module->delete();
            $role->delete();
            DB::Commit();
            Session::flash('success', "Successfully deleted");
            return redirect()->route('manage_roles');
        }catch (\Exception $e){
            DB::rollback();
            Session::flash('fail', "Failed to delete");
            return redirect()->route('manage_roles');
        }
    }

    public function assignModules($id)
    {
        $role = Role::findorFail($id);
        $get_modules = Module::with('role')->whereHas('role', function ($q) use ($role) {
            return $q->where('role_id' , $role->id);
        })->first();
        $modules = (isset($get_modules->modules) ? explode(';',$get_modules->modules) : []);

        return view('superadmin.modules',compact('role','modules'));
    }

    public function storeModules(Request $request){
        try{
            DB::beginTransaction();
            $modules = [];
            foreach ($request->except(['id']) as $value => $status) {
                if($status == 'on'){
                    array_push($modules, $value);
                }
            }
            Module::updateOrCreate(['role_id' => $request->id],['modules' => implode(';',$modules)]);
            DB::commit();
            return response()->json(['status' => 200 , 'message' => 'successfully updated the roles']);
        }catch (\Exception $e){
            DB::rollback();
            return response()->json(['status' => 1, 'error'=> $e->getMessage()]);
        }
    }
}

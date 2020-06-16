<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Role;
use App\User;
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;
class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:ROLE_SUPERADMIN');
    }

    public function datatable()
    {
        $users = User::with('roles')->whereHas('roles' , function ($q) {
            return $q->where('name','!=','ROLE_SUPERADMIN');
        });

        return DataTables::of($users)
                ->editColumn('created_at', function (User $user) {
                    return $user->created_at;
                })
                ->editColumn('updated_at', function (User $user) {
                    return $user->updated_at;
                })
                ->addColumn('actions', function (User $user) {
                    $edit = '<a class="btn btn-sm btn-primary" data-id="'.$user->id.'" data-toggle="modal" data-target="#EditModal"><i style="color: white" class="fa fa-pen"></i></a>';
                    $delete = '  <a class="btn btn-sm btn-danger" data-id="'.$user->id.'" data-toggle="modal" data-target="#DeleteModal"><i style="color: white" class="fa fa-trash"></i></a>';
                    return $edit . $delete;
                })
                ->rawColumns(['actions'])
                ->toJson();
    }

    public function index()
    {
        return view('superadmin.staff.index');
    }

    public function store(Request $request)
    {
        try{
            DB::BeginTransaction();
            $validator = \Validator::make($request->all(),[
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required'
            ],
            [
                'name.required' => 'Name is required',
                'email.required' => 'email is required',
                'email.unique' => 'Email has already taken',
                'password.required' => 'Password field is required',
            ]);
            if($validator->fails()){
                return response()->json(['status'=>1, 'errors' => $validator->errors()]);
            }
            $role = Role::find($request->role_id);
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();
            $user->roles()->attach($role);
            DB::commit();
            return response()->json(['status'=>200 , 'message' => 'User Added Successfully']);
        }catch (\Exception $e){
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        try{
            DB::BeginTransaction();
            $validator = \Validator::make($request->all(),[
                'name' => 'required',
                'email' => ['required','email',Rule::unique('users')->ignore(Auth::user()->id, 'id')],
            ],
            [
                'name.required' => 'Name is required',
                'email.required' => 'email is required',
            ]);
            if($validator->fails()){
                return response()->json(['status'=>1, 'errors' => $validator->errors()]);
            }
            $role = Role::find($request->role_id);
            $user = User::findOrFail($request->id);
            $user->name = $request->name;
            $user->email = $request->email;
            if(isset($request->password) && ($request->password != ''))
            {
               $user->password = bcrypt($request->password);
            }
            $user->save();
            $user->roles()->sync($role);
            DB::commit();
            return response()->json(['status' => 200 ,'message' => 'Successfully Updated']);
        }catch (\Exception $e)
        {
            DB::rollback();
            return response()->json(['status' => 1, 'message' => $e->getMessage()]);
        }
    }

    public function delete (Request $request){
        try{
            DB::BeginTransaction();
            $staff = User::with('roles')->findOrFail($request->get('id'));
            $modules = Module::where('role_id',$staff->roles->first()->id);
            $modules->delete();
            $staff->delete();
            DB::commit();
            return response()->json(['status' => 200, 'message' => 'Successfully Deleted']);
        }catch (\Exception $e)
        {
            DB::rollback();
            return response()->json(['status' => 1, 'message' => $e->getMessage()]);
        }

    }

    public function getRoles()
    {
        $roles = Role::where('name','!=','ROLE_SUPERADMIN')->get();
        return response()->json($roles);
    }

    public function getStaffData(Request $request)
    {
        $id = $request->get('id');
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::where('name' , '!=', 'ROLE_SUPERADMIN')->get();
        $data = [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'roles' => $roles,
            'role_id' => $user->roles->first()->id,
        ];
        return response()->json($data);
    }
}

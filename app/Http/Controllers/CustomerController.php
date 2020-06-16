<?php

namespace App\Http\Controllers;

use App\constants\PermissionConstants;
use App\Models\Customer;
use App\Models\CustomerPermissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use DB;
use Symfony\Component\Console\Input\Input;
use Yajra\DataTables\Facades\DataTables;
class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function datatable()
    {
        $customers = Customer::get();
        return DataTables::of($customers)
                ->editColumn('created_at',function (Customer $customer) {
                    return $customer->created_at;
                })
                ->editColumn('updated_at', function (Customer $customer) {
                    return $customer->updated_at;
                })
                ->addColumn('actions', function (Customer $customer) {
                    return '<a href="'.route('customer_setPermissions',$customer->id).'" class="btn btn-success" style="color: white">Set Permissions</a>
                            <a href="'.route('customer.edit', $customer->id).'" class="btn btn-sm btn-primary"><i style="color: white" class="fa fa-pen"></i></a>
                            <a data-id="'.$customer->id.'" data-toggle="modal" data-target="#DeleteModal" class="btn btn-sm btn-danger"><i style="color: white" class="fa fa-trash"></i></a>';
                })
                ->rawColumns(['actions'])
                ->toJson();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('superadmin.customer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('superadmin.customer.add');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::BeginTransaction();
        try{
            $validator = Validator::make($request->all(), [
                'username' => 'required|unique:customers',
                'email' => 'required|email|unique:customers',
                'password' => 'required',
                'company_name' => 'required',
                'address1' => 'required|max:250',
                'address2' => 'required|max:250',
                'state' => 'required',
                'zip_code' => 'required',
                'full_name' => 'required',
                'mobile_number' => 'required|digits:10',
                'city' => 'required',
                'country' => 'required',
                'fax' => 'required'
            ]);
            if($validator->fails()){
                return response()->json(['status' => 2, 'errors' => $validator->errors()]);
//                throw new ValidationException($validator->errors());
            }
            $customer =  Customer::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'company_name' => $request->company_name,
                'address1' => $request->address1,
                'address2' => $request->address2,
                'website' =>$request->website,
                'state' => $request->state,
                'zip_code' => $request->zip_code,
                'full_name' => $request->full_name,
                'mobile_number' => $request->mobile_number,
                'city' => $request->city,
                'country' => $request->country,
                'fax' => $request->fax
            ]);
            DB::commit();
            return response()->json(['status' => 200, 'message' => 'successfully updated']);
        }catch (\Exception $e)
        {
            DB::rollback();
            return response()->json(['status' => 1, 'errors' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('superadmin.customer.add', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        try{
            DB::beginTransaction();

            $validator = Validator::make($request->all() , [
                'username' => ['required',Rule::unique('customers')->ignore($customer->id, 'id')],
                'email' => ['required','email',Rule::unique('customers')->ignore($customer->id, 'id')],
                'company_name' => 'required',
                'address1' => 'required|max:250',
                'address2' => 'required|max:250',
                'state' => 'required',
                'zip_code' => 'required',
                'full_name' => 'required',
                'mobile_number' => 'required|digits:10',
                'city' => 'required',
                'country' => 'required',
                'fax' => 'required'
            ]);

            if($validator->fails()){
                return response()->json(['status' => 2 , 'errors' => $validator->getMessageBag()]);
            }



            $data = $request->all();
            if(! (isset($request->password))){
                $data = $request->except(['password']);
            }else{
                $data['password'] = bcrypt($request->password);
            }
            $customer->update($data);

            DB::commit();
            return response()->json(['status' => 200 , 'message' => 'successfully updated']);
        }catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 1 , 'error' => $e->getMessage() ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        try{
            DB::beginTransaction();
                $customer->delete();
            DB::commit();
            return response()->json(['status' => 200, 'message' => 'successfully deleted']);
        }catch (\Exception $e){
            DB::rollback();
            return response()->json(['status' => 1, 'error' => $e->getMessage()]);
        }
    }

    public function setPermissions($id)
    {
        $customer = Customer::with('permissions')->findOrFail($id);
        $permissions = [];
        if(isset($customer->permissions->permissions)){
            $permissions = explode(';',$customer->permissions->permissions);
        }
        return view('superadmin.customer.setpermission',compact('customer' , 'permissions'));
    }

    public function storePermisssions(Request $request)
    {
        try{
            DB::beginTransaction();
            $permissions = [];
            foreach ($request->except(['id']) as $index=>$value) {
                if($value == 'on'){
                    array_push($permissions, $index);
                }
            }

            CustomerPermissions::updateOrCreate(['customer_id' => $request->id],[
                'permissions' => implode(';',$permissions),
            ]);

            DB::Commit();
            return response()->json(['status' => 200 , 'message' => 'Successfully Updated']);
        }catch (\Exception $e){
            DB::rollback();
            return response()->json(['status' => 1, 'errors' => $e->getMessage()]);
        }
    }
}

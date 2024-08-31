<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Role, User};
use Illuminate\Support\Facades\{DB, Storage, Validator};
use Yajra\DataTables\DataTables;
use Exception;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('modules.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        $html = view('modules.users.create', compact('roles'))->render();
        return response()->json(['status' => true, 'html' => $html]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator  =  Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|numeric|digits:10|unique:users,phone',
            'description' => 'required|string',
            'role' => 'required|exists:roles,id',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],[
            'role.required' => 'Please select a role.',
            'role.exists' => 'The selected role is invalid.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The profile image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The profile image may not be greater than 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ]);
        }

        try {
            DB::beginTransaction();
            // Handle file upload
            // Handle the image upload separately
            $data = $validator->validated();
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('images', 'public');
            }
            $user = User::create($data);
            if ($user) {
                DB::commit();
                return response()->json(['status' => true, 'message' => 'User added successfully.']);
            }else{
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Something went wrong.']);
            }
        } catch (Exception $e) {
            // Handle other exceptions
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $id = base64_decode($id);
        $roles = Role::all();
        $row = User::findOrFail($id);
        $html = view('modules.users.edit', compact('roles','row'))->render();
        return response()->json(['status' => true, 'html' => $html]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $id = base64_decode($id);
        $validator  =  Validator::make($request->all(),[
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|numeric|digits:10|unique:users,phone,' . $id,
            'description' => 'required|string',
            'role' => 'required|exists:roles,id',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
        ],[
            'role.required' => 'Please select a role.',
            'role.exists' => 'The selected role is invalid.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'The profile image must be a file of type: jpeg, png, jpg, gif.',
            'image.max' => 'The profile image may not be greater than 2MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'errors' => $validator->errors()
            ]);
        }

        try {
            $user = User::findorFail($id);
            DB::beginTransaction();
            // Handle file upload
            // Handle the image upload separately
            $data = $validator->validated();
            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('images', 'public');
                // Optionally delete the old image
                if ($user->image) {
                    if (Storage::disk('public')->exists($user->image)) {
                        // Delete the file
                        Storage::disk('public')->delete($user->image);
                    }
                }
            }
            $user->update($data);
            if ($user) {
                DB::commit();
                return response()->json(['status' => true, 'message' => 'User updated successfully.']);
            }else{
                DB::rollBack();
                return response()->json(['status' => false, 'message' => 'Something went wrong.']);
            }
        } catch (Exception $e) {
            // Handle other exceptions
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $id = base64_decode($id);
        try {
            // Find the record by its primary key
            $row = User::find($id);

            if ($row) {
                $image = $row->image;
                // Delete the record
                $rowDelete = $row->delete();
                if($rowDelete){
                    if(!empty($image)){
                        // Check if the file exists before attempting to delete
                        if (Storage::disk('public')->exists($image)) {
                            // Delete the file
                            Storage::disk('public')->delete($image);
                        }
                    }
                    return response()->json(['status' => true, 'message' => 'User deleted successfully.']);
                }
                
            }
            return response()->json(['status' => false, 'message' => 'Record not found.']);
        } catch (Exception $e) {
            // Handle other exceptions
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getData()
    {
        $query = User::orderBy('created_at', 'desc')->get();

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('DT_RowIndex', '')
            ->editColumn('image', function ($datatables) {
                return '<img src="'.asset('public/storage/'.$datatables->image).'" alt="Product Image" width="25" class="rounded"/>';
            })
            ->editColumn('role', function ($datatables) {
                return $datatables->role_details->name;
            })
            ->addColumn('action', function ($datatables) {
                $output = '';
                $output .= '<a href="javascript:void(0)" onclick="displayEditForm(\''.base64_encode($datatables->id).'\');" class="btn btn-info btn-sm text-white" style="margin: 1px;" title="Edit">Edit</a>';
                $deleteUrl = route("users.destroy", base64_encode($datatables->id));
                $output .= '<a href="javascript:void(0)"  onclick="confirmDelete(this)" data-href="' . $deleteUrl . '" class="btn btn-danger btn-sm text-white" style="margin: 1px;" title="Delete">Delete</a>';
                return $output;
            })
            ->rawColumns(['image', 'action'])
            ->make(true);
    }
}

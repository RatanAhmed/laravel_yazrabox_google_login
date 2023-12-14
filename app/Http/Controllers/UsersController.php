<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use DataTables;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function getData(){
        $users = User::select('*');
        return Datatables::of($users)
           ->addIndexColumn()
           ->addColumn('action', function($user){
                $updateButton = "<button class='btn btn-sm btn-info createEdit' data-id='".$user->id."' data-bs-toggle='modal' data-bs-target='#createEditModal' ><i class='fa-solid fa-pen-to-square'></i></button>";
                $deleteButton = "<button class='btn btn-sm btn-danger deleteUser' data-id='".$user->id."'><i class='fa-solid fa-trash'></i></button>";
                return $updateButton." ".$deleteButton;
           }) 
           ->make();
    }

    public function index()
    {
        return view('users.index');
    }

    public function store(Request $request)
    {
        $user = new User();
        $user->fill($request->all());
        $user->password =  Hash::make($request->password);

        if ($user->save()) {
            $response['type'] = 1;
            $response['message'] = 'User created Successfully.';
        } else {
            $response['type'] = 0;
            $response['message'] = 'User creation failed';
        }
     
        return response()->json($response);
    }

    public function edit(Request $request, User $user) // getEmployeeData
    {
        if(!empty($user)){
            $response['name'] = $user->name;
            $response['email'] = $user->email;
            $response['type'] = 1;
        }else{
            $response['type'] = 0;
            $response['message'] = 'User not found';
        }

        return response()->json($response);

    
    }

    public function update(Request $request, string $id) // updateEmployee
    {
        $user = User::findOrFail($id);
       
        if (!empty($user)) {
            $user->name = $request->post('name');
            $user->email = $request->post('email');

            if ($user->update()) {
                $response['type'] = 1;
                $response['message'] = 'Update Successfully';
            } else {
                $response['type'] = 0;
                $response['message'] = 'Record not updated';
            }
        } else {
            $response['type'] = 0;
            $response['message'] = 'Invalid ID.';
        }

        return response()->json($response);
    }

    public function destroy(User $user) //deleteEmployee
    {
        if ($user->delete()) {
            $response['type'] = 1;
            $response['message'] = 'Delete Successfully';
        } else {
            $response['type'] = 0;
            $response['message'] = 'Invalid ID.';
        }

        return response()->json($response);
    }

    
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Role;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Requests\CreateUserRequest;

class UsersController extends Controller
{
    function __construct()
    {
        $this->middleware('auth', ['except'=>'show']);
        $this->middleware('roles:admin', ['except' => ['edit','update', 'show']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::with(['roles'])->get();
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //dd(config//va  a la carpeta config// y donde ira('//qeue archivo queremosservices.//que dato queremos2checkout.')); como acceder a datos
        $roles = Role::pluck('display_name', 'id');
        return view('users.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  use App\Http\Requests\UpdateUserRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        $user =(new User)->fill($request->all());

        $user->avatar = $request->file('avatar')->store('public');
        $user->save();

        $user->roles()->attach($request->roles);

        return redirect()->route('usuarios.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::findOrFail($id);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('edit',$user);
        $roles = Role::pluck('display_name', 'id');
        return view('users.edit', compact('user','roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, $id)
    {
        $user = User::findOrFail($id);
        $this->authorize($user);
        if ($request->hasFile('avatar')) {
        $user->avatar = $request->file('avatar')->store('public');
        }

        $user->update($request->only('name', 'email'));
        $user->roles()->sync($request->roles);
        return back()->with('info', 'Usuario Actualizado');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $this->authorize('destroy', $user);
        $user->delete();
        $user->roles()->sync([]);
        return back();
    }
}


<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
  public function index()
  {
    $users = User::orderBy('created_at', 'desc')->paginate(10);
    return response()->json(['status' => 'success', 'data' => $users]);
  }

  public function store(Request $request)
  {
    $filename = null;

    if ($request->hasFile('photo')) {
      $filename = Str::random(5) . $request->email . '.jpg';
      $file = $request->file('photo');
      $file->move(base_path('public/images'), $filename);
    }

    User::create([
      'name' => $request->name,
      'identity_id' => $request->identity_id,
      'gender' => $request->gender,
      'address' => $request->address,
      'photo' => $filename,
      'email' => $request->email,
      'password' => app('hash')->make($request->password),
      'phone_number' => $request->phone_number,
      'role' => $request->role,
      'status' => $request->status,
    ]);

    return response()->json(['status' => 'succes']);
  }

  public function edit($id)
  {
    //MENGAMBIL DATA BERDASARKAN ID
    $user = User::find($id);
    //KEMUDIAN KIRIM DATANYA DALAM BENTUL JSON.
    return response()->json(['status' => 'success', 'data' => $user]);
  }

  public function update(Request $request, $id)
  {
    $user = User::find($id); //GET DATA USER

    //JIKA PASSWORD YANG DIKIRIMKAN USER KOSONG, BERARTI DIA TIDAK INGIN MENGGANTI PASSWORD, MAKA KITA AKAN MENGAMBIL PASSWORD SAAT INI UNTUK DISIMPAN KEMBALI
    //JIKA TIDAK KOSONG, MAKA KITA ENCRYPT PASSWORD YANG BARU
    $password = $request->password != '' ? app('hash')->make($request->password) : $user->password;

    //LOGIC YANG SAMA ADALAH DEFAULT DARI $FILENAME ADALAH NAMA FILE DARI DATABASE
    $filaname = $user->photo;
    //JIKA ADA FILE GAMBAR YANG DIKIRIM
    if ($request->hasFile('photo')) {
      //MAKA KITA GENERATE NAMA DAN SIMPAN FILE BARU TERSEBUT
      $filaname = Str::random(5) . $user->email . '.jpg';
      $file = $request->file('photo');
      $file->move(base_path('public/images'), $filaname); //
      //HAPUS FILE LAMA
      unlink(base_path('public/images/' . $user->photo));
    }

    //KEMUDIAN PERBAHARUI DATA USERS
    $user->update([
      'name' => $request->name,
      'identity_id' => $request->identity_id,
      'gender' => $request->gender,
      'address' => $request->address,
      'photo' => $filaname,
      'password' => $password,
      'phone_number' => $request->phone_number,
      'role' => $request->role,
      'status' => $request->status
    ]);
    return response()->json(['status' => 'success']);
  }

  public function destroy($id)
  {
    $user = User::find($id);
    unlink(base_path('public/images/' . $user->photo));
    $user->delete();
    return response()->json(['status' => 'success']);
  }
}

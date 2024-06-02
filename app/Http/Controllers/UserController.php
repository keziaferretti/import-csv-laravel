<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
  public function index()
  {
    $users = User::get();
    // dd($users);

    return view('user.index', ['users' => $users]);
  }

  public function import(Request $request)
  {
    $request->validate([
      'file' => 'required|mimes:csv,txt|max:2048'
    ], [
      'file.required' => 'Por favor, selecione um arquivo CSV.',
      'file.mimes' => 'O arquivo deve ser um arquivo CSV.',
      'file.max' => 'O tamanho do arquivo execede :max MB.'
    ]);


    $headers = ['name', 'email', 'password'];
    $dataFile = array_map('str_getcsv', file($request->file('file')));

    $numeroRegistos = 0;

    $emailAlreadyRegistered = false;


    foreach ($dataFile as $keyData => $row) {

      $values = explode(';', $row[0]);

      foreach ($headers as $key => $header) {

        $arrayValues[$keyData][$header] = $values[$key];

        if ($header == 'email') {
          if (User::where('email', $arrayValues[$keyData]['email'])->first()) {
            $emailAlreadyRegistered .= $arrayValues[$keyData]['email'] . ', ';
          }
        }

        if ($header == 'password') {
          $arrayValues[$keyData][$header] = Hash::make($arrayValues[$keyData]['password'], ['rounds' => 12] );
        }
      }
      $numeroRegistos++;
    }

    if ($emailAlreadyRegistered) {
      return back()->with('error', 'Os seguintes emails já estão registados: ' . $emailAlreadyRegistered);
    }

    User::insert($arrayValues);

    return back()->with('success', 'Usuários importados com sucesso!.<br>Quantidade: ' . $numeroRegistos);
  }
}

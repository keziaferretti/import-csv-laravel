<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Importar CSV</title>
</head>

<body>

    @session('success')
    <p style="color: gray;">{!! $value !!}</p>
    @endsession
    @session('error')
    <p style="color: red;">{!! $value !!}</p>
    @endsession

    @if($errors->any())
    @foreach ($errors->all() as $error)
    <p style="color: red;">{{ $error }} </p>
    @endforeach
    @endif

    <form action="{{ route('user.import') }}" method="post" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" id="file" accept=".csv">
        <button type="submit" id="fileBtn">Importar</button>
    </form>

    <table>
        <thead>
            <tr>
                <th>Id</th>
                <th>Nome</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


</body>

</html>
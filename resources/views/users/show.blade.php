@extends('layout')

@section('contenido')
	<div class="container">
		<div class="row">
			<div class="col-12 col-sm-10 col-lg-11 mx-auto">
				<img width="150px" src="{{ Storage::url($user->avatar) }}" alt="">
	<h1>Perfil de: {{ $user->name }}</h1>

	<table class="table">
		<tr>
			<th>Nombre: </th>
			<td>{{ $user->name }}</td>
		</tr>
		<tr>
			<th>Email: </th>
			<td>{{ $user->email }}</td>
		</tr>
		<tr>
			<th>Roles: </th>
			<td>@foreach($user->roles as $role)
				{{ $role->display_name }}
			@endforeach</td>
		</tr>
		<tr>
			<th>Experiencia Laboral: </th>
			<td><a href="{{ route('expLaboral.show', $user->id) }}">Ver mas...</a></td>
		</tr>
	</table>
	@can('edit', $user)
	<a href="{{ route('usuarios.edit', $user->id) }}" class="btn btn-info">Editar</a>
	@endcan

	@can('destroy', $user)
	   <form style="display: inline;"
							method="POST"
							action="{{ route('usuarios.destroy', $user->id)}}">
						{!! csrf_field() !!}
						{{ method_field('DELETE') }}

						<button class="btn btn-danger" type="submit">Eliminar</button>
					</form>
	@endcan
			</div>
		</div>
	</div>
@endsection
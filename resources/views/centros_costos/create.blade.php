<x-app-layout>

<x-slot name="header">
Nuevo Centro de Costo
</x-slot>

<div class="card">

<form method="POST" action="{{ route('centros-costos.store') }}">
@csrf

<div class="form-grid">

<div class="form-group">
<label>Proyecto</label>

<select name="proyecto_id" class="form-control" required>

@foreach($proyectos as $proyecto)
<option value="{{ $proyecto->id }}">
{{ $proyecto->nombre }}
</option>
@endforeach

</select>

</div>


<div class="form-group">
<label>Nombre</label>

<input type="text"
name="nombre"
class="form-control"
required>
</div>


<div class="form-group">
<label>Descripción</label>

<textarea name="descripcion"
class="form-control"></textarea>
</div>

</div>


<div class="form-footer">

<a href="{{ route('centros-costos.index') }}" class="btn-cancel">
Cancelar
</a>

<button type="submit" class="btn-save">
Guardar
</button>

</div>

</form>

</div>

</x-app-layout>
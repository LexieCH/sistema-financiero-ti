<x-app-layout>

<x-slot name="header">
Editar Centro de Costo
</x-slot>

<div class="card">

<form method="POST" action="{{ route('centros-costos.update', $centro) }}">
@csrf
@method('PUT')

<div class="form-grid">

<div class="form-group">
<label>Proyecto</label>

<select name="proyecto_id" class="form-control" required>

@foreach($proyectos as $proyecto)
<option value="{{ $proyecto->id }}" {{ (int) old('proyecto_id', $centro->proyecto_id) === (int) $proyecto->id ? 'selected' : '' }}>
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
value="{{ old('nombre', $centro->nombre) }}"
required>
</div>


<div class="form-group">
<label>Descripción</label>

<textarea name="descripcion"
class="form-control">{{ old('descripcion', $centro->descripcion) }}</textarea>
</div>

<div class="form-group">
<label>Estado</label>
<select name="estado" class="form-control" required>
    <option value="1" {{ old('estado', $centro->estado) ? 'selected' : '' }}>Activo</option>
    <option value="0" {{ !old('estado', $centro->estado) ? 'selected' : '' }}>Inactivo</option>
</select>
</div>

</div>


<div class="form-footer">

<a href="{{ route('centros-costos.index') }}" class="btn-cancel">
Cancelar
</a>

<button type="submit" class="btn-save">
Guardar cambios
</button>

</div>

</form>

</div>

</x-app-layout>

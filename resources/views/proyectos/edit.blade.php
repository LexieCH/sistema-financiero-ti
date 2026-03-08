<x-app-layout>

<x-slot name="header">
Editar Proyecto
</x-slot>

<div class="card">

<form method="POST" action="{{ route('proyectos.update', $proyecto) }}">
@csrf
@method('PUT')

<div class="form-grid">

<div class="form-group">
<label>Nombre</label>
<input type="text" name="nombre" class="form-control" value="{{ old('nombre', $proyecto->nombre) }}" required>
</div>

<div class="form-group">
<label>Presupuesto</label>
<input type="number" step="0.01" name="presupuesto" class="form-control" value="{{ old('presupuesto', $proyecto->presupuesto) }}">
</div>

<div class="form-group form-col-2">
<label>Descripción</label>
<textarea name="descripcion" class="form-control">{{ old('descripcion', $proyecto->descripcion) }}</textarea>
</div>

<div class="form-group">
<label>Fecha inicio</label>
<input type="date" name="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', $proyecto->fecha_inicio) }}">
</div>

<div class="form-group">
<label>Fecha finalización</label>
<input type="date" name="fecha_finalizacion" class="form-control" value="{{ old('fecha_finalizacion', $proyecto->fecha_finalizacion) }}">
</div>

<div class="form-group">
<label>Estado</label>
<select name="estado" class="form-control" required>
    <option value="1" {{ old('estado', $proyecto->estado) ? 'selected' : '' }}>Activo</option>
    <option value="0" {{ !old('estado', $proyecto->estado) ? 'selected' : '' }}>Inactivo</option>
</select>
</div>

</div>

<div class="form-footer">
<a href="{{ route('proyectos.index') }}" class="btn-cancel">Cancelar</a>
<button type="submit" class="btn-save">Guardar cambios</button>
</div>

</form>

</div>

</x-app-layout>

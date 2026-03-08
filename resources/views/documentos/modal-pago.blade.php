<div id="modalPago" class="modal-overlay">

    <div class="modal-box" style="max-width:500px;">

        <div class="modal-header">
            <div class="modal-title">Registrar pago</div>
            <button type="button" class="modal-close" onclick="cerrarModalPago()">
                <svg viewBox="0 0 24 24"><line x1="18" y1="6" x2="6" y2="18"/><line x1="6" y1="6" x2="18" y2="18"/></svg>
            </button>
        </div>

        <div class="modal-body">

        <form method="POST" action="{{ route('pagos.store') }}">
        @csrf

        <input type="hidden" name="documento_id" id="pago_documento_id">

        <div class="form-group">
            <label>Fecha pago</label>
            <input type="date" name="fecha_pago" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Monto</label>
            <input type="number" step="0.01" name="monto" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Método de pago</label>
            <select name="metodo_pago_id" class="form-control" required>
                <option value="">Seleccionar</option>
                @foreach($metodosPago as $metodo)
                    <option value="{{ $metodo->id }}">
                        {{ $metodo->nombre }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Observación</label>
            <textarea name="observacion" class="form-control"></textarea>
        </div>

        <div class="modal-footer" style="margin-top:20px">

            <button type="submit" class="btn-save">
                Guardar
            </button>

            <button type="button" onclick="cerrarModalPago()">
                Cancelar
            </button>

        </div>

        </form>

        </div>

    </div>

</div>
<div id="modalPago" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5);">

    <div style="background:white; max-width:500px; margin:100px auto; padding:20px; border-radius:8px;">

        <h3>Registrar pago</h3>

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

        <div style="margin-top:20px">

            <button type="submit" class="btn-save">
                Registrar pago
            </button>

            <button type="button" onclick="cerrarModalPago()">
                Cancelar
            </button>

        </div>

        </form>

    </div>

</div>
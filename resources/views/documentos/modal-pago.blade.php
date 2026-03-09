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
            <label>Condición de pago</label>
            <select name="condicion_pago" id="pago_condicion" class="form-control" onchange="aplicarCondicionPago()" required>
                <option value="total">Pago total (hoy)</option>
                <option value="30">Pago a 30 días</option>
                <option value="60">Pago a 60 días</option>
                <option value="90">Pago a 90 días</option>
                <option value="120">Pago a 120 días</option>
            </select>
        </div>

        <div class="form-group">
            <label>Fecha pago</label>
            <input type="date" name="fecha_pago" id="pago_fecha" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Monto</label>
            <input type="number" step="0.01" name="monto" id="pago_monto" class="form-control" required>
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

        <div id="pago_plan_container" class="form-group" style="display:none;">
            <label>Calendario de cuotas</label>
            <div style="border:1px solid #2a2e42;border-radius:10px;overflow:hidden;background:#12141e;">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="text-align:left;padding:10px 12px;font-size:11px;color:#c2c8e0;border-bottom:1px solid #2a2e42;">Cuota</th>
                            <th style="text-align:left;padding:10px 12px;font-size:11px;color:#c2c8e0;border-bottom:1px solid #2a2e42;">Vencimiento</th>
                            <th style="text-align:right;padding:10px 12px;font-size:11px;color:#c2c8e0;border-bottom:1px solid #2a2e42;">Monto</th>
                        </tr>
                    </thead>
                    <tbody id="pago_plan_body" style="color:#f0f2fa;"></tbody>
                </table>
            </div>
            <small id="pago_plan_texto" style="display:block;margin-top:8px;color:#9aa6cf;"></small>
        </div>

        <div class="modal-footer" style="margin-top:20px">

            <button type="submit" class="btn-save">
                Pagar
            </button>

            <button type="button" class="btn-cancel" onclick="cerrarModalPago()">
                Cancelar
            </button>

        </div>

        </form>

        </div>

    </div>

</div>
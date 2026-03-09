<div id="modalDocumento" class="modal-overlay">

    <div class="modal-box">

        <h3>Nuevo documento</h3>

        <form method="POST" action="{{ route('documentos.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">

                <div class="form-group">
                    <label>Tipo documento</label>

                    <select name="tipo_documento_id" class="form-control">
                        @foreach($tipos as $tipo)

                            <option value="{{ $tipo->id }}">
                                {{ $tipo->nombre }}
                            </option>

                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Cliente / Proveedor</label>

                    <select name="tercero_id" class="form-control" required>
                        <option value="">Seleccione</option>

                        @foreach($terceros as $tercero)
                            <option value="{{ $tercero->id }}">
                                {{ $tercero->rut ?? 'Sin RUT' }} - {{ $tercero->razon_social }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Fecha</label>

                    <input type="date"
                        name="fecha_emision"
                        class="form-control"
                        value="{{ now()->toDateString() }}"
                        min="{{ now()->toDateString() }}"
                        max="{{ now()->toDateString() }}"
                        readonly>
                </div>

                <div class="form-group">
                    <label>Monto neto</label>

                    <input type="number"
                        id="monto_neto"
                        name="monto_neto"
                        class="form-control">
                </div>

                <div class="form-group">
                    <label>IVA</label>

                    <input type="text"
                        id="iva_display"
                        class="form-control"
                        readonly>
                </div>

                <div class="form-group">
                    <label class="label-total">Total</label>

                    <input type="text"
                        id="total_display"
                        class="form-control"
                        readonly>
                </div>

                <div class="form-group">
                    <label>PDF adjunto</label>

                    <input type="file"
                        name="pdf"
                        class="form-control"
                        accept="application/pdf">
                </div>

            </div>

            <div style="margin-top:20px">

                <button type="submit" class="btn-save">
                    Guardar
                </button>

                <button type="button"
                        onclick="cerrarModalDocumento()"
                        class="btn-sm">
                    Cancelar
                </button>

            </div>

        </form>

    </div>

</div>
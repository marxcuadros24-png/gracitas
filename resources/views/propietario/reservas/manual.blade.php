<x-layouts.app title="Registro Manual de Reserva">
<div class="container" style="max-width: 720px; padding-top: 30px; padding-bottom: 60px;">
    <div style="margin-bottom: 20px; font-size: 0.9rem; color: var(--text-muted);">
        <a href="{{ route('propietario.reservas.index') }}">&larr; Volver al Listado de Reservas</a>
    </div>

    <div class="card" style="border-top: 4px solid var(--primary);">
        <div class="card-header">
            <div>
                <h2>Registro Manual de Reserva (RF-11)</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem;">
                    Para solicitudes recibidas por llamada telefónica o presencialmente en el local
                </p>
            </div>
            <span class="badge" style="background: #e0f2fe; color: #0369a1;">
                Ventanilla / Teléfono
            </span>
        </div>

        <form action="{{ route('propietario.reservas.manual.store') }}" method="POST">
            @csrf

            <!-- Cancha -->
            <div class="form-group">
                <label for="cancha_id" class="form-label">Cancha Solicitada</label>
                <select name="cancha_id" id="cancha_id" class="form-select" required>
                    @foreach($canchas as $c)
                        <option value="{{ $c->id }}" data-precio="{{ $c->precio_hora }}">
                            {{ $c->establecimiento->nombre }} &bull; {{ $c->nombre }} (S/. {{ number_format($c->precio_hora, 2) }}/hr)
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Fecha y Horario -->
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 14px;">
                <div class="form-group">
                    <label for="fecha" class="form-label">Fecha del Partido</label>
                    <input type="date" name="fecha" id="fecha" class="form-control" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" required>
                </div>

                <div class="form-group">
                    <label for="hora_inicio" class="form-label">Hora de Inicio</label>
                    <input type="time" name="hora_inicio" id="hora_inicio" value="19:00" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="duracion_min" class="form-label">Duración</label>
                    <select name="duracion_min" id="duracion_min" class="form-select" required>
                        <option value="60">1 Hora (60 min)</option>
                        <option value="90">1.5 Horas (90 min)</option>
                        <option value="120">2 Horas (120 min)</option>
                        <option value="180">3 Horas (180 min)</option>
                    </select>
                </div>
            </div>

            <!-- Selección de Cliente -->
            <div style="background: #f8fafc; border: 1px solid var(--border); border-radius: var(--radius-sm); padding: 16px; margin-bottom: 20px;">
                <div class="form-label" style="margin-bottom: 10px;">Tipo de Registro de Cliente:</div>
                <div style="display: flex; gap: 24px; margin-bottom: 14px;">
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="tipo_cliente" value="nuevo" checked onclick="toggleClienteTipo('nuevo')">
                        <strong>Cliente Rápido / Por Teléfono</strong>
                    </label>
                    <label style="cursor: pointer; display: flex; align-items: center; gap: 8px;">
                        <input type="radio" name="tipo_cliente" value="existente" onclick="toggleClienteTipo('existente')">
                        <strong>Seleccionar Cliente Registrado</strong>
                    </label>
                </div>

                <!-- Formulario Cliente Nuevo -->
                <div id="cliente-nuevo-wrap" style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                    <div>
                        <label class="form-label">Nombre del Cliente / Capitán</label>
                        <input type="text" name="nuevo_nombre" id="nuevo_nombre" class="form-control" placeholder="Ej. Carlos Mendoza (Los Galácticos)">
                    </div>
                    <div>
                        <label class="form-label">Teléfono / Celular de Contacto</label>
                        <input type="text" name="nuevo_telefono" id="nuevo_telefono" class="form-control" placeholder="Ej. 966123456">
                    </div>
                </div>

                <!-- Selector Cliente Existente -->
                <div id="cliente-existente-wrap" style="display: none;">
                    <label class="form-label">Buscar Cliente Registrado</label>
                    <select name="cliente_id" id="cliente_id" class="form-select">
                        <option value="">-- Seleccionar de la lista --</option>
                        @foreach($clientes as $cli)
                            <option value="{{ $cli->id }}">
                                {{ $cli->name }} ({{ $cli->email }}) - {{ $cli->telefono ?: 'Sin cel' }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Pago Presencial -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 20px;">
                <div class="form-group">
                    <label for="metodo_pago" class="form-label">Método de Pago Presencial</label>
                    <select name="metodo_pago" id="metodo_pago" class="form-select" required>
                        <option value="efectivo">Efectivo (Ventanilla)</option>
                        <option value="yape">Yape</option>
                        <option value="plin">Plin</option>
                        <option value="otro">Otro</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="estado_pago" class="form-label">Estado Inicial del Pago</label>
                    <select name="estado_pago" id="estado_pago" class="form-select" required>
                        <option value="pendiente">Pendiente (Abonará al llegar al campo)</option>
                        <option value="pagado">Pagado (Abonado por adelantado)</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label for="observaciones" class="form-label">Notas / Observaciones de la Reserva</label>
                <input type="text" name="observaciones" id="observaciones" class="form-control" placeholder="Ej. Llamó por teléfono el día de hoy, jugará con chalecos verdes...">
            </div>

            <div style="background: #fffbeb; border: 1px solid #fde68a; padding: 14px; border-radius: 6px; font-size: 0.85rem; color: #92400e; margin-bottom: 24px;">
                <x-icon name="warning" /> <strong>Integridad de datos:</strong> Al registrarse, el sistema valida que no existan otras reservas o bloqueos en ese intervalo. Una vez guardado, el horario queda inmediatamente ocupado para evitar reservas online.
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px;">
                <a href="{{ route('propietario.reservas.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary" style="padding: 12px 24px;">
                    <x-icon name="check" /> Guardar Reserva y Bloquear Horario
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function toggleClienteTipo(tipo) {
        if (tipo === 'nuevo') {
            document.getElementById('cliente-nuevo-wrap').style.display = 'grid';
            document.getElementById('cliente-existente-wrap').style.display = 'none';
        } else {
            document.getElementById('cliente-nuevo-wrap').style.display = 'none';
            document.getElementById('cliente-existente-wrap').style.display = 'block';
        }
    }
</script>
</x-layouts.app>

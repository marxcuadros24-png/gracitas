<x-layouts.app :title="'Configurar Horarios - ' . $cancha->nombre">
<div class="container" style="max-width: 800px; padding-top: 30px; padding-bottom: 60px;">
    <div style="margin-bottom: 20px; font-size: 0.9rem; color: var(--text-muted);">
        <a href="{{ route('propietario.canchas.index') }}">&larr; Volver a Canchas</a>
    </div>

    <div class="card">
        <div class="card-header">
            <div>
                <h2>Horarios de Atención: {{ $cancha->nombre }}</h2>
                <p style="color: var(--text-muted); font-size: 0.9rem;">
                    Complejo: {{ $cancha->establecimiento->nombre }} &bull; Define el rango de apertura y cierre para cada día
                </p>
            </div>
            <button type="button" class="btn btn-outline btn-sm" onclick="copiarLunesATodos()">
                <x-icon name="copy" /> Copiar Lunes a toda la semana
            </button>
        </div>

        <form action="{{ route('propietario.horarios.update', $cancha->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Día de la Semana</th>
                            <th>Estado</th>
                            <th>Hora Apertura</th>
                            <th>Hora Cierre</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($horarios as $h)
                            <tr>
                                <td>
                                    <strong style="font-size: 1rem; color: #0f172a;">{{ $h->nombre_dia }}</strong>
                                </td>
                                <td>
                                    <label style="display: flex; align-items: center; gap: 8px; cursor: pointer;">
                                        <input type="checkbox" name="horarios[{{ $h->dia_semana }}][activo]" value="1" {{ $h->activo ? 'checked' : '' }} id="activo_{{ $h->dia_semana }}">
                                        <span style="font-size: 0.85rem; font-weight: 600;">Atiende</span>
                                    </label>
                                </td>
                                <td>
                                    <input type="time" name="horarios[{{ $h->dia_semana }}][hora_apertura]" id="apertura_{{ $h->dia_semana }}" value="{{ substr($h->hora_apertura, 0, 5) }}" class="form-control" style="width: 140px;" required>
                                </td>
                                <td>
                                    <input type="time" name="horarios[{{ $h->dia_semana }}][hora_cierre]" id="cierre_{{ $h->dia_semana }}" value="{{ substr($h->hora_cierre, 0, 5) }}" class="form-control" style="width: 140px;" required>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 12px; margin-top: 24px;">
                <a href="{{ route('propietario.canchas.index') }}" class="btn btn-outline">Cancelar</a>
                <button type="submit" class="btn btn-primary">Guardar Horarios</button>
            </div>
        </form>
    </div>
</div>

<script>
    function copiarLunesATodos() {
        const ap1 = document.getElementById('apertura_1').value;
        const ci1 = document.getElementById('cierre_1').value;
        const ac1 = document.getElementById('activo_1').checked;

        for (let d = 2; d <= 7; d++) {
            const ap = document.getElementById('apertura_' + d);
            const ci = document.getElementById('cierre_' + d);
            const ac = document.getElementById('activo_' + d);
            if (ap) ap.value = ap1;
            if (ci) ci.value = ci1;
            if (ac) ac.checked = ac1;
        }
        alert('Se han copiado los horarios del día Lunes a los demás días. Haz click en "Guardar Horarios" para confirmar.');
    }
</script>
</x-layouts.app>

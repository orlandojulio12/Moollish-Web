{{-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#filtroModal">
    Configurar Filtro
</button> --}}

<!-- Modal -->
<div class="modal fade" id="filtroModal" tabindex="-1" aria-labelledby="filtroModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Seleccionar Filtro</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div class="mb-3">
                    <label>Tipo de Filtro</label>
                    <select class="form-select" id="tipoFiltro">
                        <option value="todos">Todos los Predios</option>
                        <option value="predio">Predio Único</option>
                        <option value="regional">Por Regional (Departamento)</option>
                    </select>
                </div>

                <div class="mb-3 d-none" id="selectPredio">
                    <label>Selecciona un Predio</label>
                    <select class="form-select" id="predio">
                         @foreach ($predios as $predio)
                            <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                        @endforeach 
                    </select>
                </div>

                <div class="mb-3 d-none" id="selectDepartamento">
                    <label>Selecciona un Departamento</label>
                     <select class="form-select" id="departamento">
                        @foreach ($departamentos as $dep)
                            <option value="{{ $dep }}">{{ $dep }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" onclick="guardarFiltro()" data-bs-dismiss="modal">Guardar</button>
            </div>

        </div>
    </div>
</div>


   {{--  <strong>Filtro Actual Guardado:</strong> --}}
    <pre id="filtroActual"></pre>


<script>
document.addEventListener("DOMContentLoaded", () => {
    mostrarFiltroActual();

    document.getElementById('tipoFiltro').addEventListener('change', function() {
        const tipo = this.value;
        document.getElementById('selectPredio').classList.toggle('d-none', tipo !== 'predio');
        document.getElementById('selectDepartamento').classList.toggle('d-none', tipo !== 'regional');
    });

    // Cargar el filtro guardado al abrir el modal
    document.getElementById('filtroModal').addEventListener('show.bs.modal', function() {
        const filtro = JSON.parse(localStorage.getItem('filtroPredios') || '{}');
        if (filtro.tipo) {
            document.getElementById('tipoFiltro').value = filtro.tipo;
            if (filtro.tipo === 'predio') {
                document.getElementById('predio').value = filtro.id_predio;
            } else if (filtro.tipo === 'regional') {
                document.getElementById('departamento').value = filtro.departamento;
            }
            document.getElementById('tipoFiltro').dispatchEvent(new Event('change'));
        }
    });
});

function guardarFiltro() {
    const tipo = document.getElementById('tipoFiltro').value;
    const predio = document.getElementById('predio')?.value;
    const departamento = document.getElementById('departamento')?.value;

    let filtro = { tipo };

    if (tipo === 'predio') filtro.id_predio = predio;
   if (tipo === 'regional') {
        filtro.departamento = departamento;
        filtro.id_predio = predio; // También guarda el predio cuando es regional
    }

    localStorage.setItem('filtroPredios', JSON.stringify(filtro));
    mostrarFiltroActual();
}

function mostrarFiltroActual() {
    const filtro = localStorage.getItem('filtroPredios');
    document.getElementById('filtroActual').textContent = filtro ? filtro : 'No hay filtro guardado.';
}
</script>

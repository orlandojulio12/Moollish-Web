@extends('layouts')

@section('title')
    Moollish Monta natural
@endsection

@section('styles')
<style>
.bread {
    font-size: 28px !important;
    color: black;
}

.cumb {
    margin: 0px !important;
    align-content: center;
}

.breadcrumb {
    display: flex;
}

.active-tab {
    color: #dc7a00;
}

.no-active-tab:hover {
    color: #dc7a00;
    cursor: pointer;
    text-decoration: underline;
}

/* SOLUCIÓN CRÍTICA PARA MODALES */
body.modal-open {
    overflow: hidden !important;
    padding-right: 0 !important;
}

.modal-backdrop {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    z-index: 1040 !important;
    width: 100vw !important;
    height: 100vh !important;
    background-color: rgba(0, 0, 0, 0.5) !important;
}

.modal {
    position: fixed !important;
    top: 0 !important;
    left: 0 !important;
    z-index: 1055 !important;
    width: 100% !important;
    height: 100% !important;
    overflow-x: hidden !important;
    overflow-y: auto !important;
    outline: 0 !important;
    opacity: 0;
    transition: opacity 0.15s linear !important;
    pointer-events: none;
}

.modal.show {
    display: block !important;
    opacity: 1 !important;
    pointer-events: auto !important;
}

.modal-dialog {
    position: relative;
    width: auto;
    margin: 1.75rem auto;
    pointer-events: auto;
}
</style>
@endsection

@section('content')
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}'
                });
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}'
                });
            });
        </script>
    @endif

    <div class="container" style="background: white; padding: 20px; border-radius: 8px;">
        <div class="header-grid">
            <div class="breadcrumb">
                <a href="{{ route('inicio') }}">
                    <h3 class="cumb no-active-tab">Inicio</h3>
                </a>
                <span class="material-symbols-outlined bread">chevron_forward</span>
                <a href="{{ route('registros') }}">
                    <h3 class="cumb no-active-tab">Registros</h3>
                </a>
                <span class="material-symbols-outlined bread">chevron_forward</span>
                <a href="{{ route('reproduccionAnimal') }}">
                    <h3 class="cumb no-active-tab">Reproduccion animal</h3>
                </a>
                <span class="material-symbols-outlined bread">chevron_forward</span>
                <h3 class="cumb active-tab">Monta natural</h3>
            </div>
            <hr>
        </div>

        <div class="d-flex justify-content-end mb-3">
            <button type="button" class="btn btn-success" id="openImportModalMonta">
                <span class="material-symbols-outlined me-2">upload_file</span>
                Importar Montas Naturales
            </button>
        </div>

        <form action="{{ route('monta_natural.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="id_vaca" class="form-label">Selecciona una Vaca</label>
                <select name="id_vaca" id="id_vaca" class="form-control" required>
                    <option value="" disabled selected>-- Selecciona una vaca --</option>
                    @foreach ($vacas as $vaca)
                        <option value="{{ $vaca->id_animal }}">{{ $vaca->codigo }} - {{ $vaca->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="id_toro" class="form-label">Selecciona un Toro</label>
                <select name="id_toro" id="id_toro" class="form-control" required>
                    <option value="" disabled selected>-- Selecciona un toro --</option>
                    @foreach ($toros as $toro)
                        <option value="{{ $toro->id_animal }}">{{ $toro->codigo }} - {{ $toro->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-3">
                <label for="fecha_monta" class="form-label">Fecha de Monta</label>
                <input type="date" name="fecha_monta" id="fecha_monta" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Registrar Monta</button>
        </form>

        <hr>
        <h2>Montas Registradas</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Vaca</th>
                    <th>Toro</th>
                    <th>Fecha de Monta</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($montas as $monta)
                    <tr>
                        <td>{{ $monta->id }}</td>
                        <td>{{ $monta->vaca->nombre ?? 'N/A' }}</td>
                        <td>{{ $monta->toro->nombre ?? 'N/A' }}</td>
                        <td>{{ $monta->fecha_monta }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="importMontaModal" tabindex="-1" aria-labelledby="importMontaModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importMontaModalLabel">
                        <span class="material-symbols-outlined" style="vertical-align: middle;">upload_file</span>
                        Importar Montas Naturales
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info">
                        <strong>📋 Instrucciones:</strong>
                        <ol class="mb-0 mt-2">
                            <li>Seleccione el predio</li>
                            <li>Descargue la plantilla Excel para ese predio</li>
                            <li>Complete los datos en la plantilla</li>
                            <li>Importe el archivo completado</li>
                        </ol>
                    </div>

                    <form id="importMontaForm" enctype="multipart/form-data">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="predio_id_import" class="form-label">Predio <span class="text-danger">*</span></label>
                            <select class="form-control" id="predio_id_import" name="predio_id" required>
                                <option value="">Seleccione un predio</option>
                                @foreach(Auth::user()->predios as $predio)
                                    <option value="{{ $predio->id }}">{{ $predio->nombre_predio }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <button type="button" class="btn btn-primary" id="downloadMontaTemplate" disabled>
                                <span class="material-symbols-outlined me-2">download</span>
                                Descargar Plantilla Excel
                            </button>
                            <small class="text-muted d-block mt-1">
                                Primero seleccione un predio para descargar la plantilla
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="file_monta" class="form-label">Archivo Excel <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" id="file_monta" name="file" accept=".xlsx,.xls,.csv" required>
                            <small class="text-muted">Formatos permitidos: .xlsx, .xls, .csv (máx. 5MB)</small>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-primary" id="submitMontaImport">
                        <span class="material-symbols-outlined me-2">upload</span>
                        Importar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            if ($.fn.DataTable.isDataTable('#proposalList')) {
                $('#proposalList').DataTable().clear().destroy();
            }
            $('#proposalList').DataTable({
                language: {
                    "decimal": "",
                    "lengthMenu": "Mostrar _MENU_ entradas",
                    "emptyTable": "No tienes predios asignados",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Entradas",
                    "infoFiltered": "(Filtrado de _MAX_ total entradas)",
                    "infoPostFix": "",
                    "thousands": ",",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscar:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                },
            });
        });

        (function() {
            const modalSystem = {
                activeModal: null,
                activeBackdrop: null,

                open: function(modalId) {
                    this.closeAll();
                    
                    const modal = document.getElementById(modalId);
                    if (!modal) return;

                    this.activeBackdrop = document.createElement('div');
                    this.activeBackdrop.style.cssText = 'position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 1040; display: block;';
                    this.activeBackdrop.className = 'monta-modal-backdrop';
                    document.body.appendChild(this.activeBackdrop);

                    modal.style.cssText = 'display: block !important; position: fixed !important; top: 0 !important; left: 0 !important; width: 100% !important; height: 100% !important; z-index: 1050 !important; overflow-y: auto !important;';
                    modal.classList.add('show');
                    this.activeModal = modal;
                    document.body.style.overflow = 'hidden';

                    const self = this;
                    this.activeBackdrop.addEventListener('click', function() {
                        self.close(modalId);
                    });
                },

                close: function(modalId) {
                    const modal = document.getElementById(modalId);
                    if (modal) {
                        modal.style.display = 'none';
                        modal.classList.remove('show');
                    }

                    if (this.activeBackdrop) {
                        this.activeBackdrop.remove();
                        this.activeBackdrop = null;
                    }

                    document.body.style.overflow = '';
                    this.activeModal = null;
                },

                closeAll: function() {
                    document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
                    document.querySelectorAll('.modal').forEach(el => {
                        el.classList.remove('show', 'fade');
                        el.style.display = 'none';
                        el.style.opacity = '1';
                    });
                    document.body.classList.remove('modal-open');
                    document.body.style.paddingRight = '';
                    document.body.style.overflow = '';
                    this.activeModal = null;
                }
            };

            window.montaModalSystem = modalSystem;

            document.addEventListener('click', function(e) {
                const openTrigger = e.target.closest('[data-bs-toggle="modal"]');
                if (openTrigger) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    const targetId = openTrigger.getAttribute('data-bs-target').replace('#', '');
                    window.montaModalSystem.open(targetId);
                    return false;
                }

                const closeTrigger = e.target.closest('[data-bs-dismiss="modal"]');
                if (closeTrigger) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    
                    const modal = closeTrigger.closest('.modal');
                    if (modal) {
                        window.montaModalSystem.close(modal.id);
                    }
                    return false;
                }
            }, true);

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && window.montaModalSystem.activeModal) {
                    window.montaModalSystem.close(window.montaModalSystem.activeModal.id);
                }
            });

            document.querySelectorAll('.modal').forEach(modal => {
                modal.addEventListener('show.bs.modal', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                    return false;
                }, true);
            });

            setInterval(function() {
                const backdrops = document.querySelectorAll('.modal-backdrop:not(.monta-modal-backdrop)');
                backdrops.forEach(b => b.remove());
            }, 500);
        })();

        document.addEventListener('DOMContentLoaded', function() {
            const openModalBtn = document.getElementById('openImportModalMonta');
            const predioSelect = document.getElementById('predio_id_import');
            const downloadTemplateBtn = document.getElementById('downloadMontaTemplate');
            const submitBtn = document.getElementById('submitMontaImport');
            const form = document.getElementById('importMontaForm');

            if (openModalBtn) {
                openModalBtn.addEventListener('click', function() {
                    window.montaModalSystem.open('importMontaModal');
                });
            }

            if (predioSelect) {
                predioSelect.addEventListener('change', function() {
                    downloadTemplateBtn.disabled = !this.value;
                });
            }

            if (downloadTemplateBtn) {
                downloadTemplateBtn.addEventListener('click', function() {
                    const predioId = predioSelect.value;
                    if (!predioId) {
                        Swal.fire({ icon: 'warning', title: 'Predio no seleccionado', text: 'Por favor, seleccione un predio primero' });
                        return;
                    }
                    window.location.href = `{{ route('monta_natural.template') }}?predio_id=${predioId}`;
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Descargando plantilla...', showConfirmButton: false, timer: 2000 });
                });
            }

            if (submitBtn) {
                submitBtn.addEventListener('click', async function() {
                    const predioId = predioSelect.value;
                    const fileInput = document.getElementById('file_monta');
                    const file = fileInput.files[0];

                    if (!predioId) { Swal.fire({ icon: 'warning', title: 'Predio no seleccionado', text: 'Por favor, seleccione un predio' }); return; }
                    if (!file) { Swal.fire({ icon: 'warning', title: 'Archivo no seleccionado', text: 'Por favor, seleccione un archivo Excel' }); return; }
                    if (file.size > 5 * 1024 * 1024) { Swal.fire({ icon: 'error', title: 'Archivo muy grande', text: 'El archivo no debe superar los 5MB' }); return; }

                    const allowedExtensions = ['xlsx', 'xls', 'csv'];
                    const fileExtension = file.name.split('.').pop().toLowerCase();
                    if (!allowedExtensions.includes(fileExtension)) {
                        Swal.fire({ icon: 'error', title: 'Formato inválido', text: 'Solo se permiten archivos .xlsx, .xls, .csv' });
                        return;
                    }

                    const formData = new FormData();
                    formData.append('predio_id', predioId);
                    formData.append('file', file);
                    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Importando...';
                    submitBtn.disabled = true;

                    try {
                        const response = await fetch('{{ route("monta_natural.import") }}', {
                            method: 'POST',
                            body: formData,
                            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content }
                        });

                        const result = await response.json();
                        window.montaModalSystem.close('importMontaModal');

                        if (result.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: '¡Éxito!',
                                html: `<div style="text-align: left;"><p>${result.message}</p><hr><p><strong>✅ Importados:</strong> ${result.exitosos}</p></div>`,
                                confirmButtonText: 'Aceptar'
                            }).then(() => location.reload());
                        } else if (result.status === 'partial') {
                            let mensaje = `<div style="text-align: left;"><p><strong>${result.message}</strong></p><hr><p><strong>📊 Resumen:</strong></p><ul style="margin: 10px 0;"><li><strong>✅ Importados:</strong> ${result.exitosos}</li><li><strong>❌ Con errores:</strong> ${result.errores.length}</li><li><strong>ℹ️ Duplicados:</strong> ${result.duplicados.length}</li></ul>`;
                            if (result.errores && result.errores.length > 0) {
                                mensaje += `<hr><p><strong>❌ Errores encontrados:</strong></p><ul style="max-height: 200px; overflow-y: auto; text-align: left; color: #721c24; background: #f8d7da; padding: 10px; border-radius: 4px; margin: 10px 0;">`;
                                result.errores.forEach(err => { mensaje += `<li style="margin: 5px 0;">${err}</li>`; });
                                mensaje += `</ul>`;
                            }
                            if (result.duplicados && result.duplicados.length > 0) {
                                mensaje += `<hr><p><strong>ℹ️ Registros duplicados:</strong></p><ul style="max-height: 150px; overflow-y: auto; text-align: left; color: #856404; background: #fff3cd; padding: 10px; border-radius: 4px; margin: 10px 0;">`;
                                result.duplicados.forEach(dup => { mensaje += `<li style="margin: 5px 0;">${dup}</li>`; });
                                mensaje += `</ul>`;
                            }
                            mensaje += `</div>`;
                            Swal.fire({ icon: 'warning', title: 'Importación parcial', html: mensaje, width: 750, confirmButtonText: 'Aceptar' }).then(() => location.reload());
                        } else {
                            let mensaje = `<div style="text-align: left;"><p><strong>📊 Resumen:</strong></p><ul style="margin: 10px 0;"><li><strong>✅ Importados:</strong> ${result.exitosos || 0}</li><li><strong>❌ Con errores:</strong> ${result.errores?.length || 0}</li><li><strong>ℹ️ Duplicados:</strong> ${result.duplicados?.length || 0}</li></ul>`;
                            if (result.errores && result.errores.length > 0) {
                                mensaje += `<hr><p><strong>❌ Errores encontrados:</strong></p><ul style="max-height: 200px; overflow-y: auto; text-align: left; color: #721c24; background: #f8d7da; padding: 10px; border-radius: 4px; margin: 10px 0;">`;
                                result.errores.forEach(err => { mensaje += `<li style="margin: 5px 0;">${err}</li>`; });
                                mensaje += `</ul>`;
                            }
                            if (result.duplicados && result.duplicados.length > 0) {
                                mensaje += `<hr><p><strong>ℹ️ Registros duplicados:</strong></p><ul style="max-height: 150px; overflow-y: auto; text-align: left; color: #856404; background: #fff3cd; padding: 10px; border-radius: 4px; margin: 10px 0;">`;
                                result.duplicados.forEach(dup => { mensaje += `<li style="margin: 5px 0;">${dup}</li>`; });
                                mensaje += `</ul>`;
                            }
                            mensaje += `<hr><p style="font-size: 14px; color: #666;"><em>💡 Corrija los errores en el archivo y vuelva a intentar.</em></p></div>`;
                            Swal.fire({ icon: 'error', title: result.message || 'No se pudo importar ninguna monta natural', html: mensaje, width: 750, confirmButtonText: 'Cerrar' });
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        window.montaModalSystem.close('importMontaModal');
                        Swal.fire({ icon: 'error', title: 'Error de conexión', text: 'Error al procesar la importación. Por favor, intente nuevamente.' });
                    } finally {
                        submitBtn.innerHTML = '<span class="material-symbols-outlined me-2">upload</span>Importar';
                        submitBtn.disabled = false;
                    }
                });
            }
        });
    </script>
@endsection
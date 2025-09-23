@extends('layouts')

@section('template_title')
    Crear Gestión de Información
@endsection

@section('page-header')
    <div class="page-header">
        <div class="page-header-left d-flex align-items-center">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('predios.index') }}">Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Secciones', $predioId ) }}">Caracterizacion De Predios</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('Seccion2', $predioId ) }}">Informacion Del Predio</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Gestión de información</li>
                </ol>
            </nav>
        </div>
    </div>
@endsection

@section('content')
    <style>
        #content-container:before {
            content: '';
            display: block;
            height: 165px;
            width: 100%;
            position: absolute;
            background-color: #C29F77 !important;
            z-index: 0;
        }
    </style>



    <div class="content-area-body">
        <div class="card mb-0">
            <div class="card-body">
                @if ($GestionExists)
                <form method="POST" action="{{ route('gestion_informacion.update', $gestionInformacion->id) }}" role="form">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id_predio" value="{{ $predioId}}">

                    <div class="form-group">
                        <label for="donde_regis_info_finca">¿Dónde registra la información de la finca?</label>
                        <select name="donde_regis_info_finca" id="donde_regis_info_finca" class="form-control" required>
                            <option value="">Seleccione una opción</option>
                            <option value="CUADERNO" {{ $gestionInformacion->donde_regis_info_finca == 'CUADERNO' ? 'selected' : '' }}>CUADERNO</option>
                            <option value="COMPUTADOR" {{ $gestionInformacion->donde_regis_info_finca == 'COMPUTADOR' ? 'selected' : '' }}>COMPUTADOR</option>
                            <option value="FICHAS TÉCNICAS" {{ $gestionInformacion->donde_regis_info_finca == 'FICHAS TÉCNICAS' ? 'selected' : '' }}>FICHAS TÉCNICAS</option>
                            <option value="NO LLEVO REGISTROS" {{ $gestionInformacion->donde_regis_info_finca == 'NO LLEVO REGISTROS' ? 'selected' : '' }}>NO LLEVO REGISTROS</option>
                        </select>
                    </div>

                    <div class="form-group" id="los_registros_son_group" style="display:none;">
                        <label for="los_registros_son">Los registros son:</label>
                        <select name="los_registros_son" id="los_registros_son" class="form-control" required>
                            <option value="">Seleccione una opción</option>
                            <option value="PASTOS" {{ $gestionInformacion->los_registros_son == 'PASTOS' ? 'selected' : '' }}>PASTOS</option>
                            <option value="REPRODUCCIÓN" {{ $gestionInformacion->los_registros_son == 'REPRODUCCIÓN' ? 'selected' : '' }}>REPRODUCCIÓN</option>
                            <option value="PERSONAL" {{ $gestionInformacion->los_registros_son == 'PERSONAL' ? 'selected' : '' }}>PERSONAL</option>
                            <option value="SANIDAD" {{ $gestionInformacion->los_registros_son == 'SANIDAD' ? 'selected' : '' }}>SANIDAD</option>
                            <option value="ECONÓMICOS" {{ $gestionInformacion->los_registros_son == 'ECONÓMICOS' ? 'selected' : '' }}>ECONÓMICOS</option>
                            <option value="PRODUCCIÓN" {{ $gestionInformacion->los_registros_son == 'PRODUCCIÓN' ? 'selected' : '' }}>PRODUCCIÓN</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="calcula_indicadores">¿Calcula indicadores?</label>
                        <select name="calcula_indicadores" id="calcula_indicadores" class="form-control" required>
                            <option value="">Seleccione una opción</option>
                            <option value="si" {{ $gestionInformacion->calcula_indicadores == 'si' ? 'selected' : '' }}>Sí</option>
                            <option value="no" {{ $gestionInformacion->calcula_indicadores == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="form-group" id="calcula_indicadores_de_group" style="display:none;">
                        <label for="calcula_indicadores_de">¿Qué indicadores calcula?</label>
                        <select name="calcula_indicadores_de" id="calcula_indicadores_de" class="form-control">
                            <option value="">Seleccione una opción</option>
                            <option value="PRODUCCIÓN" {{ $gestionInformacion->calcula_indicadores_de == 'PRODUCCIÓN' ? 'selected' : '' }}>PRODUCCIÓN</option>
                            <option value="FORRAJES" {{ $gestionInformacion->calcula_indicadores_de == 'FORRAJES' ? 'selected' : '' }}>FORRAJES</option>
                            <option value="ECONÓMICOS" {{ $gestionInformacion->calcula_indicadores_de == 'ECONÓMICOS' ? 'selected' : '' }}>ECONÓMICOS</option>
                            <option value="REPRODUCCIÓN" {{ $gestionInformacion->calcula_indicadores_de == 'REPRODUCCIÓN' ? 'selected' : '' }}>REPRODUCCIÓN</option>
                        </select>
                    </div>

                    <div class="form-group" id="calcula_indicadores_de_para_group" style="display:none;">
                        <label for="calcula_indicadores_de_para">¿Para qué calcula los indicadores?</label>
                        <select name="calcula_indicadores_de_para" id="calcula_indicadores_de_para" class="form-control">
                            <option value="">Seleccione una opción</option>
                            <option value="DESCARTES" {{ $gestionInformacion->calcula_indicadores_de_para == 'DESCARTES' ? 'selected' : '' }}>DESCARTES</option>
                            <option value="DECISIONES GERENCIALES" {{ $gestionInformacion->calcula_indicadores_de_para == 'DECISIONES GERENCIALES' ? 'selected' : '' }}>DECISIONES GERENCIALES</option>
                            <option value="OTRO" {{ $gestionInformacion->calcula_indicadores_de_para == 'OTRO' ? 'selected' : '' }}>OTRO</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="la_informacion_es">La información es:</label>
                        <select name="la_informacion_es" id="la_informacion_es" class="form-control" required>
                            <option value="">Seleccione una opción</option>
                            <option value="ADECUADA" {{ $gestionInformacion->la_informacion_es == 'ADECUADA' ? 'selected' : '' }}>ADECUADA</option>
                            <option value="SUFICIENTE" {{ $gestionInformacion->la_informacion_es == 'SUFICIENTE' ? 'selected' : '' }}>SUFICIENTE</option>
                            <option value="CONFIABLE" {{ $gestionInformacion->la_informacion_es == 'CONFIABLE' ? 'selected' : '' }}>CONFIABLE</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="utiliza_software_monitore">¿Utiliza software de monitoreo?</label>
                        <select name="utiliza_software_monitore" id="utiliza_software_monitore" class="form-control" required>
                            <option value="">Seleccione una opción</option>
                            <option value="si" {{ $gestionInformacion->utiliza_software_monitore == 'si' ? 'selected' : '' }}>Sí</option>
                            <option value="no" {{ $gestionInformacion->utiliza_software_monitore == 'no' ? 'selected' : '' }}>No</option>
                        </select>
                    </div>

                    <div class="form-group" id="utiliza_software_monitore_cual_group" style="display:none;">
                        <label for="utiliza_software_monitore_cual">¿Cuál software utiliza?</label>
                        <input type="text" name="utiliza_software_monitore_cual" id="utiliza_software_monitore_cual" class="form-control" value="{{ $gestionInformacion->utiliza_software_monitore_cual }}">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                    </div>
                </form>

                @else
                <form method="POST" action="{{ route('gestion_informacion.store', $predioId) }}" role="form">
                    @csrf

                    <div class="form-group">
                        <label for="donde_regis_info_finca">¿Dónde registra la información de la finca?</label>
                        <select name="donde_regis_info_finca" id="donde_regis_info_finca" class="form-control" required>
                            <option value="">Seleccione una opción</option>
                            <option value="CUADERNO">CUADERNO</option>
                            <option value="COMPUTADOR">COMPUTADOR</option>
                            <option value="FICHAS TÉCNICAS">FICHAS TÉCNICAS</option>
                            <option value="NO LLEVO REGISTROS">NO LLEVO REGISTROS</option>
                        </select>
                    </div>

                    <div class="form-group" id="los_registros_son_group" style="display:none;">
                        <label for="los_registros_son">Los registros son:</label>
                        <select name="los_registros_son" id="los_registros_son" class="form-control" required>
                            <option value="">Seleccione una opción</option>
                            <option value="PASTOS">PASTOS</option>
                            <option value="REPRODUCCIÓN">REPRODUCCIÓN</option>
                            <option value="PERSONAL">PERSONAL</option>
                            <option value="SANIDAD">SANIDAD</option>
                            <option value="ECONÓMICOS">ECONÓMICOS</option>
                            <option value="PRODUCCIÓN">PRODUCCIÓN</option>


                        </select>
                    </div>

                    <div class="form-group">
                        <label for="calcula_indicadores">¿Calcula indicadores?</label>
                        <select name="calcula_indicadores" id="calcula_indicadores" class="form-control" required>
                            <option value="">Seleccione una opción</option>
                            <option value="si">Sí</option>
                            <option value="no">No</option>
                        </select>
                    </div>

                    <div class="form-group" id="calcula_indicadores_de_group" style="display:none;">
                        <label for="calcula_indicadores_de">¿Qué indicadores calcula?</label>

                        <select name="calcula_indicadores_de" id="calcula_indicadores_de" class="form-control">
                            <option value="">Seleccione una opción</option>
                            <option value="PRODUCCIÓN">PRODUCCIÓN</option>
                            <option value="FORRAJES">FORRAJES</option>
                            <option value="ECONÓMICOS">ECONÓMICOS</option>
                            <option value="REPRODUCCIÓN">REPRODUCCIÓN</option>
                        </select>
                    </div>

                    <div class="form-group" id="calcula_indicadores_de_para_group" style="display:none;">
                        <label for="calcula_indicadores_de_para">¿Para qué calcula los indicadores?</label>
                        <select name="calcula_indicadores_de_para" id="calcula_indicadores_de_para" class="form-control">
                            <option value="">Seleccione una opción</option>
                            <option value="DESCARTES">DESCARTES</option>
                            <option value="DECISIONES GERENCIALES">DECISIONES GERENCIALES</option>
                            <option value="OTRO">OTRO</option>


                        </select>
                    </div>

                    <div class="form-group">
                        <label for="la_informacion_es">La información es:</label>
                        <select name="la_informacion_es" id="la_informacion_es" class="form-control" required>
                            <option value="">Seleccione una opción</option>
                            <option value="ADECUADA">ADECUADA</option>
                            <option value="SUFICIENTE">SUFICIENTE</option>
                            <option value="CONFIABLE">CONFIABLE</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="utiliza_software_monitore">¿Utiliza software de monitoreo?</label>
                        <select name="utiliza_software_monitore" id="utiliza_software_monitore" class="form-control" required>
                            <option value="">Seleccione una opción</option>
                            <option value="si">Sí</option>
                            <option value="no">No</option>
                        </select>
                    </div>

                    <div class="form-group" id="utiliza_software_monitore_cual_group" style="display:none;">
                        <label for="utiliza_software_monitore_cual">¿Cuál software utiliza?</label>
                        <input type="text" name="utiliza_software_monitore_cual" id="utiliza_software_monitore_cual" class="form-control">
                    </div>

                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const dondeRegisInfoFinca = document.getElementById('donde_regis_info_finca');
            const losRegistrosSonGroup = document.getElementById('los_registros_son_group');
            const losRegistrosSon = document.getElementById('los_registros_son');

            const calculaIndicadores = document.getElementById('calcula_indicadores');
            const calculaIndicadoresDeGroup = document.getElementById('calcula_indicadores_de_group');
            const calculaIndicadoresDe = document.getElementById('calcula_indicadores_de');
            const calculaIndicadoresDeParaGroup = document.getElementById('calcula_indicadores_de_para_group');

            const utilizaSoftwareMonitore = document.getElementById('utiliza_software_monitore');
            const utilizaSoftwareMonitoreCualGroup = document.getElementById('utiliza_software_monitore_cual_group');
            const utilizaSoftwareMonitoreCual = document.getElementById('utiliza_software_monitore_cual');

            function toggleLosRegistrosSon() {
                if (dondeRegisInfoFinca.value === 'NO LLEVO REGISTROS') {
                    losRegistrosSon.value = 'NO APLICA';
                    losRegistrosSonGroup.style.display = 'none';
                } else {
                    losRegistrosSon.value = '';
                    losRegistrosSonGroup.style.display = 'block';
                }
            }

            function toggleCalculaIndicadores() {
                if (calculaIndicadores.value === 'no') {
                    calculaIndicadoresDe.value = 'NO APLICA';
                    calculaIndicadoresDeGroup.style.display = 'none';
                    calculaIndicadoresDeParaGroup.style.display = 'none';
                } else {
                    calculaIndicadoresDe.value = '';
                    calculaIndicadoresDeGroup.style.display = 'block';
                    calculaIndicadoresDeParaGroup.style.display = 'block';
                }
            }

            function toggleUtilizaSoftwareMonitore() {
                if (utilizaSoftwareMonitore.value === 'no') {
                    utilizaSoftwareMonitoreCual.value = 'NO APLICA';
                    utilizaSoftwareMonitoreCualGroup.style.display = 'none';
                } else {
                    utilizaSoftwareMonitoreCual.value = '';
                    utilizaSoftwareMonitoreCualGroup.style.display = 'block';
                }
            }

            dondeRegisInfoFinca.addEventListener('change', toggleLosRegistrosSon);
            calculaIndicadores.addEventListener('change', toggleCalculaIndicadores);
            utilizaSoftwareMonitore.addEventListener('change', toggleUtilizaSoftwareMonitore);

            // Initialize fields to be hidden
            losRegistrosSonGroup.style.display = 'none';
            calculaIndicadoresDeGroup.style.display = 'none';
            calculaIndicadoresDeParaGroup.style.display = 'none';
            utilizaSoftwareMonitoreCualGroup.style.display = 'none';
        });
    </script>
@endsection

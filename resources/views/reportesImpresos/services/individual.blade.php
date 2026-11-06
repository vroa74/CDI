<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Individual de Servicio</title>
    <style>
        @page {
            margin: 5cm 0.5cm 5.5cm 0.5cm;
        }
        #header {
            position: fixed;
            top: -5cm;
            left: 0;
            width: 100%;
            text-align: center;
            z-index: 1000;
            margin: 0;
            padding: 0;
            /* background-color: #506ffc !important; */
        }

        body {
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }

        #footer {
            position: fixed;            
            bottom: -5cm;
            width: 100%;
            text-align: center;
            padding: 10px 0;
            /* border: 1px solid #000; */
            font-size: 20px;
            /* color: #fff; */
            /* background-color: rgb(3, 26, 153); */
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
        }

        .tableclean {
            width: 100%;
            margin: 0;
            border-collapse: collapse;
            border: none;
            text-align: center;
            /* background-color: #07f8a8 !important; */
        }

        .tableclean th,
        .tableclean td {
            border: none;
            text-align: center;
            vertical-align: middle;
            padding: 2px;
        }

        .tablaSection1 {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            margin-bottom: 0;
            padding: 10px;
            /* background-color: #f306ac !important; */
        }|

        .tablaSection1 td {
            vertical-align: top;
            padding: 0;
            border: none;
        }

        .tablaSection1 td.col1 {
            width: 75%;
            padding: 2px;
        }

        .tablaSection1 td.col2 {
            width: 25%;
            padding: 2px;
        }

        .tablaSection1 .box {
            border: 2px solid black;
            border-radius: 20px;
            padding: 3px;
            margin: 0;
        }

        .tablaSection2 {
            width: 99%;
            border-collapse: collapse;
            margin-top: -10px;
            padding: 0;
            /* background-color: #e5ff52 !important; */
        }

        .tablaSection2 td {
            padding: 0;
            border: none;
        }

        .tablaSection2 .box {
            width: 99%;
            height: 7rem;
            border: 2px solid black;
            border-radius: 25px;
            box-sizing: border-box;
            margin: 0;
            padding: 5px;
        }

        .checklist {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.2;
        }

        .checklist td {
            width: 50%;
            padding: 0;
            border: none;
            vertical-align: top;
        }

        .checklist .box {
            border: 2px solid black;
            border-radius: 25px;
            padding: 10px;
            margin: 2px;
            display: block;
        }

        .Textareas {
            width: 100%;
            border-collapse: collapse;
            margin: 0;
            padding: 0;
            font-size: 11px;
            line-height: 1.2;
        }

        .Textareas td {
            width: 50%;
            padding: 0;
            border: none;
            vertical-align: top;
        }

        .Textareas .box {
            border: 2px solid black;
            border-radius: 25px;
            height: 20rem;
            padding: 6px;
            margin: 2px;
            box-sizing: border-box;
            display: block;
        }

        .text-5px { font-size: 0.3125rem; }  /* 5px */
        .text-6px { font-size: 0.375rem; }   /* 6px */
        .text-7px { font-size: 0.4375rem; }  /* 7px */
        .text-8px { font-size: 0.5rem; }     /* 8px */
        .text-9px { font-size: 0.5625rem; }  /* 9px */
        .text-10px { font-size: 0.625rem; }  /* 10px */
        .text-11px { font-size: 0.6875rem; } /* 11px */
        .text-xs { font-size: 0.75rem; }     /* 12px */
        .text-sm { font-size: 0.875rem; }    /* 14px */
        .text-base { font-size: 1rem; }      /* 16px */
        .text-lg { font-size: 1.125rem; }    /* 18px */
        .text-xl { font-size: 1.25rem; }     /* 20px */
        /* Tamaños de texto medianos */
        .text-2xl { font-size: 1.5rem; }     /* 24px */
        .text-3xl { font-size: 1.875rem; }   /* 30px */
        .text-4xl { font-size: 2.25rem; }    /* 36px */
        .text-5xl { font-size: 3rem; }       /* 48px */
        .text-6xl { font-size: 3.75rem; }    /* 60px */
        .text-7xl { font-size: 4.5rem; }     /* 72px */
        /* Tamaños de texto grandes */
        .text-8xl { font-size: 6rem; }       /* 96px */
        .text-9xl { font-size: 8rem; }       /* 128px */
        .text-left {             text-align: left;        }
        .text-center {            text-align: center;        }
        .text-right {            text-align: right;        }
        .text-justify {             text-align: justify        }

        /* Estilo para texto en negrita */
        .font-bold {            font-weight: bold;        }
        /* Estilo para texto en cursiva */
        .italic {            font-style: italic;        }
        /* Estilo para texto subrayado */
        .font-underline {            text-decoration: underline;        }
        /* Estilo para texto tachado */
        .line-through {            text-decoration: line-through;        }



        .text-center {
            text-align: center;
        }

        .m-1 {
            margin: 0.25rem;
        }

        .mb--10 {
            margin-bottom: -2.5rem;
        }
        .mt-0 {    margin-top: 0;  }
        .mt-1 {    margin-top: 0.25rem;  }
        .mt-2 {    margin-top: 0.5rem;  }
        .mt-3 {    margin-top: 0.75rem;  }
        .mt-4 {    margin-top: 1rem;  }
        .mt-5 {    margin-top: 1.25rem;  }
        .mt-6 {    margin-top: 1.5rem;  }
        .mt-7 {    margin-top: 1.75rem;  }
        
        .header h1 {
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 5px;
        }
        
        .header .subtitle {
            font-size: 14px;
            color: #64748b;
            font-weight: 500;
        }
        
        .service-info {
            margin-bottom: 25px;
        }
        
        .info-section {
            margin-bottom: 20px;
        }
        
        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            background-color: #f1f5f9;
            padding: 8px 12px;
            border-left: 4px solid #2563eb;
            margin-bottom: 12px;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        
        .info-item {
            display: flex;
            flex-direction: column;
        }
        
        .info-label {
            font-size: 11px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        
        .info-value {
            font-size: 12px;
            color: #1e293b;
            font-weight: 500;
            padding: 6px 8px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            min-height: 20px;
        }
        
        .info-value.empty {
            color: #94a3b8;
            font-style: italic;
        }
        
        .full-width {
            grid-column: 1 / -1;
        }
        
        .users-section {
            margin-bottom: 20px;
        }
        
        .user-card {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 10px;
        }
        
        .user-role {
            font-size: 11px;
            font-weight: 600;
            color: #2563eb;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 4px;
        }
        
        .user-name {
            font-size: 13px;
            font-weight: 500;
            color: #1e293b;
        }
        
        .user-details {
            margin-top: 8px;
            padding-top: 8px;
            border-top: 1px solid #e2e8f0;
        }
        
        .user-detail-item {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 4px;
            font-size: 11px;
            line-height: 1.3;
        }
        
        .detail-label {
            font-weight: 600;
            color: #64748b;
            min-width: 60px;
            margin-right: 8px;
        }
        
        .detail-value {
            color: #1e293b;
            flex: 1;
            word-wrap: break-word;
        }
        
        .service-types {
            margin-bottom: 20px;
        }
        
        .type-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px;
        }
        
        .type-item {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 8px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }
        
        .type-checkbox {
            width: 12px;
            height: 12px;
            border: 2px solid #2563eb;
            border-radius: 2px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .type-checkbox.checked {
            background-color: #2563eb;
        }
        
        .type-checkbox.checked::after {
            content: '✓';
            color: white;
            font-size: 8px;
            font-weight: bold;
        }
        
        .type-label {
            font-size: 11px;
            font-weight: 500;
            color: #1e293b;
        }
        
        .content-section {
            margin-bottom: 20px;
        }
        
        .content-text {
            font-size: 12px;
            color: #1e293b;
            line-height: 1.5;
            padding: 10px;
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            min-height: 40px;
        }
        
        /* .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            text-align: center;
            font-size: 10px;
            color: #64748b;
        } */
        
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .status-active {
            background-color: #dcfce7;
            color: #166534;
        }
        
        .status-inactive {
            background-color: #fef2f2;
            color: #991b1b;
        }
        
        @media print {
            body {
                font-size: 10px;
            }
            
            .header h1 {
                font-size: 20px;
            }
            
            .section-title {
                font-size: 12px;
            }
            
            .info-value {
                font-size: 10px;
            }
        }
    </style>

</head>
<body>
    <div id="header" >
        <div style="width: 96%; display: flex; justify-content: center;">
            <table class="tableclean">
                
                <tr>
                    <td style="width: 20%; text-align: center; vertical-align: middle;"></td>
                    <td style="width: 56%; text-align: center; vertical-align: middle; padding: 10px;">

                        @php
                        // Determinar qué imagen usar basado en la fecha del servicio
                        $fechaLimite = \Carbon\Carbon::create(2025, 7, 25);
                        $fechaServicio = $service->F_serv ? \Carbon\Carbon::parse($service->F_serv) : null;
                        
                        if ($fechaServicio && $fechaServicio->greaterThanOrEqualTo($fechaLimite)) {
                            $headImagePath = public_path('ple/head65.png');
                        } else {
                            $headImagePath = public_path('ple/head.png');
                        }
                        
                        $headImageBase64 = base64_encode(file_get_contents($headImagePath));
                    @endphp
                    <img    src="data:image/png;base64,{{ $headImageBase64 }}" 
                            alt="Logo izquierdo" style="max-width: 160%; height: auto;">

                        <h1 style="margin: 5px 0;">ORDEN DE SERVICIO</h1>
                    </td>
                    <td style="width: 20%; text-align: center; vertical-align: middle;"></td>
                </tr>
            </table>
        </div>
    </div>

    <table class="tablaSection1">
        <tr>
            <td class="col1">
                <p class="text-xs font-bold m-1" style="margin: 2px 0;">FECHA DE SERVICIO:  <span class="font-underline"> {{ $service->F_serv ? $service->F_serv->format('d/m/Y') : 'N/A' }} </span> </p>
                <p class="text-xs font-bold m-1" style="margin: 2px 0;">NOMBRE DE SOLICITANTE:   <span class="font-underline">{{ $service->solicitante->name ?? 'N/A' }}</span> </p>
                <p class="text-xs font-bold m-1" style="margin: 2px 0;">OFICINA O DEPARTAMENTO:   <span class="font-underline">{{ $service->solicitante->direction ?? 'N/A' }}</span> </p>
            </td>
              <br>
    <br>
    
            <td class="col2">
                <div class="box text-center">
                    No. Reporte:<br>
                    <span class="font-bold ml-5 text-sm"> {{ $service->id_s ?? 'N/A' }}</span> <br>                    
                </div>
            </td>
        </tr>
    </table>
    
    {{-- <div style="margin-top: 5px;"></div> --}}
    
                <table class="tablaSection2">
                    <tr>
                        <td>
                            <div class="box">
                                <span class="font-bold ml-5 text-sm">OBJETIVO DE LA SOLICITUD :</span> <br>
                                <span class="text-xs"><x-service-text :value="$service->obj_sol" /></span>
                            </div>
                        </td>
                    </tr>
                </table>
    
    {{-- <div style="margin-top: 8px;"></div> --}}
    
                <table class="checklist">
                    <tr>
                        <td>
                            <div class="box">
                                <div style="font-size: 10px; font-weight: bold; margin-bottom: 5px;">TIPOS DE SERVICIO:</div>
                                <table style="width: 100%; border-collapse: collapse; font-size: 9px;">
                                    <tr>
                                        <td style="width: 50%; padding: 1px;">
                                            <div style="white-space: nowrap;">
                                                <span style="display: inline-block; width: 8px; height: 8px; border: 1px solid black; margin-right: 3px; vertical-align: middle; {{ $service->correctivo ? 'background-color: black;' : '' }}"></span>
                                                <span style="vertical-align: middle;">Mto. Correctivo</span>
                                            </div>
                                        </td>
                                        <td style="width: 50%; padding: 1px;">
                                            <div style="white-space: nowrap;">
                                                <span style="display: inline-block; width: 8px; height: 8px; border: 1px solid black; margin-right: 3px; vertical-align: middle; {{ $service->web_ins ? 'background-color: black;' : '' }}"></span>
                                                <span style="vertical-align: middle;">Página Institucional </span>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td style="width: 50%; padding: 1px;">
                                            <div style="white-space: nowrap;">
                                                <span style="display: inline-block; width: 8px; height: 8px; border: 1px solid black; margin-right: 3px; vertical-align: middle; {{ $service->preventivo ? 'background-color: black;' : '' }}"></span>
                                                <span style="vertical-align: middle;">Mto. Preventivo</span>
                                            </div>
                                        </td>

                                        <td style="width: 50%; padding: 1px;">
                                            <div style="white-space: nowrap;">
                                                <span style="display: inline-block; width: 8px; height: 8px; border: 1px solid black; margin-right: 3px; vertical-align: middle; {{ $service->print ? 'background-color: black;' : '' }}"></span>
                                                <span style="vertical-align: middle;">Impresión</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%; padding: 1px;">
                                            <div style="white-space: nowrap;">
                                                <span style="display: inline-block; width: 8px; height: 8px; border: 1px solid black; margin-right: 3px; vertical-align: middle; {{ $service->transparencia ? 'background-color: black;' : '' }}"></span>
                                                <span style="vertical-align: middle;">Transparencia</span>
                                            </div>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td style="width: 50%; padding: 1px;">
                                            <div style="white-space: nowrap;">
                                                <span style="display: inline-block; width: 8px; height: 8px; border: 1px solid black; margin-right: 3px; vertical-align: middle; {{ $service->a_tec ? 'background-color: black;' : '' }}"></span>
                                                <span style="vertical-align: middle;">Asistencia Técnica </span>
                                            </div>
                                        </td>

                                </table>
                            </div>
                        </td>
                        <td>
                            <div class="box">
                                <div style="font-size: 10px; font-weight: bold; margin-bottom: 5px;">VÍAS DE SOLICITUD:</div>
                                <table style="width: 100%; border-collapse: collapse; font-size: 9px;">
                                    <tr>
                                        <td style="width: 50%; padding: 1px;">
                                            <div style="white-space: nowrap;">
                                                <span style="display: inline-block; width: 8px; height: 8px; border: 1px solid black; margin-right: 3px; vertical-align: middle; {{ $service->email ? 'background-color: black;' : '' }}"></span>
                                                <span style="vertical-align: middle;">Correo</span>
                                            </div>
                                        </td>
                                        <td style="width: 50%; padding: 1px;">
                                            <div style="white-space: nowrap;">
                                                <span style="display: inline-block; width: 8px; height: 8px; border: 1px solid black; margin-right: 3px; vertical-align: middle; {{ $service->tel ? 'background-color: black;' : '' }}"></span>
                                                <span style="vertical-align: middle;">Teléfono</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%; padding: 1px;">
                                            <div style="white-space: nowrap;">
                                                <span style="display: inline-block; width: 8px; height: 8px; border: 1px solid black; margin-right: 3px; vertical-align: middle; {{ $service->sol_ser ? 'background-color: black;' : '' }}"></span>
                                                <span style="vertical-align: middle;">Solicitud de Servicio</span>
                                            </div>
                                        </td>
                                        <td style="width: 50%; padding: 1px;">
                                            <div style="white-space: nowrap;">
                                                <span style="display: inline-block; width: 8px; height: 8px; border: 1px solid black; margin-right: 3px; vertical-align: middle; {{ $service->oficio ? 'background-color: black;' : '' }}"></span>
                                                <span style="vertical-align: middle;">Oficio</span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%; padding: 1px;">
                                            <div style="white-space: nowrap;">
                                                <span style="display: inline-block; width: 8px; height: 8px; border: 1px solid black; margin-right: 3px; vertical-align: middle; {{ $service->calendario ? 'background-color: black;' : '' }}"></span>
                                                <span style="vertical-align: middle;">Calendario</span>
                                            </div>
                                        </td>
                                        <td style="width: 50%; padding: 1px;">
                                            <!-- Espacio vacío para mantener la estructura -->
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%; padding: 1px;">
                                            <div style="white-space: nowrap;">
                                                <br>
                                                <span style="vertical-align: middle;"></span>
                                            </div>
                                        </td>
                                        <td style="width: 50%; padding: 1px;">
                                            <!-- Espacio vacío para mantener la estructura -->
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </td>
                    </tr>
                </table>

                <table class="Textareas">
                    <tr>
                        <td>
                            <div class="box">
                                <!-- Contenido columna izquierda -->
                                <div class="text-center ">
                                    <span class="font-bold ml-5 text-sm"> Actividades Realizadas </span>
                                </div><br>
                                <span  class="text-11px text-justify">
                                    <x-service-text :value="$service->actividades" />
                                </span>
                            </div>
                        </td>
                        <td>
                            <div class="box">
                                <!-- Contenido columna derecha -->
                                <div class="text-center mb-2">
                                    <span class="font-bold ml-5 text-sm"> Observaciones </span>
                                </div> <br>
                                <span class="text-11px text-justify">
                                    <x-service-text :value="$service->observaciones" />
                                </span>
                            </div>
                        </td>
                    </tr>
                </table>

    <div id="footer" style="border: none !important;">
            <!-- Tablas de firmas - Pie de página -->
    <table style="width: 100%; border-collapse: separate; border-spacing: 0.5rem;" class="mb-4">
        <tr style="height: 1rem;">
            <td style="border: 2px solid black; height: 4rem; border-radius: 25px; padding: 0.5rem; position: relative;" class="text-center text-xs">
                <div style="position: absolute; top: 0.5rem; left: 50%; transform: translateX(-50%);">Conformidad</div>
            </td>
            <td style="border: 2px solid black; height: 4rem; border-radius: 25px; padding: 0.5rem; position: relative;" class="text-center text-xs">
                <div style="position: absolute; top: 0.5rem; left: 50%; transform: translateX(-50%);">Efectuó</div>
            </td>
            <td style="border: 2px solid black; height: 4rem; border-radius: 25px; padding: 0.5rem; position: relative;" class="text-center text-xs">
                <div style="position: absolute; top: 0.5rem; left: 50%; transform: translateX(-50%);">VoBo</div>
            </td>
        </tr>
    </table>
    
    <table style="width: 100%; table-layout: fixed; border-collapse: separate; border-spacing: 0; margin-top: -0.5rem;" class="mb-4">
        <tr style="height: 6rem;">
            <td style="padding: 0.5rem; vertical-align: bottom; text-align: center; border: none;" class="text-xs">
                <div class="text-8px">
                    {{ $service->solicitante->name ?? 'N/A' }}<br>
                    {{ $service->solicitante->position ?? 'N/A' }}<br>
                    {{ $service->solicitante->direction ?? 'N/A' }}
                </div>
            </td>        
            <td style="padding: 0.5rem; vertical-align: bottom; text-align: center; border: none;" class="text-xs">
                <div class="text-8px">
                    {{ $service->efectuo->name ?? 'N/A' }}<br>
                    {{ $service->efectuo->position ?? 'N/A' }}<br>                                        
                    {{ $service->efectuo->direction ?? 'N/A' }}                
                </div>
            </td>
    
            <td style="padding: 0.5rem; vertical-align: bottom; text-align: center; border: none;" class="text-xs">
                <div class="text-8px">
                    {{ $service->vobo->name ?? 'N/A' }}<br>
                    {{ $service->vobo->position ?? 'N/A' }}<br>
                    {{ $service->vobo->direction ?? 'N/A' }}                
                </div>
            </td>
        </tr>
    </table>
    
    <!-- Leyenda by VROA arriba de la imagen del footer -->
    <div style="text-align: right; margin-bottom: 5px;">
        <p class="text-5px" style="color: #313131;">by VROA</p>
    </div>
        
        @php
            $footImagePath = public_path('ple/foot.png');
            $footImageBase64 = file_exists($footImagePath) ? base64_encode(file_get_contents($footImagePath)) : '';
        @endphp
        
        @if($footImageBase64)
            <img    src="data:image/png;base64,{{ $footImageBase64 }}" 
            alt="Pie de página" 
            style="max-width: 80%; height: auto; margin-top: 5px;">
        @endif
        
    </div>
    
</body>
</html> 
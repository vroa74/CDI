<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Detallado de Servicio</title>
    <style>
        @page {
            margin: 4cm 0.5cm 2cm 0.5cm;
        }
        #header {
            position: fixed;
            top: -4cm;
            left: 0;
            width: 100%;
            text-align: center;
            z-index: 1000;
            margin-top: -0.3cm;
            padding: 2px 0; 
            /* background-color: #5b6af0;  */
        }

        body {
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            font-size: 14px;
            margin: 0;
            padding: 0;
            /* background-color: #01fc72; */
        }
        #footer {
            position: fixed;
            bottom: -1.5cm;
            margin-bottom: 0.3cm;
            /* height: 3cm; */
            width: 100%; 
            text-align: center;
            padding: 10px 0;
            font-size: 20px;
            /* background-color: rgb(238, 198, 21); */
            font-family: Arial, "Helvetica Neue", Helvetica, sans-serif;
            z-index: 999;
        }
        .section {
            margin: 2px 0;
            padding: 15px;
            border-radius: 5px;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .active {
            background-color: #d4edda;
            color: #155724;
        }
        .inactive {
            background-color: #f8d7da;
            color: #721c24;
        }
        .photo-grid {
            width: 100%;
            margin-top: 15px;
        }
        .photo-row {
            display: table;
            width: 100%;
            margin-bottom: 10px;
            table-layout: fixed;
        }
        .photo-card {
            display: table-cell;
            width: 48%;
            border: 2px solid #ddd;
            border-radius: 8px;
            padding: 10px;
            background-color: #f9f9f9;
            text-align: center;
            vertical-align: top;
            margin: 0 5px;
        }
        .photo-card:first-child {
            margin-right: 10px;
        }
        .photo-card:last-child {
            margin-left: 10px;
        }
        .photo-image {
            width: 80%;
            max-height: 150px;
            object-fit: contain;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            display: block;
            margin-left: auto;
            margin-right: auto;
            background-color: #f9f9f9;
        }
        .photo-details {
            background-color: white;
            padding: 12px;
            border-radius: 5px;
            border: 1px solid #e0e0e0;
            text-align: center;
        }
        .photo-description {
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            font-size: 11px;
        }
        .photo-info {
            font-size: 10px;
            color: #666;
        }
        .no-photos {
            text-align: center;
            padding: 30px;
            color: #666;
            font-style: italic;
            background-color: #f5f5f5;
            border-radius: 5px;
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
.container{
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}
.bloque{
    height: 1cm;
    width: 2cm;
    margin: 0.2cm;
    background-color: #d0ff00;
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
                        $headImagePath = public_path('ple/head.png');
                        $headImageBase64 = base64_encode(file_get_contents($headImagePath));
                    @endphp
                    <img    src="data:image/png;base64,{{ $headImageBase64 }}" 
                            alt="Logo izquierdo" style="max-width: 100%; height: auto;">
                        <p class="text-xl">        REPORTE DETALLES DEL SERVICIO              </p>
                        <p class="title">        Reporte {{ $service->id_s }}              </p>
                    </td>
                    <td style="width: 20%; text-align: center; vertical-align: middle;"></td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Detalles del Servicio -->



    <div id="footer"
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

    <div class="container">
        <div class="section">
            {{-- <div class="title">DETALLES DEL SERVICIO</div> --}}
            @if($service->mantenimiento && !empty(trim($service->mantenimiento)))
            <table>
                <tr>
                    <td><strong>Mantenimiento:</strong></td>
                    <td><x-service-text :value="$service->mantenimiento" /></td>
                </tr>
            </table>
            @endif
        </div>
    
        <!-- Fotos del Servicio -->
        <div class="section">
            <div class="title">FOTOS DEL SERVICIO</div>
            @if($service->photos && $service->photos->count() > 0)
                {{-- <p style="margin-bottom: 15px; color: #666; font-weight: bold;">Total de fotos: {{ $service->photos->count() }}</p> --}}
                <div class="photo-grid">
                    @foreach($service->photos->chunk(2) as $photoRow)
                    <div class="photo-row">
                        @foreach($photoRow as $photo)
                        <div class="photo-card">
                            @php
                                // Convertir todas las imágenes a base64 para PDFs
                                $photoPath = str_replace(['/', '\\'], DIRECTORY_SEPARATOR, storage_path('app/public/' . $photo->photo_path));
                                $photoBase64 = '';
                                $useBase64 = false;
                                $mimeType = 'image/jpeg';
                                
                                if (file_exists($photoPath)) {
                                    try {
                                        $photoBase64 = base64_encode(file_get_contents($photoPath));
                                        $imageInfo = getimagesize($photoPath);
                                        $mimeType = $imageInfo['mime'] ?? 'image/jpeg';
                                        $useBase64 = true;
                                    } catch (Exception $e) {
                                        \Log::error('Error al cargar imagen: ' . $e->getMessage());
                                        $useBase64 = false;
                                    }
                                } else {
                                    \Log::warning('Imagen no encontrada: ' . $photoPath);
                                }
                            @endphp
                            @if($useBase64 && $photoBase64)
                                <img src="data:{{ $mimeType }};base64,{{ $photoBase64 }}" 
                                     alt="Foto del servicio" 
                                     class="photo-image">
                            @else
                                <div class="photo-image" style="background-color: #f0f0f0; display: flex; align-items: center; justify-content: center; color: #666; font-size: 10px;">
                                    Imagen no disponible
                                </div>
                            @endif
                            <div class="photo-details">
                                <div class="photo-description">
                                    @if($photo->description)
                                        {{ $photo->description }}
                                    @else
                                        <span style="color: #999; font-style: italic;">Sin descripción</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                        @if($photoRow->count() == 1)
                        <div class="photo-card" style="border: none; background: none; width: 48%;"></div>
                        @endif
                    </div>
                    @endforeach
                </div>
            @else
                <div class="no-photos">
                    <p>No hay fotos registradas para este servicio.</p>
                </div>
            @endif
        </div>
    
    </div>
    
</body>
</html> 
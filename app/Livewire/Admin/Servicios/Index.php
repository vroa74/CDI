<?php

namespace App\Livewire\Admin\Servicios;

use App\Models\Service;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Index extends Component
{
    use WithFileUploads, WithPagination;

    // Forzar recarga completa del componente en cada render
    protected $listeners = ['$refresh'];

    public function mount()
    {
        // Restaurar filtros desde la sesión
        $this->filterPreventivo = session('filterPreventivo', false);
        $this->filterCalendario = session('filterCalendario', false);
    }

    public $search = '';

    public $editing = false;

    public $serviceId = null;

    // Filtros
    public $filterIdMin = '';

    public $filterIdMax = '';

    public $filterFechaMin = '';

    public $filterFechaMax = '';

    public $filterSolicitante = '';

    public $filterEfectuo = '';

    public $filterVobo = '';

    public $filterObjetivo = '';

    public $filterActividades = '';

    public $filterObservaciones = '';

    public $filterFechaServicio = '';

    public $filterEstado = '';

    // Filtros de tipo de servicio
    public $filterCorrectivo = false;

    public $filterPreventivo = false;

    public $filterTransparencia = false;

    public $filterATecnico = false;

    public $filterWebIns = false;

    public $filterPrint = false;

    // Filtros de vía de solicitud
    public $filterEmail = false;

    public $filterTelefono = false;

    public $filterSolicitudServicio = false;

    public $filterOficio = false;

    public $filterCalendario = false;

    public $perPage = 30;

    public $orderBy = 'id';

    public $orderDirection = 'desc';

    // Propiedades para reportes
    public $showReportModal = false;

    public $reportType = '';

    public $reportDateFrom = '';

    public $reportDateTo = '';

    public $reportUser = '';

    public $reportStatus = '';

    // Campos del formulario para edición
    public $id_s = '';

    public $F_serv = '';

    public $solicitante_id = '';

    public $solicitante2_id = '';

    public $efectuo_id = '';

    public $vobo_id = '';

    public $obj_sol = '';

    public $actividades = '';

    public $mantenimiento = '';

    public $observaciones = '';

    // Tipo de servicio
    public $correctivo = false;

    public $preventivo = false;

    public $transparencia = false;

    public $a_tec = false;

    public $web_ins = false;

    public $print = false;

    // Via de solicitud
    public $email = false;

    public $tel = false;

    public $sol_ser = false;

    public $oficio = false;

    public $calendario = false;

    public $capturo = '';

    public $status = false;

    public $impressions = false;

    protected $rules = [
        'id_s' => 'nullable|string|max:25',
        'F_serv' => 'nullable|date',
        'solicitante_id' => 'required|exists:users,id',
        'solicitante2_id' => 'nullable|exists:users,id',
        'efectuo_id' => 'required|exists:users,id',
        'vobo_id' => 'required|exists:users,id',
        'obj_sol' => 'nullable|string',
        'actividades' => 'nullable|string',
        'mantenimiento' => 'nullable|string',
        'observaciones' => 'nullable|string',
        'correctivo' => 'boolean',
        'preventivo' => 'boolean',
        'transparencia' => 'boolean',
        'a_tec' => 'boolean',
        'web_ins' => 'boolean',
        'print' => 'boolean',
        'email' => 'boolean',
        'tel' => 'boolean',
        'sol_ser' => 'boolean',
        'oficio' => 'boolean',
        'calendario' => 'boolean',
        'capturo' => 'required|exists:users,id',
        'status' => 'boolean',
        'impressions' => 'boolean',
    ];

    protected $messages = [
        'F_serv.date' => 'La fecha debe ser válida',
        'solicitante_id.exists' => 'El solicitante debe ser un usuario válido',
        'solicitante2_id.exists' => 'El solicitante 2 debe ser un usuario válido',
        'efectuo_id.exists' => 'El usuario que efectuó debe ser válido',
        'vobo_id.exists' => 'El usuario de VºBº debe ser válido',
        'capturo.exists' => 'El usuario que captura debe ser válido',
    ];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
    }

    public function toggleIdSorting()
    {
        if ($this->orderBy === 'id') {
            // Si ya está ordenando por ID, cambiar la dirección
            $this->orderDirection = $this->orderDirection === 'asc' ? 'desc' : 'asc';
        } else {
            // Si no está ordenando por ID, establecer orden ascendente por defecto
            $this->orderBy = 'id';
            $this->orderDirection = 'asc';
        }
        $this->resetPage();
    }

    // Computed property para obtener el icono de ordenamiento
    public function getIdSortIconProperty()
    {
        if ($this->orderBy !== 'id') {
            return 'arrow-up-down'; // Icono neutro cuando no está ordenando por ID
        }

        return $this->orderDirection === 'asc' ? 'arrow-up' : 'arrow-down';
    }

    // Computed property para obtener el texto del botón
    public function getIdSortTextProperty()
    {
        if ($this->orderBy !== 'id') {
            return 'ID';
        }

        return $this->orderDirection === 'asc' ? 'ID ↑' : 'ID ↓';
    }

    public function editService($serviceId)
    {
        // Redirigir a la página de edición
        return redirect()->route('servicios.edit', $serviceId);
    }

    public function toggleStatus($serviceId)
    {
        $service = Service::find($serviceId);
        $service->update(['status' => ! $service->status]);
        session()->flash('message', 'Estado del servicio actualizado.');
    }

    // Métodos para reportes
    public function openReportModal($type = 'general')
    {
        $this->reportType = $type;
        $this->reportDateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->reportDateTo = now()->format('Y-m-d');
        $this->reportUser = '';
        $this->reportStatus = '';
        $this->showReportModal = true;
    }

    public function closeReportModal()
    {
        $this->showReportModal = false;
        $this->reportType = '';
        $this->reportDateFrom = '';
        $this->reportDateTo = '';
        $this->reportUser = '';
        $this->reportStatus = '';
    }

    public function generateReport()
    {
        try {
            $query = Service::with(['solicitante', 'efectuo', 'vobo', 'capturo']);

            // Aplicar filtros según el tipo de reporte
            if ($this->reportDateFrom && $this->reportDateTo) {
                $query->whereBetween('F_serv', [$this->reportDateFrom, $this->reportDateTo]);
            }

            if ($this->reportUser) {
                $query->where(function ($q) {
                    $q->where('solicitante_id', $this->reportUser)
                        ->orWhere('efectuo_id', $this->reportUser)
                        ->orWhere('vobo_id', $this->reportUser)
                        ->orWhere('capturo', $this->reportUser);
                });
            }

            if ($this->reportStatus !== '') {
                $query->where('status', $this->reportStatus);
            }

            $services = $query->orderBy('F_serv', 'desc')->get();

            // Generar el reporte según el tipo
            switch ($this->reportType) {
                case 'general':
                    $filename = $this->generateGeneralReport($services);
                    break;
                case 'por_usuario':
                    $filename = $this->generateUserReport($services);
                    break;
                case 'por_tipo':
                    $filename = $this->generateTypeReport($services);
                    break;
                case 'por_fecha':
                    $filename = $this->generateDateReport($services);
                    break;
                default:
                    $filename = $this->generateGeneralReport($services);
                    break;
            }

            // Cerrar el modal
            $this->closeReportModal();

            // Emitir evento para descargar el archivo
            $this->dispatch('download-report', url: '/storage/temp/'.$filename);

            session()->flash('message', 'Reporte generado correctamente. La descarga comenzará automáticamente.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al generar el reporte: '.$e->getMessage());
        }
    }

    // Método para generar reporte individual de servicio
    public function generateIndividualServiceReport($serviceId)
    {
        try {
            // Buscar el servicio y actualizar los campos
            $service = Service::find($serviceId);
            if ($service) {
                $service->update([
                    'impressions' => true,
                    'status' => true,
                ]);
            }

            // Emitir evento inmediatamente para abrir el PDF en nueva pestaña
            $this->dispatch('openPdfInNewTab', url: route('service.pdf', $serviceId));

            session()->flash('message', 'Reporte del servicio generado correctamente. Los campos de impresión y estado han sido actualizados.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al generar el reporte del servicio: '.$e->getMessage());
        }
    }

    public function generateIndividualServiceReportCal($serviceId)
    {
        try {
            // Buscar el servicio y actualizar los campos
            $service = Service::find($serviceId);
            if ($service) {
                $service->update([
                    'impressions' => true,
                    'status' => true,
                ]);
            }

            // Emitir evento inmediatamente para abrir el PDF en nueva pestaña
            $this->dispatch('openPdfInNewTab', url: route('service.pdf.cal', $serviceId));

            session()->flash('message', 'Reporte del servicio generado correctamente. Los campos de impresión y estado han sido actualizados.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al generar el reporte del servicio: '.$e->getMessage());
        }
    }

    // Método para generar reporte detallado de servicio
    public function generateDetailsServiceReport($serviceId)
    {
        try {
            // Buscar el servicio y actualizar los campos
            $service = Service::find($serviceId);
            if ($service) {
                $service->update([
                    'impressions' => true,
                    'status' => true,
                ]);
            }

            // Emitir evento inmediatamente para abrir el PDF en nueva pestaña
            $this->dispatch('openPdfInNewTab', url: route('service.details.pdf', $serviceId));

            session()->flash('message', 'Reporte detallado del servicio generado correctamente. Los campos de impresión y estado han sido actualizados.');

        } catch (\Exception $e) {
            session()->flash('error', 'Error al generar el reporte detallado del servicio: '.$e->getMessage());
        }
    }

    private function generateGeneralReport($services)
    {
        $data = [
            'title' => 'Reporte General de Servicios',
            'dateRange' => $this->reportDateFrom.' - '.$this->reportDateTo,
            'services' => $services,
            'totalServices' => $services->count(),
            'activeServices' => $services->where('status', true)->count(),
            'inactiveServices' => $services->where('status', false)->count(),
        ];

        $pdf = Pdf::loadView('reportesImpresos.services.general', $data);
        $filename = 'reporte_general_servicios_'.now()->format('Y-m-d_H-i-s').'.pdf';

        // Guardar temporalmente el PDF
        $path = storage_path('app/public/temp/'.$filename);
        if (! file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        file_put_contents($path, $pdf->output());

        return $filename;
    }

    private function generateUserReport($services)
    {
        $userServices = $services->groupBy('solicitante_id');
        $data = [
            'title' => 'Reporte de Servicios por Usuario',
            'dateRange' => $this->reportDateFrom.' - '.$this->reportDateTo,
            'userServices' => $userServices,
            'totalServices' => $services->count(),
        ];

        $pdf = Pdf::loadView('reportesImpresos.services.by_user', $data);
        $filename = 'reporte_servicios_por_usuario_'.now()->format('Y-m-d_H-i-s').'.pdf';

        // Guardar temporalmente el PDF
        $path = storage_path('app/public/temp/'.$filename);
        if (! file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        file_put_contents($path, $pdf->output());

        return $filename;
    }

    private function generateTypeReport($services)
    {
        $typeStats = [
            'correctivo' => $services->where('correctivo', true)->count(),
            'preventivo' => $services->where('preventivo', true)->count(),
            'transparencia' => $services->where('transparencia', true)->count(),
            'a_tec' => $services->where('a_tec', true)->count(),
            'web_ins' => $services->where('web_ins', true)->count(),
            'print' => $services->where('print', true)->count(),
        ];

        $data = [
            'title' => 'Reporte de Servicios por Tipo',
            'dateRange' => $this->reportDateFrom.' - '.$this->reportDateTo,
            'typeStats' => $typeStats,
            'totalServices' => $services->count(),
        ];

        $pdf = Pdf::loadView('reportesImpresos.services.by_type', $data);
        $filename = 'reporte_servicios_por_tipo_'.now()->format('Y-m-d_H-i-s').'.pdf';

        // Guardar temporalmente el PDF
        $path = storage_path('app/public/temp/'.$filename);
        if (! file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        file_put_contents($path, $pdf->output());

        return $filename;
    }

    private function generateDateReport($services)
    {
        $dateStats = $services->groupBy(function ($service) {
            return $service->F_serv ? $service->F_serv->format('Y-m') : 'Sin fecha';
        });

        $data = [
            'title' => 'Reporte de Servicios por Fecha',
            'dateRange' => $this->reportDateFrom.' - '.$this->reportDateTo,
            'dateStats' => $dateStats,
            'totalServices' => $services->count(),
        ];

        $pdf = Pdf::loadView('reportesImpresos.services.by_date', $data);
        $filename = 'reporte_servicios_por_fecha_'.now()->format('Y-m-d_H-i-s').'.pdf';

        // Guardar temporalmente el PDF
        $path = storage_path('app/public/temp/'.$filename);
        if (! file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }
        file_put_contents($path, $pdf->output());

        return $filename;
    }

    // Método de prueba para verificar DomPDF
    public function testPdf()
    {
        $this->dispatch('openPdfInNewTab', url: route('test.pdf'));
    }

    private function NameQrCode($string)
    {
        return preg_replace('/[^0-9]/', '', $string);
    }

    private function cleanString($string)
    {
        if (empty($string)) {
            return '';
        }

        // Intentar diferentes codificaciones
        $encodings = ['UTF-8', 'ISO-8859-1', 'Windows-1252', 'ASCII'];

        foreach ($encodings as $encoding) {
            if (mb_check_encoding($string, $encoding)) {
                $cleaned = mb_convert_encoding($string, 'UTF-8', $encoding);
                if (mb_check_encoding($cleaned, 'UTF-8')) {
                    return $cleaned;
                }
            }
        }

        // Si nada funciona, intentar limpiar caracteres problemáticos
        return preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/', '', $string);
    }

    /**
     * Truncate text to specified number of words
     */
    private function truncateWords($text, $limit = 20, $ending = '...')
    {
        if (empty($text)) {
            return 'N/A';
        }

        // Clean the text first
        $text = $this->cleanString($text);

        // Remove HTML tags for word counting
        $plainText = strip_tags($text);

        // Split into words
        $words = preg_split('/\s+/', trim($plainText));

        if (count($words) <= $limit) {
            return $text;
        }

        // Take first $limit words
        $truncatedWords = array_slice($words, 0, $limit);
        $truncatedText = implode(' ', $truncatedWords);

        return $truncatedText.$ending;
    }

    /**
     * Check if text has more than specified number of words
     */
    private function hasMoreWords($text, $limit = 20)
    {
        if (empty($text)) {
            return false;
        }

        $plainText = strip_tags($text);
        $words = preg_split('/\s+/', trim($plainText));

        return count($words) > $limit;
    }

    public function clearFilters()
    {
        $this->filterIdMin = '';
        $this->filterIdMax = '';
        $this->filterFechaMin = '';
        $this->filterFechaMax = '';
        $this->filterSolicitante = '';
        $this->filterEfectuo = '';
        $this->filterVobo = '';
        $this->filterObjetivo = '';
        $this->filterActividades = '';
        $this->filterObservaciones = '';
        $this->filterFechaServicio = '';
        $this->filterEstado = '';

        // Limpiar filtros de tipo de servicio
        $this->filterCorrectivo = false;
        $this->filterPreventivo = false;
        $this->filterTransparencia = false;
        $this->filterATecnico = false;
        $this->filterWebIns = false;
        $this->filterPrint = false;

        // Limpiar filtros de vía de solicitud
        $this->filterEmail = false;
        $this->filterTelefono = false;
        $this->filterSolicitudServicio = false;
        $this->filterOficio = false;
        $this->filterCalendario = false;

        $this->search = '';
        $this->resetPage();
    }

    // Métodos para manejar cambios en filtros críticos
    public function updatedFilterPreventivo()
    {
        $this->resetPage();
        // Guardar el estado del filtro en la sesión antes de recargar
        session(['filterPreventivo' => $this->filterPreventivo]);
        // Forzar recarga completa de la página para evitar problemas de cache
        $this->dispatch('reload-page');
    }

    public function updatedFilterCalendario()
    {
        $this->resetPage();
        // Guardar el estado del filtro en la sesión antes de recargar
        session(['filterCalendario' => $this->filterCalendario]);
        // Forzar recarga completa de la página para evitar problemas de cache
        $this->dispatch('reload-page');
    }

    public function updatedFilterIdMin()
    {
        // Solo validar si el campo no está vacío
        if ($this->filterIdMin !== '') {
            $this->validateIdFilter();
        }
        // Debug temporal
        \Log::info('FilterIdMin updated:', ['value' => $this->filterIdMin]);
    }

    public function updatedFilterIdMax()
    {
        // Solo validar si el campo no está vacío
        if ($this->filterIdMax !== '') {
            $this->validateIdFilter();
        }
        // Debug temporal
        \Log::info('FilterIdMax updated:', ['value' => $this->filterIdMax]);
    }

    public function updatedFilterFechaMin()
    {
        // Debug temporal
        \Log::info('FilterFechaMin updated:', [
            'value' => $this->filterFechaMin,
            'type' => gettype($this->filterFechaMin),
            'empty' => empty($this->filterFechaMin),
        ]);

        // Limpiar el filtro de fecha del select si se usa el rango
        if ($this->filterFechaMin) {
            $this->filterFechaServicio = '';
        }

        // Si solo hay fecha mínima, aplicar filtro automáticamente
        if ($this->filterFechaMin && ! $this->filterFechaMax) {
            $this->resetPage();
        }
    }

    public function updatedFilterFechaMax()
    {
        // Debug temporal
        \Log::info('FilterFechaMax updated:', ['value' => $this->filterFechaMax]);

        // Limpiar el filtro de fecha del select si se usa el rango
        if ($this->filterFechaMax) {
            $this->filterFechaServicio = '';
        }

        // Si solo hay fecha máxima, aplicar filtro automáticamente
        if ($this->filterFechaMax && ! $this->filterFechaMin) {
            $this->resetPage();
        }
    }

    public function updatedFilterFechaServicio()
    {
        // Debug temporal
        \Log::info('FilterFechaServicio updated:', ['value' => $this->filterFechaServicio]);

        // Limpiar los filtros de rango si se usa el select de fecha
        if ($this->filterFechaServicio) {
            $this->filterFechaMin = '';
            $this->filterFechaMax = '';
            $this->resetPage();
        }
    }

    // Método temporal para debug
    public function testFilter()
    {
        \Log::info('Test Filter - Valores actuales:', [
            'filterFechaMin' => $this->filterFechaMin,
            'filterFechaMax' => $this->filterFechaMax,
            'filterFechaServicio' => $this->filterFechaServicio,
        ]);

        // Probar query manual
        $query = Service::query();
        if ($this->filterFechaMin) {
            $query->whereDate('F_serv', '>=', $this->filterFechaMin);
        }
        if ($this->filterFechaMax) {
            $query->whereDate('F_serv', '<=', $this->filterFechaMax);
        }

        $count = $query->count();
        \Log::info('Test Filter - Resultado:', ['count' => $count]);

        return $count;
    }

    private function validateIdFilter()
    {
        $this->validate([
            'filterIdMin' => 'nullable|integer|min:1',
            'filterIdMax' => 'nullable|integer|min:1',
        ], [
            'filterIdMin.integer' => 'El ID mínimo debe ser un número entero.',
            'filterIdMin.min' => 'El ID mínimo debe ser mayor a 0.',
            'filterIdMax.integer' => 'El ID máximo debe ser un número entero.',
            'filterIdMax.min' => 'El ID máximo debe ser mayor a 0.',
        ]);
    }

    public function exportServices(): StreamedResponse
    {
        abort_unless(Auth::check() && (int) Auth::user()->tipo !== 3, 403);

        $services = $this->servicesQuery()->without(['capturo', 'photos']);
        $filename = 'servicios_'.now()->format('Y-m-d_H-i-s').'.csv';

        return response()->streamDownload(function () use ($services): void {
            $file = fopen('php://output', 'w');

            if ($file === false) {
                throw new \RuntimeException('No fue posible iniciar la exportación de servicios.');
            }

            try {
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
                fputcsv($file, [
                    'ID Servicio',
                    'Servicio',
                    'Código QR',
                    'Fecha de servicio',
                    'Solicitante',
                    'Efectuó',
                    'VºBº',
                    'Objetivo de la solicitud',
                    'Actividades realizadas',
                    'Observaciones',
                    'Estado',
                ]);

                $services->chunk(500, function (Collection $serviceBatch) use ($file): void {
                    foreach ($serviceBatch as $service) {
                        fputcsv($file, [
                            $service->id,
                            $this->safeCsvValue($service->id_s),
                            $this->safeCsvValue(preg_replace('/[^0-9]/', '', (string) $service->id_s)),
                            $service->F_serv?->format('d/m/Y') ?? 'N/A',
                            $this->safeCsvValue($service->solicitante?->name ?? 'N/A'),
                            $this->safeCsvValue($service->efectuo?->name ?? 'N/A'),
                            $this->safeCsvValue($service->vobo?->name ?? 'N/A'),
                            $this->safeCsvValue($this->plainText($service->obj_sol)),
                            $this->safeCsvValue($this->plainText($service->actividades)),
                            $this->safeCsvValue($this->plainText($service->observaciones)),
                            $service->status ? 'Activo' : 'Inactivo',
                        ]);
                    }
                });
            } finally {
                fclose($file);
            }
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }

    private function safeCsvValue(?string $value): string
    {
        $value ??= '';

        return preg_match('/^[\t\r ]*[=+\-@]/u', $value) === 1 ? "'".$value : $value;
    }

    private function plainText(?string $value): string
    {
        $plainText = html_entity_decode(strip_tags($value ?? ''), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim($plainText) !== '' ? trim($plainText) : 'N/A';
    }

    private function servicesQuery(): Builder
    {
        // Forzar recarga completa de relaciones y datos
        $authUser = Auth::user();

        return Service::query()
            ->with(['solicitante', 'efectuo', 'vobo', 'capturo', 'photos'])
            ->when(in_array((int) $authUser->lvl, [4, 5]) && (int) $authUser->tipo === 3, function ($query) use ($authUser) {
                // Los usuarios (tipo 3) de nivel 4 o 5 solo ven servicios de su propia dirección
                $query->whereHas('solicitante', function ($q) use ($authUser) {
                    $q->where('direction', $authUser->direction);
                });
            })
            ->when((int) $authUser->lvl > 5 && (int) $authUser->tipo === 3, function ($query) use ($authUser) {
                // Los usuarios (tipo 3) de nivel mayor a 5 solo ven sus propios servicios como solicitante
                $query->where('solicitante_id', $authUser->id);
            })
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('id_s', 'like', '%'.$this->search.'%')
                        ->orWhere('obj_sol', 'like', '%'.$this->search.'%')
                        ->orWhere('actividades', 'like', '%'.$this->search.'%')
                        ->orWhere('capturo', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->filterSolicitante, function ($query) {
                $query->whereHas('solicitante', function ($q) {
                    $q->where('name', 'like', '%'.$this->filterSolicitante.'%');
                });
            })
            ->when($this->filterEfectuo, function ($query) {
                $query->whereHas('efectuo', function ($q) {
                    $q->where('name', 'like', '%'.$this->filterEfectuo.'%');
                });
            })
            ->when($this->filterVobo, function ($query) {
                $query->whereHas('vobo', function ($q) {
                    $q->where('name', 'like', '%'.$this->filterVobo.'%');
                });
            })
            ->when($this->filterObjetivo, function ($query) {
                $query->where('obj_sol', 'like', '%'.$this->filterObjetivo.'%');
            })
            ->when($this->filterActividades, function ($query) {
                $query->where('actividades', 'like', '%'.$this->filterActividades.'%');
            })
            ->when($this->filterObservaciones, function ($query) {
                $query->where('observaciones', 'like', '%'.$this->filterObservaciones.'%');
            })
            ->when($this->filterFechaMin || $this->filterFechaMax, function ($query) {
                // Filtro por rango de fechas (prioridad sobre filterFechaServicio)
                $fechaMin = $this->filterFechaMin ?: null;
                $fechaMax = $this->filterFechaMax ?: null;

                // Debug temporal
                \Log::info('Aplicando filtro de fecha:', [
                    'filterFechaMin' => $this->filterFechaMin,
                    'filterFechaMax' => $this->filterFechaMax,
                    'fechaMin' => $fechaMin,
                    'fechaMax' => $fechaMax,
                ]);

                if ($fechaMin && $fechaMax) {
                    // Ambos campos llenos: rango de fechas
                    \Log::info('Aplicando rango de fechas:', [$fechaMin, $fechaMax]);
                    if ($fechaMin <= $fechaMax) {
                        $query->whereBetween('F_serv', [$fechaMin, $fechaMax]);
                    } else {
                        // Si la fecha mínima es mayor a la máxima, intercambiar valores
                        $query->whereBetween('F_serv', [$fechaMax, $fechaMin]);
                    }
                } elseif ($fechaMin) {
                    // Solo fecha mínima: mayor o igual
                    \Log::info('Aplicando fecha mínima:', ['fecha' => $fechaMin]);
                    $query->whereDate('F_serv', '>=', $fechaMin);
                } elseif ($fechaMax) {
                    // Solo fecha máxima: menor o igual
                    \Log::info('Aplicando fecha máxima:', ['fecha' => $fechaMax]);
                    $query->whereDate('F_serv', '<=', $fechaMax);
                }
            })
            ->when($this->filterFechaServicio && ! $this->filterFechaMin && ! $this->filterFechaMax, function ($query) {
                // Solo aplicar filterFechaServicio si no hay filtros de rango activos
                $query->whereDate('F_serv', $this->filterFechaServicio);
            })
            ->when($this->filterEstado !== '', function ($query) {
                $query->where('status', $this->filterEstado);
            })
            ->when($this->filterCorrectivo || $this->filterPreventivo || $this->filterTransparencia ||
                   $this->filterATecnico || $this->filterWebIns || $this->filterPrint, function ($query) {
                       // Aplicar filtros de tipo de servicio con lógica AND cuando múltiples están activos
                       $query->where(function ($q) {
                           if ($this->filterCorrectivo) {
                               $q->where('correctivo', true);
                           }
                           if ($this->filterPreventivo) {
                               $q->where('preventivo', true);
                           }
                           if ($this->filterTransparencia) {
                               $q->where('transparencia', true);
                           }
                           if ($this->filterATecnico) {
                               $q->where('a_tec', true);
                           }
                           if ($this->filterWebIns) {
                               $q->where('web_ins', true);
                           }
                           if ($this->filterPrint) {
                               $q->where('print', true);
                           }
                       });
                   })
            ->when($this->filterEmail || $this->filterTelefono || $this->filterSolicitudServicio ||
                          $this->filterOficio || $this->filterCalendario, function ($query) {
                              // Aplicar filtros de vía de solicitud con lógica AND cuando múltiples están activos
                              $query->where(function ($q) {
                                  if ($this->filterEmail) {
                                      $q->where('email', true);
                                  }
                                  if ($this->filterTelefono) {
                                      $q->where('tel', true);
                                  }
                                  if ($this->filterSolicitudServicio) {
                                      $q->where('sol_ser', true);
                                  }
                                  if ($this->filterOficio) {
                                      $q->where('oficio', true);
                                  }
                                  if ($this->filterCalendario) {
                                      $q->where('calendario', true);
                                  }
                              });
                          })
            ->when($this->filterIdMin || $this->filterIdMax, function ($query) {
                // Convertir a enteros, manejar valores vacíos
                $min = $this->filterIdMin ? (int) $this->filterIdMin : null;
                $max = $this->filterIdMax ? (int) $this->filterIdMax : null;

                if ($min && $max) {
                    // Ambos campos llenos: rango de IDs
                    if ($min <= $max) {
                        $query->whereBetween('id', [$min, $max]);
                    } else {
                        // Si el mínimo es mayor al máximo, intercambiar valores
                        $query->whereBetween('id', [$max, $min]);
                    }
                } elseif ($min) {
                    // Solo ID mínimo: mayor o igual
                    $query->where('id', '>=', $min);
                } elseif ($max) {
                    // Solo ID máximo: menor o igual
                    $query->where('id', '<=', $max);
                }
            })
            ->orderBy($this->orderBy, $this->orderDirection);
    }

    public function render(): View
    {
        $services = $this->servicesQuery()->paginate($this->perPage);
        $users = User::orderBy('name')->get();

        return view('livewire.admin.servicios.index', [
            'services' => $services,
            'users' => $users,
        ]);
    }
}

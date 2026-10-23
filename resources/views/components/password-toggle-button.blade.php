{{-- Botón de mostrar/ocultar contraseña (mismo icono que el login). Requiere x-data="{ show: false }" en el contenedor. --}}
<button type="button" class="absolute inset-y-0 right-0 flex items-center pr-3" x-on:click="show = !show" x-bind:aria-label="show ? 'Ocultar contraseña' : 'Mostrar contraseña'" aria-label="Mostrar contraseña">
    <svg class="w-4 h-4 text-gray-400 hover:text-gray-600" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 14">
        <path x-show="!show" d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"/>
        <path x-show="show" style="display: none;" d="m1 1 22 22m-2-2a11.969 11.969 0 0 0 5.74-3.74M12 5c-2.612 0-4.95.981-6.743 2.257l10.999 11c2.646-2.879 4.744-6.644 4.744-10.257 0-1.664-.612-3-1.257-3.743"/>
    </svg>
</button>

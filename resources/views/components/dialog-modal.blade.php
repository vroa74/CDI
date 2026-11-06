@props(['id' => null, 'maxWidth' => null])

<x-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
        <div class="text-lg font-medium text-gray-100">
            {{ $title }}
        </div>

        <div class="mt-4 text-sm text-gray-300">
            {{ $content }}
        </div>
    </div>

    <div class="flex flex-col-reverse gap-3 sm:flex-row sm:justify-end px-6 py-4 bg-gray-800 text-end">
        {{ $footer }}
    </div>
</x-modal>

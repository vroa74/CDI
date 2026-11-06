<?php

namespace App\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ServiceText extends Component
{
    public string $html;

    public string $summary;

    public bool $hasMore;

    public function __construct(?string $value = null, public bool $expandable = false)
    {
        $value ??= 'N/A';
        $this->html = self::format($value);
        $words = explode(' ', strip_tags($value));
        $this->hasMore = count($words) > 20;
        $this->summary = implode(' ', array_slice($words, 0, 20)).'...';
    }

    public static function format(?string $value): string
    {
        $escaped = htmlspecialchars($value ?? '', ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
        $escapedTags = [];
        $safeTags = [];

        // Recuperar solo etiquetas exactas: nunca atributos, enlaces ni contenido activo.
        foreach (['b', 'strong', 'i', 'em', 'u', 'p', 'ul', 'ol', 'li', 'br', 'br/', 'br /'] as $tag) {
            $escapedTags[] = '&lt;'.$tag.'&gt;';
            $safeTags[] = '<'.$tag.'>';

            if (! str_starts_with($tag, 'br')) {
                $escapedTags[] = '&lt;/'.$tag.'&gt;';
                $safeTags[] = '</'.$tag.'>';
            }
        }

        return str_ireplace($escapedTags, $safeTags, $escaped);
    }

    public function render(): View
    {
        return view('components.service-text');
    }
}

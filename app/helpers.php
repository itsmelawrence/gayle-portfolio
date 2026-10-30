<?php

use Illuminate\Support\HtmlString;

if (! function_exists('ph')) {
    /**
     * Wrap placeholder text so it is visibly marked for replacement.
     */
    function ph(string $text): HtmlString
    {
        return new HtmlString('<span class="ph">'.e($text).'</span>');
    }
}

if (! function_exists('fmt')) {
    /**
     * Render [bracketed] fragments as placeholders inside otherwise plain text.
     */
    function fmt(string $text): HtmlString
    {
        $parts = preg_split('/(\[[^\]]*\])/', $text, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

        return new HtmlString(implode('', array_map(
            fn ($part) => str_starts_with($part, '[') ? ph($part)->toHtml() : e($part),
            $parts,
        )));
    }
}

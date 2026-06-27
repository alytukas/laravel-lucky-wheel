@php
    $theme = $settings->theme ?? 'default';
    $viewName = "lucky-wheel::themes.{$theme}";
    if (!view()->exists($viewName)) {
        $viewName = "lucky-wheel::themes.default";
    }
@endphp

@include($viewName, ['settings' => $settings, 'prizes' => $prizes])

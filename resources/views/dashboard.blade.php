<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('partials.head')
    </head>
    <body class="career-app antialiased">
        <main class="min-h-screen">
            <header class="career-header">
                <a href="{{ route('home') }}" class="career-brand">
                    <span class="career-brand-mark"><x-app-logo-icon class="size-5" /></span>
                    <span>Pathfinder <em>/</em> STEM</span>
                </a>
                <div class="career-header-meta">
                    <span class="career-status-dot"></span>
                    <span>Guidance engine online</span>
                    <a href="{{ route('home') }}">Exit workspace</a>
                </div>
            </header>

            <livewire:career-chatbot />
        </main>

        @fluxScripts
    </body>
</html>

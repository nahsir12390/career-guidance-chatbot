<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>STEM Career Guide</title>

        <link rel="icon" href="/favicon.ico" sizes="any">
        <link rel="icon" href="/favicon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="/apple-touch-icon.png">

        @fonts
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="career-app antialiased">
        <main class="min-h-screen">
            <section class="mx-auto flex min-h-screen max-w-[1500px] flex-col px-5 py-5 lg:px-10">
                <header class="career-header !mx-0 !px-0">
                    <a href="{{ route('home') }}" class="career-brand">
                        <span class="career-brand-mark">
                            <x-app-logo-icon class="size-5" />
                        </span>
                        <span>Pathfinder <em>/</em> STEM</span>
                    </a>

                    <div class="career-header-meta">
                        <span class="career-status-dot"></span>
                        <span>Guidance available</span>
                        <a href="{{ route('dashboard') }}">Open chatbot</a>
                    </div>
                </header>

                <div class="grid flex-1 items-center gap-10 py-12 lg:grid-cols-[0.97fr_1.03fr] lg:py-16">
                    <div>
                        <p class="career-kicker">Final year project</p>
                        <h1 class="career-display mt-7 max-w-3xl text-[#18232b]">
                            Career guidance
                            <span class="block text-[#f27a5b]">for the next move.</span>
                        </h1>
                        <p class="mt-6 max-w-2xl text-base leading-8 text-[#68757a]">
                            A practical STEM decision tool that helps students discover suitable career paths using explainable rules, not black-box AI. It recommends pathways, subjects, tools, and project ideas in minutes.
                        </p>

                        <div class="mt-8 flex flex-wrap gap-3">
                            <a href="{{ route('dashboard') }}" class="career-button career-button-accent">
                                Start assessment
                            </a>
                            <a href="#preview" class="career-button career-button-quiet">
                                See preview
                            </a>
                        </div>

                        <div class="mt-10 flex flex-wrap items-center gap-6 text-xs font-medium uppercase tracking-[0.12em] text-[#68757a]">
                            <span>Rule-based matching</span>
                            <span class="h-px w-7 bg-[#d5d1c9]"></span>
                            <span>Career clarity</span>
                            <span class="h-px w-7 bg-[#d5d1c9]"></span>
                            <span>STEM roadmap</span>
                        </div>
                    </div>

                    <div id="preview" class="career-panel bg-white p-4 sm:p-5">
                        <div class="rounded-[6px] bg-[#18232b] p-5 text-white sm:p-6">
                            <div class="flex items-start justify-between gap-4">
                                <div>
                                    <p class="career-kicker !text-[#c7e86a]">Live recommendation</p>
                                    <h2 class="mt-2 text-2xl font-extrabold tracking-tight">Software Developer</h2>
                                </div>
                                <span class="inline-flex items-center justify-center rounded-[4px] bg-[#c7e86a] px-3 py-2 text-sm font-extrabold text-[#18232b]">92%</span>
                            </div>
                            <p class="mt-4 text-sm leading-6 text-[#d5d9dc]">
                                Matched from Computer Science, app building, logical thinking, and hands-on learning.
                            </p>
                        </div>

                        <div class="mt-4 grid gap-3 sm:grid-cols-3">
                            <div class="bg-[#f7f5f1] p-4">
                                <p class="career-label">Subject</p>
                                <p class="mt-2 text-sm font-bold text-[#18232b]">Computer Science</p>
                            </div>
                            <div class="bg-[#f7f5f1] p-4">
                                <p class="career-label">Tool</p>
                                <p class="mt-2 text-sm font-bold text-[#18232b]">Laravel</p>
                            </div>
                            <div class="bg-[#f7f5f1] p-4">
                                <p class="career-label">Project</p>
                                <p class="mt-2 text-sm font-bold text-[#18232b]">Result checker</p>
                            </div>
                        </div>

                        <div class="mt-4 rounded-[6px] border border-[#dedbd4] bg-[#f9f7f3] p-4">
                            <p class="text-sm font-extrabold text-[#18232b]">Advisor chat</p>
                            <p class="mt-2 text-sm leading-6 text-[#68757a]">Ask why the path fits, what to learn, or what project to build first.</p>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </body>
</html>

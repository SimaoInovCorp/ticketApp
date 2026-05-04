<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

defineOptions({
    layout: {
        breadcrumbs: [{ title: 'About', href: '/about' }],
    },
});
</script>

<template>
    <Head title="About" />

    <div class="flex max-w-3xl flex-col gap-8 p-6">
        <div>
            <h1 class="text-3xl font-bold">TicketApp</h1>
            <p class="mt-2 text-muted-foreground">
                A modern, self-hosted customer support ticket system built for
                teams that value simplicity and clarity.
            </p>
        </div>

        <!-- Author -->
        <div class="rounded-lg border bg-card p-6">
            <h2 class="mb-4 text-xl font-semibold">Author</h2>
            <div class="flex items-start gap-4">
                <div
                    class="flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-primary text-lg font-bold text-primary-foreground"
                >
                    SM
                </div>
                <div>
                    <p class="text-lg font-semibold">Simão Morais</p>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Full-stack developer passionate about clean
                        architecture, SOLID principles, and building tools that
                        actually work. This project follows strict Clean
                        Architecture with SOLID principles applied throughout
                        the Laravel backend and Vue 3 frontend.
                    </p>
                </div>
            </div>
        </div>

        <!-- What this app does -->
        <div class="rounded-lg border bg-card p-6">
            <h2 class="mb-4 text-xl font-semibold">What is TicketApp?</h2>
            <p class="text-sm leading-relaxed text-muted-foreground">
                TicketApp is a lightweight support desk application designed for
                small to medium-sized businesses. Operators manage support
                requests (tickets) organised into inboxes — each inbox
                representing a support channel or department. Customers are
                represented as contacts linked to entities (organisations).
                Tickets move through configurable statuses and can be assigned
                to specific operators, keeping support workflows clear and
                auditable.
            </p>
            <ul
                class="mt-4 list-inside list-disc space-y-1 text-sm text-muted-foreground"
            >
                <li>Multi-inbox support with operator assignment per inbox</li>
                <li>
                    Full ticket thread with internal notes and file attachments
                </li>
                <li>Entity &amp; contact management with role-based access</li>
                <li>Configurable ticket types and statuses</li>
                <li>Activity log on every ticket for full audit trail</li>
                <li>Email notifications on ticket creation and replies</li>
            </ul>
        </div>

        <!-- Tech stack -->
        <div class="rounded-lg border bg-card p-6">
            <h2 class="mb-4 text-xl font-semibold">Technology Stack</h2>
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <h3
                        class="mb-2 text-sm font-semibold tracking-wide text-muted-foreground uppercase"
                    >
                        Backend
                    </h3>
                    <ul class="space-y-1 text-sm">
                        <li class="flex items-center gap-2">
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-primary"
                            ></span>
                            PHP 8.3
                        </li>
                        <li class="flex items-center gap-2">
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-primary"
                            ></span>
                            Laravel 12
                        </li>
                        <li class="flex items-center gap-2">
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-primary"
                            ></span>
                            Laravel Fortify (authentication)
                        </li>
                        <li class="flex items-center gap-2">
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-primary"
                            ></span>
                            Inertia.js 3 (SPA bridge)
                        </li>
                        <li class="flex items-center gap-2">
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-primary"
                            ></span>
                            Pest (testing)
                        </li>
                        <li class="flex items-center gap-2">
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-primary"
                            ></span>
                            MySQL / SQLite
                        </li>
                    </ul>
                </div>
                <div>
                    <h3
                        class="mb-2 text-sm font-semibold tracking-wide text-muted-foreground uppercase"
                    >
                        Frontend
                    </h3>
                    <ul class="space-y-1 text-sm">
                        <li class="flex items-center gap-2">
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-primary"
                            ></span>
                            Vue 3 (Composition API)
                        </li>
                        <li class="flex items-center gap-2">
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-primary"
                            ></span>
                            TypeScript
                        </li>
                        <li class="flex items-center gap-2">
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-primary"
                            ></span>
                            Tailwind CSS v4
                        </li>
                        <li class="flex items-center gap-2">
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-primary"
                            ></span>
                            Reka UI (shadcn/vue components)
                        </li>
                        <li class="flex items-center gap-2">
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-primary"
                            ></span>
                            Vite + Laravel Wayfinder
                        </li>
                        <li class="flex items-center gap-2">
                            <span
                                class="h-1.5 w-1.5 rounded-full bg-primary"
                            ></span>
                            Lucide Vue icons
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Architecture note -->
        <div class="rounded-lg border border-primary/20 bg-primary/5 p-6">
            <h2
                class="mb-2 text-sm font-semibold tracking-wide text-primary uppercase"
            >
                Architecture
            </h2>
            <p class="text-sm leading-relaxed text-muted-foreground">
                This application is built following
                <strong class="text-foreground">SOLID principles</strong> and
                Clean Architecture patterns. Controllers are thin — they only
                authorise and delegate. All business logic lives in Services.
                Validation is handled exclusively in FormRequests. API responses
                are shaped by Laravel Resources. The Vue frontend communicates
                with the backend via Inertia.js, with TypeScript action types
                auto-generated from PHP routes by Laravel Wayfinder.
            </p>
        </div>
    </div>
</template>

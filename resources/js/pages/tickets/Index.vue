<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';
import WebTicketController from '@/actions/App/Http/Controllers/TicketController';
import type { Inbox } from '@/types/inbox';
import type { Ticket, TicketStatus, TicketType } from '@/types/ticket';
import TicketCard from '@/components/tickets/TicketCard.vue';
import TicketFilters from '@/components/tickets/TicketFilters.vue';

interface Props {
    tickets: {
        data: Ticket[];
        links: unknown;
        meta: { current_page: number; last_page: number; total: number };
    };
    inboxes: Inbox[];
    statuses: TicketStatus[];
    types: TicketType[];
    operators: { id: number; name: string }[];
    filters: Record<string, string>;
}

const props = defineProps<Props>();

const filters = ref({ ...props.filters });

watch(
    filters,
    (val) => {
        router.get(WebTicketController.index.url(), val, {
            preserveState: true,
            replace: true,
        });
    },
    { deep: true },
);

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Tickets', href: WebTicketController.index.url() },
        ],
    },
});
</script>

<template>
    <Head title="Tickets" />

    <div class="flex flex-col gap-4 p-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Tickets</h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    View and manage all support tickets you have access to. Use
                    the filters below to narrow down by inbox, status, type, or
                    operator. Click any ticket to open it.
                </p>
            </div>
            <a
                :href="WebTicketController.create.url()"
                class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                title="Open a new support ticket"
            >
                New Ticket
            </a>
        </div>

        <TicketFilters
            v-model="filters"
            :inboxes="inboxes"
            :statuses="statuses"
            :types="types"
            :operators="operators"
        />

        <div class="flex flex-col gap-2">
            <TicketCard
                v-for="ticket in tickets.data"
                :key="ticket.id"
                :ticket="ticket"
            />
        </div>

        <p
            v-if="tickets.data.length === 0"
            class="py-8 text-center text-muted-foreground"
        >
            No tickets found.
        </p>

        <div
            v-if="tickets.meta.last_page > 1"
            class="flex justify-center gap-2 pt-4"
        >
            <span class="text-sm text-muted-foreground">
                Page {{ tickets.meta.current_page }} of
                {{ tickets.meta.last_page }} ({{ tickets.meta.total }} total)
            </span>
        </div>
    </div>
</template>

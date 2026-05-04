<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import WebTicketController from '@/actions/App/Http/Controllers/TicketController';
import type { Ticket } from '@/types/ticket';
import TicketStatusBadge from './TicketStatusBadge.vue';

defineProps<{ ticket: Ticket }>();
</script>

<template>
    <div
        class="flex cursor-pointer items-start gap-4 rounded-lg border bg-card p-4 hover:bg-muted/30"
        @click="router.visit(WebTicketController.show.url(ticket.id))"
    >
        <div class="min-w-0 flex-1">
            <div class="flex flex-wrap items-center gap-2">
                <span
                    class="font-mono text-xs font-medium text-muted-foreground"
                    >{{ ticket.number }}</span
                >
                <TicketStatusBadge
                    v-if="ticket.status"
                    :status="ticket.status"
                />
                <span class="text-xs text-muted-foreground">{{
                    ticket.inbox?.name
                }}</span>
            </div>
            <p class="mt-1 truncate font-medium">{{ ticket.subject }}</p>
            <div
                class="mt-1 flex items-center gap-3 text-xs text-muted-foreground"
            >
                <span v-if="ticket.entity">{{ ticket.entity.name }}</span>
                <span v-if="ticket.contact">{{ ticket.contact.name }}</span>
                <span v-if="ticket.operator">→ {{ ticket.operator.name }}</span>
            </div>
        </div>
        <div class="shrink-0 text-right text-xs text-muted-foreground">
            <p>{{ ticket.messages_count }} msg</p>
            <p>{{ new Date(ticket.created_at).toLocaleDateString('en-GB') }}</p>
        </div>
    </div>
</template>

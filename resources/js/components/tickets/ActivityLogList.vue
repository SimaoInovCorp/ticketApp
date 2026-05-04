<script setup lang="ts">
import type { ActivityLog } from '@/types/ticket';

defineProps<{ logs: ActivityLog[] }>();

function labelFor(log: ActivityLog): string {
    switch (log.action) {
        case 'created':
            return 'Ticket created';
        case 'message_added':
            return 'New message';
        case 'operator_assigned':
            return log.payload?.user_name
                ? `Operator assigned: ${log.payload.user_name}`
                : 'Operator removed';
        case 'status_changed':
            return `Status changed: ${log.payload?.from} → ${log.payload?.to}`;
        default:
            return log.action;
    }
}
</script>

<template>
    <div class="relative space-y-3 pl-4">
        <div class="absolute top-0 left-1.5 h-full w-px bg-border" />
        <div v-for="log in logs" :key="log.id" class="relative text-xs">
            <div
                class="absolute top-1 -left-3 h-2 w-2 rounded-full bg-muted-foreground/50"
            />
            <p class="font-medium">{{ labelFor(log) }}</p>
            <p class="text-muted-foreground">
                {{ log.user?.name ?? 'System' }} ·
                {{ new Date(log.created_at).toLocaleString('en-GB') }}
            </p>
        </div>
    </div>
</template>

<script setup lang="ts">
import type { Inbox } from '@/types/inbox';
import type { TicketStatus, TicketType } from '@/types/ticket';

const model = defineModel<Record<string, string>>({ required: true });

defineProps<{
    inboxes: Inbox[];
    statuses: TicketStatus[];
    types: TicketType[];
    operators: { id: number; name: string }[];
}>();
</script>

<template>
    <div class="flex flex-wrap gap-3 rounded-lg border bg-muted/20 p-3">
        <input
            :value="model.search ?? ''"
            type="text"
            placeholder="Search..."
            class="rounded-md border bg-background px-3 py-1.5 text-sm"
            @input="
                model = {
                    ...model,
                    search: ($event.target as HTMLInputElement).value,
                }
            "
        />

        <select
            :value="model.inbox_id ?? ''"
            class="rounded-md border bg-background px-3 py-1.5 text-sm"
            @change="
                model = {
                    ...model,
                    inbox_id: ($event.target as HTMLSelectElement).value,
                }
            "
        >
            <option value="">All inboxes</option>
            <option v-for="inbox in inboxes" :key="inbox.id" :value="inbox.id">
                {{ inbox.name }}
            </option>
        </select>

        <select
            :value="model.ticket_status_id ?? ''"
            class="rounded-md border bg-background px-3 py-1.5 text-sm"
            @change="
                model = {
                    ...model,
                    ticket_status_id: ($event.target as HTMLSelectElement)
                        .value,
                }
            "
        >
            <option value="">All statuses</option>
            <option
                v-for="status in statuses"
                :key="status.id"
                :value="status.id"
            >
                {{ status.name }}
            </option>
        </select>

        <select
            :value="model.ticket_type_id ?? ''"
            class="rounded-md border bg-background px-3 py-1.5 text-sm"
            @change="
                model = {
                    ...model,
                    ticket_type_id: ($event.target as HTMLSelectElement).value,
                }
            "
        >
            <option value="">All types</option>
            <option v-for="type in types" :key="type.id" :value="type.id">
                {{ type.name }}
            </option>
        </select>

        <select
            :value="model.operator_id ?? ''"
            class="rounded-md border bg-background px-3 py-1.5 text-sm"
            @change="
                model = {
                    ...model,
                    operator_id: ($event.target as HTMLSelectElement).value,
                }
            "
        >
            <option value="">All operators</option>
            <option v-for="op in operators" :key="op.id" :value="op.id">
                {{ op.name }}
            </option>
        </select>
    </div>
</template>

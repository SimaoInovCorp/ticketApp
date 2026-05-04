<script setup lang="ts">
import type { TicketMessage } from '@/types/ticket';

const props = defineProps<{
    messages: TicketMessage[];
    currentUserId: number;
}>();
</script>

<template>
    <div class="flex flex-col gap-3">
        <div
            v-for="message in messages"
            :key="message.id"
            :class="[
                'rounded-lg border p-4',
                message.is_internal
                    ? 'border-amber-300 bg-amber-50 dark:bg-amber-900/20'
                    : 'bg-card',
            ]"
        >
            <div
                class="mb-2 flex items-center justify-between text-xs text-muted-foreground"
            >
                <span class="font-medium text-foreground">{{
                    message.author?.name ?? 'System'
                }}</span>
                <div class="flex items-center gap-2">
                    <span
                        v-if="message.is_internal"
                        class="rounded bg-amber-200 px-1.5 py-0.5 text-xs font-medium text-amber-800 dark:bg-amber-800 dark:text-amber-200"
                    >
                        Internal note
                    </span>
                    <span>{{
                        new Date(message.created_at).toLocaleString('en-GB')
                    }}</span>
                </div>
            </div>

            <!-- eslint-disable-next-line vue/no-v-html -->
            <div
                class="prose prose-sm dark:prose-invert max-w-none"
                v-html="message.body"
            />

            <div
                v-if="message.attachments.length"
                class="mt-3 flex flex-wrap gap-2"
            >
                <a
                    v-for="att in message.attachments"
                    :key="att.id"
                    :href="att.url"
                    target="_blank"
                    class="inline-flex items-center gap-1 rounded-md border px-2 py-1 text-xs hover:bg-muted"
                >
                    📎 {{ att.original_name }}
                    <span class="text-muted-foreground"
                        >({{ Math.round(att.size / 1024) }} KB)</span
                    >
                </a>
            </div>
        </div>

        <p
            v-if="messages.length === 0"
            class="py-4 text-center text-sm text-muted-foreground"
        >
            No messages yet.
        </p>
    </div>
</template>

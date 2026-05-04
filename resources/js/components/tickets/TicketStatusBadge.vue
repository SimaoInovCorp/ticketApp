<script setup lang="ts">
import type { TicketStatus } from '@/types/ticket';

defineProps<{ status: TicketStatus }>();
</script>

<template>
    <span
        class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium text-white"
        :style="{ backgroundColor: status.color }"
    >
        {{ status.name }}
    </span>
</template>

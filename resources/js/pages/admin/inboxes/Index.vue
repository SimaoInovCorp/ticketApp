<script setup lang="ts">
import { computed } from 'vue';
import axios from 'axios';
import { Head, router } from '@inertiajs/vue3';
import { toast } from 'vue-sonner';
import AdminInboxController from '@/actions/App/Http/Controllers/Admin/InboxController';
import ApiInboxController from '@/actions/App/Http/Controllers/Api/InboxController';
import type { Inbox } from '@/types/inbox';

interface Props {
    inboxes: Inbox[] | { data: Inbox[] };
    operators: { id: number; name: string }[];
}

const props = defineProps<Props>();
const inboxList = computed(() =>
    Array.isArray(props.inboxes) ? props.inboxes : (props.inboxes?.data ?? []),
);

function assignedOperatorIds(inbox: Inbox): number[] {
    return inbox.operators?.map((op) => op.id) ?? [];
}

function syncOperators(inbox: Inbox, event: Event) {
    const select = event.target as HTMLSelectElement;
    const selected = Array.from(select.selectedOptions).map((o) => +o.value);
    axios
        .post(ApiInboxController.syncOperators.url(inbox.id), {
            user_ids: selected,
        })
        .then(() => {
            router.reload({ only: ['inboxes'] });
        })
        .catch((error: unknown) => {
            const msg = (
                error as { response?: { data?: { message?: string } } }
            )?.response?.data?.message;
            toast.error(msg ?? 'Failed to sync operators.');
        });
}

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Admin', href: '#' },
            { title: 'Inboxes', href: AdminInboxController.index.url() },
        ],
    },
});
</script>

<template>
    <Head title="Inbox Management" />

    <div class="flex flex-col gap-6 p-4">
        <div>
            <h1 class="text-2xl font-semibold">Inbox Management</h1>
            <p class="mt-1 text-sm text-muted-foreground">
                Inboxes group support tickets by department or topic. Use the
                multi-select below each inbox card to assign operators who will
                handle that inbox's tickets. Hold
                <kbd class="rounded border px-1 font-mono text-xs">Ctrl</kbd>
                (or
                <kbd class="rounded border px-1 font-mono text-xs">⌘</kbd> on
                Mac) to select multiple operators.
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div
                v-for="inbox in inboxList"
                :key="inbox.id"
                class="rounded-lg border bg-card p-4"
            >
                <h2 class="font-semibold">{{ inbox.name }}</h2>
                <p class="text-sm text-muted-foreground">{{ inbox.slug }}</p>
                <p
                    v-if="inbox.description"
                    class="mt-1 text-sm text-muted-foreground"
                >
                    {{ inbox.description }}
                </p>
                <p class="mt-2 text-xs text-muted-foreground">
                    {{ inbox.operators_count ?? inbox.operators?.length ?? 0 }}
                    operator(s) assigned
                </p>

                <div class="mt-3">
                    <label class="mb-1 block text-xs font-medium"
                        >Assigned Operators</label
                    >
                    <select
                        multiple
                        class="w-full rounded-md border bg-background px-2 py-1 text-sm"
                        @change="syncOperators(inbox, $event)"
                    >
                        <option
                            v-for="op in operators"
                            :key="op.id"
                            :value="op.id"
                            :selected="
                                assignedOperatorIds(inbox).includes(op.id)
                            "
                        >
                            {{ op.name }}
                        </option>
                    </select>
                    <p class="mt-1 text-xs text-muted-foreground">
                        Currently highlighted = already assigned. Click to
                        toggle.
                    </p>
                </div>
            </div>
        </div>

        <p v-if="!inboxList.length" class="text-muted-foreground">
            No inboxes configured.
        </p>
    </div>
</template>

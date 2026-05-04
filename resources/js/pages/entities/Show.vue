<script setup lang="ts">
import { computed } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import EntityController from '@/actions/App/Http/Controllers/EntityController';
import type { Entity, Contact } from '@/types/entity';
import type { Ticket } from '@/types/ticket';
import TicketCard from '@/components/tickets/TicketCard.vue';
import ConfirmDeleteDialog from '@/components/ConfirmDeleteDialog.vue';

interface Props {
    entity: Entity & { contacts: Contact[] };
    tickets: Ticket[] | { data: Ticket[] };
}

const props = defineProps<Props>();

const ticketList = computed(() =>
    Array.isArray(props.tickets) ? props.tickets : (props.tickets?.data ?? []),
);

function deleteEntity() {
    router.delete(EntityController.destroy.url(props.entity.id));
}

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Entities', href: EntityController.index.url() },
        ],
    },
});
</script>

<template>
    <Head :title="entity.name" />

    <div class="flex flex-col gap-6 p-4">
        <!-- Entity header -->
        <div class="rounded-lg border bg-card p-4">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">{{ entity.name }}</h1>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Organisation details, associated contacts and recent
                        tickets. Use the Edit button to update information, or
                        Delete to remove this entity.
                    </p>
                </div>
                <div class="flex gap-2">
                    <a
                        :href="
                            entity.id
                                ? EntityController.edit.url(entity.id)
                                : '#'
                        "
                        class="rounded-md border px-3 py-1.5 text-sm hover:bg-muted"
                        title="Edit this entity's information"
                        >Edit</a
                    >
                    <ConfirmDeleteDialog
                        title="Delete entity?"
                        description="This will permanently delete the entity and all its records. This cannot be undone."
                        @confirm="deleteEntity()"
                    >
                        <button
                            type="button"
                            class="rounded-md border border-destructive px-3 py-1.5 text-sm text-destructive hover:bg-destructive/10"
                            title="Permanently delete this entity and all its records"
                        >
                            Delete
                        </button>
                    </ConfirmDeleteDialog>
                </div>
            </div>
            <dl
                class="mt-3 grid grid-cols-2 gap-x-6 gap-y-2 text-sm md:grid-cols-3"
            >
                <div v-if="entity.nif">
                    <dt class="text-muted-foreground">NIF</dt>
                    <dd>{{ entity.nif }}</dd>
                </div>
                <div v-if="entity.email">
                    <dt class="text-muted-foreground">Email</dt>
                    <dd>{{ entity.email }}</dd>
                </div>
                <div v-if="entity.phone">
                    <dt class="text-muted-foreground">Phone</dt>
                    <dd>{{ entity.phone }}</dd>
                </div>
                <div v-if="entity.mobile">
                    <dt class="text-muted-foreground">Mobile</dt>
                    <dd>{{ entity.mobile }}</dd>
                </div>
                <div v-if="entity.website">
                    <dt class="text-muted-foreground">Website</dt>
                    <dd>
                        <a
                            :href="entity.website"
                            target="_blank"
                            class="text-primary underline"
                            >{{ entity.website }}</a
                        >
                    </dd>
                </div>
            </dl>
            <div
                v-if="entity.internal_notes"
                class="mt-3 rounded-md bg-amber-50 p-3 text-sm dark:bg-amber-900/20"
            >
                <p class="font-medium text-amber-700 dark:text-amber-400">
                    Internal notes
                </p>
                <p
                    class="mt-1 whitespace-pre-wrap text-amber-800 dark:text-amber-300"
                >
                    {{ entity.internal_notes }}
                </p>
            </div>
        </div>

        <!-- Contacts -->
        <div class="rounded-lg border bg-card p-4">
            <h2 class="mb-3 font-semibold">Contacts</h2>
            <div v-if="entity.contacts?.length" class="divide-y">
                <div
                    v-for="contact in entity.contacts"
                    :key="contact.id"
                    class="py-2 text-sm"
                >
                    <div class="flex items-center justify-between">
                        <span class="font-medium">{{ contact.name }}</span>
                        <span
                            v-if="contact.role"
                            class="text-xs text-muted-foreground"
                            >{{ contact.role.name }}</span
                        >
                    </div>
                    <p v-if="contact.email" class="text-muted-foreground">
                        {{ contact.email }}
                    </p>
                </div>
            </div>
            <p v-else class="text-sm text-muted-foreground">
                No associated contacts.
            </p>
        </div>

        <!-- Recent tickets -->
        <div>
            <h2 class="mb-3 font-semibold">Recent tickets</h2>
            <div v-if="ticketList.length" class="flex flex-col gap-2">
                <TicketCard
                    v-for="ticket in ticketList"
                    :key="ticket.id"
                    :ticket="ticket"
                />
            </div>
            <p v-else class="text-sm text-muted-foreground">No tickets.</p>
        </div>
    </div>
</template>

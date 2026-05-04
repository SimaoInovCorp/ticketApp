<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import ContactController from '@/actions/App/Http/Controllers/ContactController';
import type { Contact } from '@/types/entity';
import ConfirmDeleteDialog from '@/components/ConfirmDeleteDialog.vue';

const props = defineProps<{ contact: Contact }>();

function deleteContact() {
    router.delete(ContactController.destroy.url(props.contact.id));
}

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Contacts', href: ContactController.index.url() },
        ],
    },
});
</script>

<template>
    <Head :title="contact.name" />

    <div class="flex flex-col gap-6 p-4">
        <div class="rounded-lg border bg-card p-4">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-2xl font-semibold">
                        {{ contact.name }}
                    </h1>
                    <p
                        v-if="contact.role"
                        class="mt-1 text-sm text-muted-foreground"
                    >
                        {{ contact.role.name }}
                    </p>
                    <p class="mt-1 text-xs text-muted-foreground">
                        Contact profile. Use Edit to update details or Delete to
                        remove. Linked entities are shown below.
                    </p>
                </div>
                <div class="flex gap-2">
                    <a
                        :href="
                            contact.id
                                ? ContactController.edit.url(contact.id)
                                : '#'
                        "
                        class="rounded-md border px-3 py-1.5 text-sm hover:bg-muted"
                        title="Edit this contact's information"
                        >Edit</a
                    >
                    <ConfirmDeleteDialog
                        title="Delete contact?"
                        description="This will permanently delete the contact and all its records. This cannot be undone."
                        @confirm="deleteContact()"
                    >
                        <button
                            type="button"
                            class="rounded-md border border-destructive px-3 py-1.5 text-sm text-destructive hover:bg-destructive/10"
                            title="Permanently delete this contact"
                        >
                            Delete
                        </button>
                    </ConfirmDeleteDialog>
                </div>
            </div>

            <dl
                class="mt-4 grid grid-cols-2 gap-x-6 gap-y-2 text-sm md:grid-cols-3"
            >
                <div v-if="contact.email">
                    <dt class="text-muted-foreground">Email</dt>
                    <dd>{{ contact.email }}</dd>
                </div>
                <div v-if="contact.phone">
                    <dt class="text-muted-foreground">Phone</dt>
                    <dd>{{ contact.phone }}</dd>
                </div>
                <div v-if="contact.mobile">
                    <dt class="text-muted-foreground">Mobile</dt>
                    <dd>{{ contact.mobile }}</dd>
                </div>
            </dl>

            <div
                v-if="contact.internal_notes"
                class="mt-3 rounded-md bg-amber-50 p-3 text-sm dark:bg-amber-900/20"
            >
                <p class="font-medium text-amber-700 dark:text-amber-400">
                    Internal notes
                </p>
                <p
                    class="mt-1 whitespace-pre-wrap text-amber-800 dark:text-amber-300"
                >
                    {{ contact.internal_notes }}
                </p>
            </div>
        </div>

        <div
            v-if="contact.entities?.length"
            class="rounded-lg border bg-card p-4"
        >
            <h2 class="mb-3 font-semibold">Associated Entities</h2>
            <ul class="divide-y text-sm">
                <li
                    v-for="entity in contact.entities"
                    :key="entity.id"
                    class="py-2"
                >
                    <a
                        :href="`/entities/${entity.id}`"
                        class="text-primary hover:underline"
                    >
                        {{ entity.name }}
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

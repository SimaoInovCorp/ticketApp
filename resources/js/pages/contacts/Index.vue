<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import ContactController from '@/actions/App/Http/Controllers/ContactController';
import SearchInput from '@/components/SearchInput.vue';
import ConfirmDeleteDialog from '@/components/ConfirmDeleteDialog.vue';
import type { Contact } from '@/types/entity';

interface Props {
    contacts: {
        data: Contact[];
        meta: { current_page: number; last_page: number; total: number };
    };
    filters: Record<string, string>;
}

const props = defineProps<Props>();
const search = ref(props.filters.search ?? '');

function applySearch() {
    router.get(
        ContactController.index.url(),
        { search: search.value },
        { preserveState: true, replace: true },
    );
}

function deleteContact(id: number) {
    router.delete(ContactController.destroy.url(id));
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
    <Head title="Contacts" />

    <div class="flex flex-col gap-4 p-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Contacts</h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Contacts are individuals associated with entities who can
                    submit or be linked to tickets. Click a contact's name to
                    view their profile and linked entities.
                </p>
            </div>
            <a
                :href="ContactController.create.url()"
                class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                title="Add a new contact person"
            >
                New Contact
            </a>
        </div>

        <SearchInput
            v-model="search"
            placeholder="Search by name or email…"
            @search="applySearch"
        />

        <div class="rounded-lg border">
            <table class="w-full text-sm">
                <thead class="border-b bg-muted/40">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Name</th>
                        <th class="px-4 py-3 text-left font-medium">Role</th>
                        <th class="px-4 py-3 text-left font-medium">Email</th>
                        <th class="px-4 py-3 text-left font-medium">
                            Entities
                        </th>
                        <th class="px-4 py-3 text-right font-medium">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="contact in contacts.data"
                        :key="contact.id"
                        class="cursor-pointer border-b hover:bg-muted/20"
                        @click="
                            router.visit(ContactController.show.url(contact.id))
                        "
                    >
                        <td class="px-4 py-3 font-medium">
                            {{ contact.name }}
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">
                            {{ contact.role?.name ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">
                            {{ contact.email ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">
                            {{
                                contact.entities
                                    .map((e) => e.name)
                                    .join(', ') || '—'
                            }}
                        </td>
                        <td class="px-4 py-3 text-right" @click.stop>
                            <div class="flex items-center justify-end gap-2">
                                <a
                                    :href="
                                        ContactController.edit.url(contact.id)
                                    "
                                    class="rounded px-2 py-1 text-xs hover:bg-muted"
                                    title="Edit contact"
                                    >Edit</a
                                >
                                <ConfirmDeleteDialog
                                    title="Delete contact?"
                                    description="This will permanently delete the contact and cannot be undone."
                                    @confirm="deleteContact(contact.id)"
                                >
                                    <button
                                        type="button"
                                        class="rounded px-2 py-1 text-xs text-destructive hover:bg-destructive/10"
                                        title="Delete contact"
                                    >
                                        Delete
                                    </button>
                                </ConfirmDeleteDialog>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <p
                v-if="contacts.data.length === 0"
                class="py-8 text-center text-muted-foreground"
            >
                No contacts found.
            </p>
        </div>
    </div>
</template>

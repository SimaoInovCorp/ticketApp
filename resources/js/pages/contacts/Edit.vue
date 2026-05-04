<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import ContactController from '@/actions/App/Http/Controllers/ContactController';
import type { Contact, ContactRole, Entity } from '@/types/entity';

const props = defineProps<{
    contact: Contact;
    roles: ContactRole[];
    entities: Pick<Entity, 'id' | 'name'>[];
}>();

const form = useForm({
    name: props.contact.name,
    email: props.contact.email ?? '',
    phone: props.contact.phone ?? '',
    mobile: props.contact.mobile ?? '',
    contact_role_id: props.contact.role?.id ?? ('' as string | number),
    internal_notes: props.contact.internal_notes ?? '',
    entity_ids: (props.contact.entities ?? []).map((e) => e.id),
});

function toggleEntity(id: number) {
    const idx = form.entity_ids.indexOf(id);
    if (idx >= 0) {
        form.entity_ids.splice(idx, 1);
    } else {
        form.entity_ids.push(id);
    }
}

function submit() {
    form.put(ContactController.update.url(props.contact.id));
}

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Contacts', href: ContactController.index.url() },
            { title: 'Edit', href: '' },
        ],
    },
});
</script>

<template>
    <Head :title="`Edit – ${contact.name}`" />

    <div class="mx-auto max-w-2xl p-4">
        <h1 class="mb-1 text-2xl font-semibold">Edit Contact</h1>
        <p class="mb-6 text-sm text-muted-foreground">
            Update the contact's information and their linked entities. Changes
            are saved when you click Save.
        </p>

        <form class="space-y-4" @submit.prevent="submit">
            <div class="space-y-4 rounded-lg border bg-card p-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium"
                            >Name *</label
                        >
                        <input
                            v-model="form.name"
                            type="text"
                            class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                            required
                        />
                        <p
                            v-if="form.errors.name"
                            class="mt-1 text-xs text-destructive"
                        >
                            {{ form.errors.name }}
                        </p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium"
                            >Role</label
                        >
                        <select
                            v-model="form.contact_role_id"
                            class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                        >
                            <option value="">No role</option>
                            <option
                                v-for="role in roles"
                                :key="role.id"
                                :value="role.id"
                            >
                                {{ role.name }}
                            </option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium"
                            >Email</label
                        >
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                        />
                        <p
                            v-if="form.errors.email"
                            class="mt-1 text-xs text-destructive"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium"
                            >Phone</label
                        >
                        <input
                            v-model="form.phone"
                            type="text"
                            class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                        />
                        <p
                            v-if="form.errors.phone"
                            class="mt-1 text-xs text-destructive"
                        >
                            {{ form.errors.phone }}
                        </p>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">Mobile</label>
                    <input
                        v-model="form.mobile"
                        type="text"
                        class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                    />
                    <p
                        v-if="form.errors.mobile"
                        class="mt-1 text-xs text-destructive"
                    >
                        {{ form.errors.mobile }}
                    </p>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium"
                        >Internal Notes</label
                    >
                    <textarea
                        v-model="form.internal_notes"
                        rows="3"
                        class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                    />
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium"
                        >Entities *
                        <span class="text-muted-foreground"
                            >(select at least one)</span
                        ></label
                    >
                    <div
                        class="max-h-48 space-y-1 overflow-y-auto rounded-md border bg-background p-2"
                    >
                        <label
                            v-for="entity in entities"
                            :key="entity.id"
                            class="flex cursor-pointer items-center gap-2 rounded px-2 py-1 text-sm hover:bg-muted"
                        >
                            <input
                                type="checkbox"
                                :checked="form.entity_ids.includes(entity.id)"
                                @change="toggleEntity(entity.id)"
                            />
                            {{ entity.name }}
                        </label>
                    </div>
                    <p
                        v-if="form.errors.entity_ids"
                        class="mt-1 text-xs text-destructive"
                    >
                        {{ form.errors.entity_ids }}
                    </p>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a
                    :href="ContactController.show.url(contact.id)"
                    class="rounded-md border px-4 py-2 text-sm hover:bg-muted"
                    >Cancel</a
                >
                <button
                    type="submit"
                    class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                    :disabled="form.processing"
                >
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</template>

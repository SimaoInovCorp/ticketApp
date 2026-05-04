<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import EntityController from '@/actions/App/Http/Controllers/EntityController';

const form = useForm({
    name: '',
    nif: '',
    email: '',
    phone: '',
    mobile: '',
    website: '',
    internal_notes: '',
});

function submit() {
    form.post(EntityController.store.url(), {
        onSuccess: () => form.reset(),
    });
}

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Entities', href: EntityController.index.url() },
            { title: 'New Entity', href: '' },
        ],
    },
});
</script>

<template>
    <Head title="New Entity" />

    <div class="mx-auto max-w-2xl p-4">
        <h1 class="mb-1 text-2xl font-semibold">New Entity</h1>
        <p class="mb-6 text-sm text-muted-foreground">
            Register a new company or organisation. The entity groups contacts
            and their tickets together. Only the name is required — the other
            fields can be filled in later.
        </p>

        <form class="space-y-4" @submit.prevent="submit">
            <div class="space-y-4 rounded-lg border bg-card p-4">
                <div>
                    <label class="mb-1 block text-sm font-medium">Name *</label>
                    <input
                        v-model="form.name"
                        type="text"
                        class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                        placeholder="Organisation name"
                        required
                    />
                    <p
                        v-if="form.errors.name"
                        class="mt-1 text-xs text-destructive"
                    >
                        {{ form.errors.name }}
                    </p>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium"
                            >Tax ID (NIF)</label
                        >
                        <input
                            v-model="form.nif"
                            type="text"
                            class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                            placeholder="9 digits"
                        />
                        <p
                            v-if="form.errors.nif"
                            class="mt-1 text-xs text-destructive"
                        >
                            {{ form.errors.nif }}
                        </p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium"
                            >Email</label
                        >
                        <input
                            v-model="form.email"
                            type="email"
                            class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                            placeholder="contact@company.com"
                        />
                        <p
                            v-if="form.errors.email"
                            class="mt-1 text-xs text-destructive"
                        >
                            {{ form.errors.email }}
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="mb-1 block text-sm font-medium"
                            >Phone</label
                        >
                        <input
                            v-model="form.phone"
                            type="text"
                            class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                            placeholder="+351..."
                        />
                        <p
                            v-if="form.errors.phone"
                            class="mt-1 text-xs text-destructive"
                        >
                            {{ form.errors.phone }}
                        </p>
                    </div>
                    <div>
                        <label class="mb-1 block text-sm font-medium"
                            >Mobile</label
                        >
                        <input
                            v-model="form.mobile"
                            type="text"
                            class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                            placeholder="+351..."
                        />
                        <p
                            v-if="form.errors.mobile"
                            class="mt-1 text-xs text-destructive"
                        >
                            {{ form.errors.mobile }}
                        </p>
                    </div>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium"
                        >Website</label
                    >
                    <input
                        v-model="form.website"
                        type="url"
                        class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                        placeholder="https://..."
                    />
                    <p
                        v-if="form.errors.website"
                        class="mt-1 text-xs text-destructive"
                    >
                        {{ form.errors.website }}
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
                        placeholder="Private notes visible only to operators..."
                    />
                    <p
                        v-if="form.errors.internal_notes"
                        class="mt-1 text-xs text-destructive"
                    >
                        {{ form.errors.internal_notes }}
                    </p>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <a
                    :href="EntityController.index.url()"
                    class="rounded-md border px-4 py-2 text-sm hover:bg-muted"
                    >Cancel</a
                >
                <button
                    type="submit"
                    class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                    :disabled="form.processing"
                >
                    Create Entity
                </button>
            </div>
        </form>
    </div>
</template>

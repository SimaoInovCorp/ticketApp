<script setup lang="ts">
import { Head, router } from '@inertiajs/vue3';
import { ref } from 'vue';
import EntityController from '@/actions/App/Http/Controllers/EntityController';
import SearchInput from '@/components/SearchInput.vue';
import ConfirmDeleteDialog from '@/components/ConfirmDeleteDialog.vue';
import type { Entity } from '@/types/entity';

interface Props {
    entities: {
        data: Entity[];
        meta: { current_page: number; last_page: number; total: number };
    };
    filters: Record<string, string>;
}

const props = defineProps<Props>();
const search = ref(props.filters.search ?? '');

function applySearch() {
    router.get(
        EntityController.index.url(),
        { search: search.value },
        { preserveState: true, replace: true },
    );
}

function deleteEntity(id: number) {
    router.delete(EntityController.destroy.url(id));
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
    <Head title="Entities" />

    <div class="flex flex-col gap-4 p-4">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-semibold">Entities</h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    Entities represent companies or organisations that submit
                    support tickets. Click on a name to view their details,
                    contacts, and ticket history.
                </p>
            </div>
            <a
                :href="EntityController.create.url()"
                class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                title="Add a new organisation"
            >
                New Entity
            </a>
        </div>

        <SearchInput
            v-model="search"
            placeholder="Search by name, tax ID or email…"
            @search="applySearch"
        />

        <div class="rounded-lg border">
            <table class="w-full text-sm">
                <thead class="border-b bg-muted/40">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Name</th>
                        <th class="px-4 py-3 text-left font-medium">Tax ID</th>
                        <th class="px-4 py-3 text-left font-medium">Email</th>
                        <th class="px-4 py-3 text-left font-medium">Phone</th>
                        <th class="px-4 py-3 text-right font-medium">
                            Actions
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="entity in entities.data"
                        :key="entity.id"
                        class="cursor-pointer border-b hover:bg-muted/20"
                        @click="
                            router.visit(EntityController.show.url(entity.id))
                        "
                    >
                        <td class="px-4 py-3 font-medium">{{ entity.name }}</td>
                        <td class="px-4 py-3 text-muted-foreground">
                            {{ entity.nif ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">
                            {{ entity.email ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-muted-foreground">
                            {{ entity.phone ?? entity.mobile ?? '—' }}
                        </td>
                        <td class="px-4 py-3 text-right" @click.stop>
                            <div class="flex items-center justify-end gap-2">
                                <a
                                    :href="EntityController.show.url(entity.id)"
                                    class="rounded px-2 py-1 text-xs hover:bg-muted"
                                    title="View entity details and contacts"
                                    >Details</a
                                >
                                <a
                                    :href="EntityController.edit.url(entity.id)"
                                    class="rounded px-2 py-1 text-xs hover:bg-muted"
                                    title="Edit entity"
                                    >Edit</a
                                >
                                <ConfirmDeleteDialog
                                    title="Delete entity?"
                                    description="This will permanently delete the entity and cannot be undone."
                                    @confirm="deleteEntity(entity.id)"
                                >
                                    <button
                                        type="button"
                                        class="rounded px-2 py-1 text-xs text-destructive hover:bg-destructive/10"
                                        title="Delete entity"
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
                v-if="entities.data.length === 0"
                class="py-8 text-center text-muted-foreground"
            >
                No entities found.
            </p>
        </div>
    </div>
</template>

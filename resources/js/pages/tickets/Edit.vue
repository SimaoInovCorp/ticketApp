<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import WebTicketController from '@/actions/App/Http/Controllers/TicketController';
import type { Ticket, TicketType } from '@/types/ticket';

const props = defineProps<{ ticket: Ticket; types: TicketType[] }>();

const form = useForm({
    subject: props.ticket.subject,
    ticket_type_id: props.ticket.type?.id ?? '',
    knowledge_emails: props.ticket.knowledge_emails ?? [],
});

const knowledgeEmailInput = ref('');

function addEmail() {
    const email = knowledgeEmailInput.value.trim();
    if (email && !form.knowledge_emails.includes(email)) {
        form.knowledge_emails.push(email);
    }
    knowledgeEmailInput.value = '';
}

function removeEmail(index: number) {
    form.knowledge_emails.splice(index, 1);
}

function submit() {
    form.put(WebTicketController.update.url(props.ticket.id));
}

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Tickets', href: WebTicketController.index.url() },
            { title: 'Edit', href: '' },
        ],
    },
});
</script>

<template>
    <Head :title="`Edit — ${ticket.number}`" />
    <div class="mx-auto max-w-2xl p-4">
        <h1 class="mb-1 text-2xl font-semibold">Edit Ticket</h1>
        <p class="mb-6 text-sm text-muted-foreground">
            Update the subject, type, or CC addresses for this ticket.
        </p>
        <form class="space-y-4" @submit.prevent="submit">
            <div>
                <label class="mb-1 block text-sm font-medium">Subject *</label>
                <input
                    v-model="form.subject"
                    type="text"
                    class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                    required
                />
                <p
                    v-if="form.errors.subject"
                    class="mt-1 text-xs text-destructive"
                >
                    {{ form.errors.subject }}
                </p>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium">Type</label>
                <select
                    v-model="form.ticket_type_id"
                    class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                >
                    <option value="">No type</option>
                    <option
                        v-for="type in types"
                        :key="type.id"
                        :value="type.id"
                    >
                        {{ type.name }}
                    </option>
                </select>
            </div>
            <div>
                <label class="mb-1 block text-sm font-medium"
                    >CC Knowledge</label
                >
                <div class="flex gap-2">
                    <input
                        v-model="knowledgeEmailInput"
                        type="email"
                        class="flex-1 rounded-md border bg-background px-3 py-2 text-sm"
                        placeholder="email@example.com"
                        @keydown.enter.prevent="addEmail"
                    />
                    <button
                        type="button"
                        class="rounded-md border px-3 py-2 text-sm hover:bg-muted"
                        @click="addEmail"
                    >
                        Add
                    </button>
                </div>
                <div
                    v-if="form.knowledge_emails.length"
                    class="mt-2 flex flex-wrap gap-1"
                >
                    <span
                        v-for="(email, i) in form.knowledge_emails"
                        :key="i"
                        class="inline-flex items-center gap-1 rounded-full bg-muted px-2 py-0.5 text-xs"
                    >
                        {{ email }}
                        <button
                            type="button"
                            class="text-muted-foreground hover:text-foreground"
                            @click="removeEmail(i)"
                        >
                            ×
                        </button>
                    </span>
                </div>
            </div>
            <div class="flex justify-end gap-3 pt-2">
                <a
                    :href="WebTicketController.show.url(ticket.id)"
                    class="rounded-md border px-4 py-2 text-sm hover:bg-muted"
                >
                    Cancel
                </a>
                <button
                    type="submit"
                    :disabled="form.processing"
                    class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
                >
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</template>

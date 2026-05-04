<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import WebTicketController from '@/actions/App/Http/Controllers/TicketController';
import type { Inbox } from '@/types/inbox';
import type { TicketType } from '@/types/ticket';

interface Props {
    inboxes: Inbox[];
    types: TicketType[];
    operators: { id: number; name: string }[];
    contacts: { id: number; name: string; email: string | null }[];
}

defineProps<Props>();

const form = useForm({
    subject: '',
    inbox_id: '',
    ticket_type_id: '',
    entity_id: '',
    contact_id: '',
    knowledge_emails: [] as string[],
    message: '',
    attachments: [] as File[],
    operator_id: '',
});

const knowledgeEmailInput = ref('');

function addKnowledgeEmail() {
    const email = knowledgeEmailInput.value.trim();
    if (email && !form.knowledge_emails.includes(email)) {
        form.knowledge_emails.push(email);
    }
    knowledgeEmailInput.value = '';
}

function removeKnowledgeEmail(index: number) {
    form.knowledge_emails.splice(index, 1);
}

function handleFileChange(event: Event) {
    const input = event.target as HTMLInputElement;
    if (input.files) {
        form.attachments = Array.from(input.files);
    }
}

function submit() {
    form.post(WebTicketController.store.url(), {
        forceFormData: true,
    });
}

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Tickets', href: WebTicketController.index.url() },
            { title: 'New Ticket', href: '' },
        ],
    },
});
</script>

<template>
    <Head title="New Ticket" />

    <div class="mx-auto max-w-2xl p-4">
        <h1 class="mb-1 text-2xl font-semibold">New Ticket</h1>
        <p class="mb-6 text-sm text-muted-foreground">
            Fill in the form below to open a new support ticket. Select the
            inbox that best matches the request category, choose the contact who
            submitted the request, and provide a clear subject and message.
        </p>

        <form class="space-y-4" @submit.prevent="submit">
            <div>
                <label class="mb-1 block text-sm font-medium">Subject *</label>
                <input
                    v-model="form.subject"
                    type="text"
                    class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                    placeholder="Brief summary of the request"
                    required
                />
                <p
                    v-if="form.errors.subject"
                    class="mt-1 text-xs text-destructive"
                >
                    {{ form.errors.subject }}
                </p>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-1 block text-sm font-medium"
                        >Inbox *</label
                    >
                    <select
                        v-model="form.inbox_id"
                        class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                        required
                    >
                        <option value="">Select inbox...</option>
                        <option
                            v-for="inbox in inboxes"
                            :key="inbox.id"
                            :value="inbox.id"
                        >
                            {{ inbox.name }}
                        </option>
                    </select>
                    <p
                        v-if="form.errors.inbox_id"
                        class="mt-1 text-xs text-destructive"
                    >
                        {{ form.errors.inbox_id }}
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
            </div>

            <!-- Contact (required for operator) -->
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="mb-1 block text-sm font-medium"
                        >Contact *</label
                    >
                    <select
                        v-model="form.contact_id"
                        class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                        required
                    >
                        <option value="">Select contact...</option>
                        <option
                            v-for="contact in contacts"
                            :key="contact.id"
                            :value="contact.id"
                        >
                            {{ contact.name
                            }}{{ contact.email ? ` (${contact.email})` : '' }}
                        </option>
                    </select>
                    <p
                        v-if="form.errors.contact_id"
                        class="mt-1 text-xs text-destructive"
                    >
                        {{ form.errors.contact_id }}
                    </p>
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium"
                        >Assign to Operator</label
                    >
                    <select
                        v-model="form.operator_id"
                        class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                    >
                        <option value="">Unassigned</option>
                        <option
                            v-for="op in operators"
                            :key="op.id"
                            :value="op.id"
                        >
                            {{ op.name }}
                        </option>
                    </select>
                </div>
            </div>

            <!-- CC Knowledge emails -->
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
                        @keydown.enter.prevent="addKnowledgeEmail"
                    />
                    <button
                        type="button"
                        class="rounded-md border px-3 py-2 text-sm hover:bg-muted"
                        @click="addKnowledgeEmail"
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
                            @click="removeKnowledgeEmail(i)"
                        >
                            ×
                        </button>
                    </span>
                </div>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium">Message *</label>
                <textarea
                    v-model="form.message"
                    rows="6"
                    class="w-full rounded-md border bg-background px-3 py-2 text-sm"
                    placeholder="Describe your request..."
                    required
                />
                <p
                    v-if="form.errors.message"
                    class="mt-1 text-xs text-destructive"
                >
                    {{ form.errors.message }}
                </p>
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium"
                    >Attachments</label
                >
                <input
                    type="file"
                    multiple
                    accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.zip"
                    class="text-sm"
                    @change="handleFileChange"
                />
                <p class="mt-1 text-xs text-muted-foreground">
                    Max. 10 files · 10 MB each
                </p>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a
                    :href="WebTicketController.index.url()"
                    class="rounded-md border px-4 py-2 text-sm hover:bg-muted"
                    >Cancel</a
                >
                <button
                    type="submit"
                    class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                    :disabled="form.processing"
                >
                    Create Ticket
                </button>
            </div>
        </form>
    </div>
</template>

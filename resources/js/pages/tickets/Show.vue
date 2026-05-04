<script setup lang="ts">
import { Head, router, usePage } from '@inertiajs/vue3';
import axios from 'axios';
import { ref } from 'vue';
import { toast } from 'vue-sonner';
import ApiTicketController from '@/actions/App/Http/Controllers/Api/TicketController';
import ApiTicketMessageController from '@/actions/App/Http/Controllers/Api/TicketMessageController';
import WebTicketController from '@/actions/App/Http/Controllers/TicketController';
import type { Ticket, TicketStatus } from '@/types/ticket';
import MessageThread from '@/components/tickets/MessageThread.vue';
import ReplyForm from '@/components/tickets/ReplyForm.vue';
import ActivityLogList from '@/components/tickets/ActivityLogList.vue';
import TicketStatusBadge from '@/components/tickets/TicketStatusBadge.vue';
import ConfirmDeleteDialog from '@/components/ConfirmDeleteDialog.vue';

interface Props {
    ticket: Ticket;
    operators: { id: number; name: string }[];
    statuses: TicketStatus[];
}

const props = defineProps<Props>();
const page = usePage();
const user = page.props.auth.user;
const testingEmail = ref(false);

defineOptions({
    layout: {
        breadcrumbs: [
            { title: 'Tickets', href: WebTicketController.index.url() },
        ],
    },
});

async function assignOperator(operatorId: number | null) {
    try {
        await axios.patch(ApiTicketController.assign.url(props.ticket.id), {
            operator_id: operatorId,
        });
        router.reload({ only: ['ticket'] });
    } catch (error: unknown) {
        const msg = (error as { response?: { data?: { message?: string } } })
            ?.response?.data?.message;
        toast.error(msg ?? 'Failed to assign operator.');
    }
}

async function changeStatus(statusId: number) {
    try {
        await axios.patch(
            ApiTicketController.changeStatus.url(props.ticket.id),
            {
                status_id: statusId,
            },
        );
        router.reload({ only: ['ticket'] });
    } catch (error: unknown) {
        const msg = (error as { response?: { data?: { message?: string } } })
            ?.response?.data?.message;
        toast.error(msg ?? 'Failed to change status.');
    }
}

async function handleReply(data: {
    body: string;
    is_internal: boolean;
    files: File[];
}) {
    const form = new FormData();
    form.append('body', data.body);
    form.append('is_internal', data.is_internal ? '1' : '0');
    data.files.forEach((f) => form.append('attachments[]', f));

    try {
        await axios.post(
            ApiTicketMessageController.store.url(props.ticket.id),
            form,
        );
        router.reload({ only: ['ticket'] });
    } catch (error: unknown) {
        const msg = (error as { response?: { data?: { message?: string } } })
            ?.response?.data?.message;
        toast.error(msg ?? 'Failed to send reply.');
    }
}

async function sendTestEmail() {
    testingEmail.value = true;
    try {
        const { data } = await axios.post(
            ApiTicketController.testEmail.url(props.ticket.id),
        );
        toast.success(data.message ?? 'Test email sent!');
    } catch (error: unknown) {
        const msg = (error as { response?: { data?: { message?: string } } })
            ?.response?.data?.message;
        toast.error(msg ?? 'Failed to send test email.');
    } finally {
        testingEmail.value = false;
    }
}

function deleteTicket() {
    router.delete(WebTicketController.destroy.url(props.ticket.id));
}
</script>

<template>
    <Head :title="`${ticket.number} — ${ticket.subject}`" />

    <div class="flex flex-col gap-6 p-4 lg:flex-row">
        <!-- Main content -->
        <div class="flex flex-1 flex-col gap-4">
            <div class="rounded-lg border bg-card p-4">
                <div class="mb-2 flex items-center gap-3">
                    <span
                        class="font-mono text-sm font-medium text-muted-foreground"
                        >{{ ticket.number }}</span
                    >
                    <TicketStatusBadge
                        v-if="ticket.status"
                        :status="ticket.status"
                    />
                </div>
                <h1 class="text-xl font-semibold">{{ ticket.subject }}</h1>
                <p class="mt-1 text-sm text-muted-foreground">
                    {{ ticket.inbox?.name ?? '—' }}
                    <template v-if="ticket.type">
                        · {{ ticket.type.name }}</template
                    >
                </p>
                <p class="mt-2 text-xs text-muted-foreground">
                    Read the conversation thread below. Use the reply box to add
                    a response or an internal note (only visible to operators).
                    Change status or reassign using the panel on the right.
                </p>
            </div>

            <MessageThread
                :messages="ticket.messages ?? []"
                :current-user-id="user.id"
            />

            <ReplyForm
                :is-operator="user.role === 'operator'"
                @submitted="handleReply"
            />
        </div>

        <!-- Sidebar meta -->
        <div class="w-full space-y-4 lg:w-72">
            <div class="rounded-lg border bg-card p-4 text-sm">
                <h2 class="mb-3 font-semibold">Details</h2>

                <dl class="space-y-2">
                    <div>
                        <dt class="text-muted-foreground">Entity</dt>
                        <dd>{{ ticket.entity?.name ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-muted-foreground">Contact</dt>
                        <dd>{{ ticket.contact?.name ?? '—' }}</dd>
                    </div>
                    <div v-if="ticket.operator">
                        <dt class="text-muted-foreground">Operator</dt>
                        <dd>{{ ticket.operator.name }}</dd>
                    </div>
                </dl>

                <!-- Operator controls -->
                <template v-if="user.role === 'operator'">
                    <hr class="my-3" />
                    <div class="space-y-2">
                        <label
                            class="text-xs font-medium text-muted-foreground"
                            title="Select the current workflow status of this ticket"
                            >Change status</label
                        >
                        <select
                            :value="ticket.status?.id ?? ''"
                            class="w-full rounded-md border bg-background px-2 py-1.5 text-sm"
                            title="Update ticket status — e.g. mark as In Progress or Closed"
                            @change="
                                changeStatus(
                                    +($event.target as HTMLSelectElement).value,
                                )
                            "
                        >
                            <option
                                v-for="s in statuses"
                                :key="s.id"
                                :value="s.id"
                            >
                                {{ s.name }}
                            </option>
                        </select>

                        <label
                            class="text-xs font-medium text-muted-foreground"
                            title="Assign this ticket to a specific operator"
                            >Assign operator</label
                        >
                        <select
                            :value="ticket.operator?.id ?? ''"
                            class="w-full rounded-md border bg-background px-2 py-1.5 text-sm"
                            title="Choose which operator is responsible for this ticket"
                            @change="
                                assignOperator(
                                    +($event.target as HTMLSelectElement)
                                        .value || null,
                                )
                            "
                        >
                            <option value="">— No operator —</option>
                            <option
                                v-for="op in operators"
                                :key="op.id"
                                :value="op.id"
                            >
                                {{ op.name }}
                            </option>
                        </select>
                    </div>
                </template>

                <!-- Test email -->
                <hr class="my-3" />
                <button
                    type="button"
                    :disabled="testingEmail"
                    class="w-full rounded-md border border-dashed px-3 py-1.5 text-xs text-muted-foreground hover:border-primary hover:text-primary disabled:opacity-50"
                    title="Send a test copy of the ticket-created email to your own address to verify mail delivery"
                    @click="sendTestEmail"
                >
                    {{ testingEmail ? 'Sending…' : 'Send test email to me' }}
                </button>

                <!-- Edit / Delete (operators only) -->
                <template v-if="user.role === 'operator'">
                    <hr class="my-3" />
                    <div class="flex gap-2">
                        <a
                            :href="WebTicketController.edit.url(ticket.id)"
                            class="flex-1 rounded-md border px-3 py-1.5 text-center text-sm hover:bg-muted"
                        >
                            Edit
                        </a>
                        <ConfirmDeleteDialog
                            :title="`Delete ticket ${ticket.number}?`"
                            description="This will permanently delete the ticket and all its messages. This cannot be undone."
                            @confirm="deleteTicket"
                        >
                            <button
                                type="button"
                                class="flex-1 rounded-md border border-destructive px-3 py-1.5 text-sm text-destructive hover:bg-destructive/10"
                            >
                                Delete
                            </button>
                        </ConfirmDeleteDialog>
                    </div>
                </template>
            </div>

            <div
                v-if="ticket.activity_logs?.length"
                class="rounded-lg border bg-card p-4"
            >
                <h2 class="mb-3 text-sm font-semibold">Activity</h2>
                <ActivityLogList :logs="ticket.activity_logs" />
            </div>
        </div>
    </div>
</template>

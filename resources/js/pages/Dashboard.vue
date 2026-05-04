<script setup lang="ts">
import { Head } from '@inertiajs/vue3';
import { Building2, CheckCircle, Inbox, Ticket, Users } from 'lucide-vue-next';
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card';
import { dashboard } from '@/routes';

defineProps<{
    stats: {
        total_tickets: number;
        open_tickets: number;
        closed_tickets: number;
        entities: number | null;
        contacts: number | null;
    };
}>();

defineOptions({
    layout: {
        breadcrumbs: [
            {
                title: 'Dashboard',
                href: dashboard(),
            },
        ],
    },
});
</script>

<template>
    <Head title="Dashboard" />

    <div class="flex flex-1 flex-col gap-6 p-4">
        <div>
            <h1 class="text-2xl font-semibold">Dashboard</h1>
            <p class="mt-1 text-sm text-muted-foreground">
                Overview of your support activity. Use the navigation on the
                left to manage tickets, entities, and contacts.
            </p>
        </div>

        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-4">
            <Card>
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0 pb-2"
                >
                    <CardTitle class="text-sm font-medium"
                        >Total Tickets</CardTitle
                    >
                    <Ticket class="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">
                        {{ stats.total_tickets }}
                    </div>
                    <p class="text-xs text-muted-foreground">
                        All tickets assigned to you
                    </p>
                </CardContent>
            </Card>

            <Card>
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0 pb-2"
                >
                    <CardTitle class="text-sm font-medium"
                        >Open Tickets</CardTitle
                    >
                    <Inbox class="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">
                        {{ stats.open_tickets }}
                    </div>
                    <p class="text-xs text-muted-foreground">
                        Awaiting resolution
                    </p>
                </CardContent>
            </Card>

            <Card>
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0 pb-2"
                >
                    <CardTitle class="text-sm font-medium"
                        >Closed Tickets</CardTitle
                    >
                    <CheckCircle class="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">
                        {{ stats.closed_tickets }}
                    </div>
                    <p class="text-xs text-muted-foreground">
                        Successfully resolved
                    </p>
                </CardContent>
            </Card>

            <Card v-if="stats.entities !== null">
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0 pb-2"
                >
                    <CardTitle class="text-sm font-medium">Entities</CardTitle>
                    <Building2 class="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">{{ stats.entities }}</div>
                    <p class="text-xs text-muted-foreground">
                        Registered organisations
                    </p>
                </CardContent>
            </Card>

            <Card v-if="stats.contacts !== null">
                <CardHeader
                    class="flex flex-row items-center justify-between space-y-0 pb-2"
                >
                    <CardTitle class="text-sm font-medium">Contacts</CardTitle>
                    <Users class="h-4 w-4 text-muted-foreground" />
                </CardHeader>
                <CardContent>
                    <div class="text-2xl font-bold">{{ stats.contacts }}</div>
                    <p class="text-xs text-muted-foreground">
                        Registered contacts
                    </p>
                </CardContent>
            </Card>
        </div>
    </div>
</template>

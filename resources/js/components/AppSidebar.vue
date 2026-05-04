<script setup lang="ts">
import { Link, usePage } from '@inertiajs/vue3';
import {
    Building2,
    Info,
    Inbox,
    LayoutGrid,
    Ticket,
    Users,
} from 'lucide-vue-next';
import { computed } from 'vue';
import AppLogo from '@/components/AppLogo.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import AdminInboxController from '@/actions/App/Http/Controllers/Admin/InboxController';
import ContactController from '@/actions/App/Http/Controllers/ContactController';
import EntityController from '@/actions/App/Http/Controllers/EntityController';
import WebTicketController from '@/actions/App/Http/Controllers/TicketController';
import { dashboard } from '@/routes';
import type { NavItem } from '@/types';

const page = usePage();
const isOperator = computed(
    () => (page.props.auth?.user as any)?.role === 'operator',
);

const mainNavItems = computed<NavItem[]>(() => {
    const items: NavItem[] = [
        {
            title: 'Dashboard',
            href: dashboard(),
            icon: LayoutGrid,
        },
        {
            title: 'Tickets',
            href: WebTicketController.index.url(),
            icon: Ticket,
        },
    ];

    if (isOperator.value) {
        items.push(
            {
                title: 'Entities',
                href: EntityController.index.url(),
                icon: Building2,
            },
            {
                title: 'Contacts',
                href: ContactController.index.url(),
                icon: Users,
            },
        );
    }

    items.push({
        title: 'About',
        href: '/about',
        icon: Info,
    });

    return items;
});

const adminNavItems = computed<NavItem[]>(() =>
    isOperator.value
        ? [
              {
                  title: 'Inboxes',
                  href: AdminInboxController.index.url(),
                  icon: Inbox,
              },
          ]
        : [],
);
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="dashboard()">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>

        <SidebarContent>
            <NavMain :items="mainNavItems" label="Navigation" />
            <NavMain
                v-if="adminNavItems.length"
                :items="adminNavItems"
                label="Administration"
            />
        </SidebarContent>

        <SidebarFooter>
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>

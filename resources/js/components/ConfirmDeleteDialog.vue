<script setup lang="ts">
import { ref } from 'vue';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogClose,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
    DialogTrigger,
} from '@/components/ui/dialog';

defineProps<{
    title?: string;
    description?: string;
}>();

const emit = defineEmits<{ confirm: [] }>();

const open = ref(false);

function handleConfirm() {
    open.value = false;
    emit('confirm');
}
</script>

<template>
    <Dialog v-model:open="open">
        <DialogTrigger as-child>
            <slot />
        </DialogTrigger>
        <DialogContent>
            <DialogHeader>
                <DialogTitle>{{ title ?? 'Are you sure?' }}</DialogTitle>
                <DialogDescription>
                    {{
                        description ??
                        'This action cannot be undone. This will permanently delete the record.'
                    }}
                </DialogDescription>
            </DialogHeader>
            <DialogFooter>
                <DialogClose as-child>
                    <Button variant="outline">Cancel</Button>
                </DialogClose>
                <Button variant="destructive" @click="handleConfirm">
                    Delete
                </Button>
            </DialogFooter>
        </DialogContent>
    </Dialog>
</template>

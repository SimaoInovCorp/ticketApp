<script setup lang="ts">
import { Search } from 'lucide-vue-next';
import { Input } from '@/components/ui/input';
import { Button } from '@/components/ui/button';

const props = withDefaults(
    defineProps<{
        modelValue: string;
        placeholder?: string;
        /** Show the search button. Defaults to true. */
        showButton?: boolean;
    }>(),
    {
        placeholder: 'Search…',
        showButton: true,
    },
);

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'search'): void;
}>();
</script>

<template>
    <div class="flex items-center gap-2">
        <div class="relative flex-1">
            <Search
                class="absolute top-1/2 left-3 h-4 w-4 -translate-y-1/2 text-muted-foreground"
            />
            <Input
                :model-value="modelValue"
                :placeholder="placeholder"
                class="pl-9"
                title="Type to filter results"
                @update:model-value="emit('update:modelValue', String($event))"
                @keydown.enter="emit('search')"
            />
        </div>
        <Button
            v-if="showButton"
            variant="outline"
            size="sm"
            title="Apply search filter"
            @click="emit('search')"
        >
            Search
        </Button>
    </div>
</template>

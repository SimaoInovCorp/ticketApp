<script setup lang="ts">
import { ref } from 'vue';

const props = defineProps<{ isOperator: boolean }>();
const emit = defineEmits<{
    submitted: [data: { body: string; is_internal: boolean; files: File[] }];
}>();

const body = ref('');
const isInternal = ref(false);
const files = ref<File[]>([]);

function handleFileChange(event: Event) {
    const input = event.target as HTMLInputElement;
    if (input.files) {
        files.value = Array.from(input.files);
    }
}

function submit() {
    if (!body.value.trim()) return;
    emit('submitted', {
        body: body.value,
        is_internal: isInternal.value,
        files: files.value,
    });
    body.value = '';
    isInternal.value = false;
    files.value = [];
}
</script>

<template>
    <div class="rounded-lg border bg-card p-4">
        <h3 class="mb-3 text-sm font-semibold">Reply</h3>

        <textarea
            v-model="body"
            rows="4"
            class="w-full rounded-md border bg-background px-3 py-2 text-sm"
            placeholder="Write your reply..."
        />

        <div class="mt-2 flex flex-wrap items-center justify-between gap-3">
            <div class="flex items-center gap-4">
                <label class="flex cursor-pointer items-center gap-1 text-sm">
                    <input
                        type="file"
                        multiple
                        accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.zip"
                        class="text-xs"
                        @change="handleFileChange"
                    />
                </label>
                <label
                    v-if="isOperator"
                    class="flex cursor-pointer items-center gap-2 text-sm"
                >
                    <input
                        v-model="isInternal"
                        type="checkbox"
                        class="rounded"
                    />
                    Internal note
                </label>
            </div>
            <button
                type="button"
                class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90 disabled:opacity-50"
                :disabled="!body.trim()"
                @click="submit"
            >
                Send
            </button>
        </div>
    </div>
</template>

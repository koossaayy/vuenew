<script setup lang="ts">
import { usePage } from '@inertiajs/vue3';
import { CheckCircle, XCircle, X } from 'lucide-vue-next';
import { computed, ref, watch } from 'vue';

type FlashMessages = {
    success?: string | null;
    error?: string | null;
};

const page = usePage<{ flash: FlashMessages }>();
const flash = computed(() => page.props.flash ?? {});

const visible = ref(false);
const currentMessage = ref('');
const currentType = ref<'success' | 'error'>('success');

let timeout: ReturnType<typeof setTimeout>;

function autoDismiss() {
    clearTimeout(timeout);
    timeout = setTimeout(() => {
        visible.value = false;
    }, 5000);
}

function dismiss() {
    visible.value = false;
    clearTimeout(timeout);
}

watch(
    flash,
    (val) => {
        if (val.success) {
            currentMessage.value = val.success;
            currentType.value = 'success';
            visible.value = true;
            autoDismiss();
        } else if (val.error) {
            currentMessage.value = val.error;
            currentType.value = 'error';
            visible.value = true;
            autoDismiss();
        }
    },
    { immediate: true },
);


</script>

<template>
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="translate-y-2 opacity-0"
        enter-to-class="translate-y-0 opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="translate-y-0 opacity-100"
        leave-to-class="translate-y-2 opacity-0"
    >
        <div
            v-if="visible"
            class="fixed bottom-4 right-4 z-50 flex max-w-sm items-center gap-3 rounded-lg border px-4 py-3 shadow-lg"
            :class="{
                'border-green-200 bg-green-50 text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-200':
                    currentType === 'success',
                'border-red-200 bg-red-50 text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200':
                    currentType === 'error',
            }"
        >
            <CheckCircle
                v-if="currentType === 'success'"
                class="size-5 shrink-0"
            />
            <XCircle
                v-else
                class="size-5 shrink-0"
            />
            <span class="text-sm font-medium">{{ currentMessage }}</span>
            <button
                class="ml-2 shrink-0 opacity-70 hover:opacity-100"
                @click="dismiss"
            >
                <X class="size-4" />
            </button>
        </div>
    </Transition>
</template>

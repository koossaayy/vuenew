<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Tags', href: '/tags' },
    { title: 'Create Tag', href: '/tags/create' },
];

const form = useForm({
    name: '',
});

const localError = ref('');

function submit() {
    if (!form.name.trim()) {
        localError.value = 'The tag name field is required.';
        return;
    }

    localError.value = '';
    form.post('/tags', {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head title="Create Tag" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="mx-auto w-full max-w-2xl">
                <h1 class="text-2xl font-bold">Create Tag</h1>
                <p class="text-muted-foreground mt-1 text-sm">
                    Add a new tag to label your articles.
                </p>

                <form class="mt-6 space-y-6" @submit.prevent="submit">
                    <div
                        v-if="localError"
                        class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200"
                    >
                        {{ localError }}
                    </div>

                    <div class="space-y-2">
                        <Label for="name">Name</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            placeholder="e.g. Laravel, Vue.js, PHP"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button type="submit" :disabled="form.processing">
                            Create Tag
                        </Button>
                        <Button variant="outline" as-child>
                            <Link href="/tags">Cancel</Link>
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

<script setup lang="ts">
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import { useI18n } from 'vue-i18n';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const { t } = useI18n();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Dashboard'), href: '/dashboard' },
    { title: t('Categories'), href: '/categories' },
    { title: t('Create Category'), href: '/categories/create' },
];

const form = useForm({
    name: '',
});

const localError = ref('');

function submit() {
    if (!form.name.trim()) {
        localError.value = t('Please enter a category name before submitting.');
        return;
    }

    localError.value = '';
    form.post('/categories', {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head :title="$t('Create Category')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="mx-auto w-full max-w-2xl">
                <h1 class="text-2xl font-bold">{{ $t('Create Category') }}</h1>
                <p class="text-muted-foreground mt-1 text-sm"> {{ $t('Add a new category to organize your articles.') }} </p>

                <form class="mt-6 space-y-6" @submit.prevent="submit">
                    <div
                        v-if="localError"
                        class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200"
                    >
                        {{ localError }}
                    </div>

                    <div class="space-y-2">
                        <Label for="name">{{ $t('Name') }}</Label>
                        <Input
                            id="name"
                            v-model="form.name"
                            :placeholder="$t('e.g. Technology, Science, Sports')"
                        />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button type="submit" :disabled="form.processing"> {{ $t('Create Category') }} </Button>
                        <Button variant="outline" as-child>
                            <Link href="/categories">{{ $t('Cancel') }}</Link>
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

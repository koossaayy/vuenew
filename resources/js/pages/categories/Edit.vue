<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const { t } = useI18n();

type Category = {
    id: number;
    name: string;
    slug: string;
};

const props = defineProps<{
    category: Category;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Dashboard'), href: '/dashboard' },
    { title: t('Categories'), href: '/categories' },
    { title: t('Edit Category'), href: `/categories/${props.category.id}/edit` },
];

const form = useForm({
    name: props.category.name,
});

const localSuccess = ref('');

function submit() {
    form.put(`/categories/${props.category.id}`, {
        preserveScroll: true,
        onSuccess: () => {
            localSuccess.value = t('Changes saved! Redirecting to categories list...');
        },
    });
}
</script>

<template>
    <Head :title="$t('Edit Category')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="mx-auto w-full max-w-2xl">
                <h1 class="text-2xl font-bold">{{ $t('Edit Category') }}</h1>
                <p class="text-muted-foreground mt-1 text-sm"> {{ $t('Update the category details below.') }} </p>

                <form class="mt-6 space-y-6" @submit.prevent="submit">
                    <div
                        v-if="localSuccess"
                        class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800 dark:border-green-800 dark:bg-green-950 dark:text-green-200"
                    >
                        {{ localSuccess }}
                    </div>

                    <div class="space-y-2">
                        <Label for="name">{{ $t('Name') }}</Label>
                        <Input id="name" v-model="form.name" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button type="submit" :disabled="form.processing"> {{ $t('Update Category') }} </Button>
                        <Button variant="outline" as-child>
                            <Link href="/categories">{{ $t('Cancel') }}</Link>
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

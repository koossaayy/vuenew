<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { Head, Link, useForm } from '@inertiajs/vue3';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const { t } = useI18n();

type Tag = {
    id: number;
    name: string;
    slug: string;
};

const props = defineProps<{
    tag: Tag;
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Dashboard'), href: '/dashboard' },
    { title: t('Tags'), href: '/tags' },
    { title: t('Edit Tag'), href: `/tags/${props.tag.id}/edit` },
];

const form = useForm({
    name: props.tag.name,
});

function submit() {
    form.put(`/tags/${props.tag.id}`, {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head :title="$t('Edit Tag')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="mx-auto w-full max-w-2xl">
                <h1 class="text-2xl font-bold">{{ $t('Edit Tag') }}</h1>
                <p class="text-muted-foreground mt-1 text-sm"> {{ $t('Update the tag details below.') }} </p>

                <form class="mt-6 space-y-6" @submit.prevent="submit">
                    <div class="space-y-2">
                        <Label for="name">{{ $t('Name') }}</Label>
                        <Input id="name" v-model="form.name" />
                        <InputError :message="form.errors.name" />
                    </div>

                    <div class="flex items-center gap-4">
                        <Button type="submit" :disabled="form.processing"> {{ $t('Update Tag') }} </Button>
                        <Button variant="outline" as-child>
                            <Link href="/tags">{{ $t('Cancel') }}</Link>
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

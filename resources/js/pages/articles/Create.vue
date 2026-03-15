<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
import InputError from '@/components/InputError.vue';
import { Button } from '@/components/ui/button';
import { Checkbox } from '@/components/ui/checkbox';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const { t } = useI18n();

type Category = {
    id: number;
    name: string;
};

type Tag = {
    id: number;
    name: string;
};

defineProps<{
    categories: Category[];
    tags: Tag[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Dashboard'), href: '/dashboard' },
    { title: t('Articles'), href: '/articles' },
    { title: t('New Article'), href: '/articles/create' },
];

const form = useForm({
    title: '',
    body: '',
    category_id: '',
    tags: [] as number[],
    is_published: false,
});

const localError = ref('');

function toggleTag(tagId: number) {
    const index = form.tags.indexOf(tagId);
    if (index === -1) {
        form.tags.push(tagId);
    } else {
        form.tags.splice(index, 1);
    }
}

function submit() {
    if (!form.title.trim() || !form.body.trim() || !form.category_id) {
        localError.value =
            t('Please fill in all required fields (title, body, and category).');
        return;
    }

    localError.value = '';
    form.post('/articles', {
        preserveScroll: true,
    });
}
</script>

<template>
    <Head :title="$t('New Article')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="mx-auto w-full max-w-2xl">
                <h1 class="text-2xl font-bold">{{ $t('New Article') }}</h1>
                <p class="text-muted-foreground mt-1 text-sm"> {{ $t('Write a new article and assign it to a category.') }} </p>

                <form class="mt-6 space-y-6" @submit.prevent="submit">
                    <div
                        v-if="localError"
                        class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200"
                    >
                        {{ localError }}
                    </div>

                    <div class="space-y-2">
                        <Label for="title">{{ $t('Title') }}</Label>
                        <Input
                            id="title"
                            v-model="form.title"
                            :placeholder="$t('Enter article title')"
                        />
                        <InputError :message="form.errors.title" />
                    </div>

                    <div class="space-y-2">
                        <Label for="body">{{ $t('Body') }}</Label>
                        <textarea
                            id="body"
                            v-model="form.body"
                            rows="8"
                            :placeholder="$t('Write your article content here...')"
                            class="border-input bg-background ring-offset-background placeholder:text-muted-foreground focus-visible:ring-ring flex w-full rounded-md border px-3 py-2 text-sm focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:outline-none disabled:cursor-not-allowed disabled:opacity-50 dark:bg-input/30"
                        />
                        <InputError :message="form.errors.body" />
                    </div>

                    <div class="space-y-2">
                        <Label>{{ $t('Category') }}</Label>
                        <Select v-model="form.category_id">
                            <SelectTrigger class="w-full">
                                <SelectValue
                                    :placeholder="$t('Select a category')"
                                />
                            </SelectTrigger>
                            <SelectContent>
                                <SelectItem
                                    v-for="category in categories"
                                    :key="category.id"
                                    :value="String(category.id)"
                                >
                                    {{ category.name }}
                                </SelectItem>
                            </SelectContent>
                        </Select>
                        <InputError :message="form.errors.category_id" />
                        <p
                            v-if="categories.length === 0"
                            class="text-muted-foreground text-sm"
                        > {{ $t('No categories available.') }} <Link
                                href="/categories/create"
                                class="text-primary underline"
                            > {{ $t('Create one first.') }} </Link>
                        </p>
                    </div>

                    <div class="space-y-2">
                        <Label>{{ $t('Tags') }}</Label>
                        <div
                            v-if="tags.length > 0"
                            class="flex flex-wrap gap-3"
                        >
                            <label
                                v-for="tag in tags"
                                :key="tag.id"
                                class="flex cursor-pointer items-center gap-2"
                            >
                                <Checkbox
                                    :model-value="form.tags.includes(tag.id)"
                                    @update:model-value="toggleTag(tag.id)"
                                />
                                <span class="text-sm">{{ tag.name }}</span>
                            </label>
                        </div>
                        <p
                            v-else
                            class="text-muted-foreground text-sm"
                        > {{ $t('No tags available.') }} <Link
                                href="/tags/create"
                                class="text-primary underline"
                            > {{ $t('Create some first.') }} </Link>
                        </p>
                    </div>

                    <div class="flex items-center gap-2">
                        <Checkbox
                            id="is_published"
                            :model-value="form.is_published"
                            @update:model-value="
                                (val: boolean | 'indeterminate') =>
                                    (form.is_published = val === true)
                            "
                        />
                        <Label for="is_published" class="cursor-pointer"> {{ $t('Publish immediately') }} </Label>
                    </div>

                    <div class="flex items-center gap-4">
                        <Button type="submit" :disabled="form.processing"> {{ $t('Create Article') }} </Button>
                        <Button variant="outline" as-child>
                            <Link href="/articles">{{ $t('Cancel') }}</Link>
                        </Button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>

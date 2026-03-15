<script setup lang="ts">
import { useI18n } from 'vue-i18n';
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
import { Badge } from '@/components/ui/badge';
import { Button } from '@/components/ui/button';
import {
    Dialog,
    DialogContent,
    DialogDescription,
    DialogFooter,
    DialogHeader,
    DialogTitle,
} from '@/components/ui/dialog';
import AppLayout from '@/layouts/AppLayout.vue';
import type { BreadcrumbItem } from '@/types';

const { t } = useI18n();

type Tag = {
    id: number;
    name: string;
};

type Category = {
    id: number;
    name: string;
};

type Article = {
    id: number;
    title: string;
    slug: string;
    body: string;
    is_published: boolean;
    category: Category;
    tags: Tag[];
    created_at: string;
};

defineProps<{
    articles: Article[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: t('Dashboard'), href: '/dashboard' },
    { title: t('Articles'), href: '/articles' },
];

const confirmingDelete = ref(false);
const articleToDelete = ref<Article | null>(null);

function confirmDelete(article: Article) {
    articleToDelete.value = article;
    confirmingDelete.value = true;
}

function deleteArticle() {
    if (articleToDelete.value) {
        router.delete(`/articles/${articleToDelete.value.id}`, {
            onFinish: () => {
                confirmingDelete.value = false;
                articleToDelete.value = null;
            },
        });
    }
}
</script>

<template>
    <Head :title="$t('Articles')" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">{{ $t('Articles') }}</h1>
                <Button as-child>
                    <Link href="/articles/create">
                        <Plus class="size-4" /> {{ $t('New Article') }} </Link>
                </Button>
            </div>

            <div
                v-if="articles.length === 0"
                class="flex flex-1 items-center justify-center rounded-xl border border-dashed p-12"
            >
                <div class="text-center">
                    <h3 class="text-lg font-medium">{{ $t('No articles yet') }}</h3>
                    <p class="text-muted-foreground mt-1 text-sm"> {{ $t('Get started by creating your first article.') }} </p>
                    <Button as-child class="mt-4">
                        <Link href="/articles/create">
                            <Plus class="size-4" /> {{ $t('Write Article') }} </Link>
                    </Button>
                </div>
            </div>

            <div v-else class="rounded-xl border">
                <table class="w-full">
                    <thead>
                        <tr class="border-b text-left text-sm">
                            <th class="px-4 py-3 font-medium">{{ $t('Title') }}</th>
                            <th class="px-4 py-3 font-medium">{{ $t('Category') }}</th>
                            <th class="px-4 py-3 font-medium">{{ $t('Tags') }}</th>
                            <th class="px-4 py-3 font-medium">{{ $t('Status') }}</th>
                            <th class="px-4 py-3 text-right font-medium"> {{ $t('Actions') }} </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="article in articles"
                            :key="article.id"
                            class="border-b last:border-0"
                        >
                            <td class="px-4 py-3">
                                <div>
                                    <p class="font-medium">
                                        {{ article.title }}
                                    </p>
                                    <p
                                        class="text-muted-foreground mt-0.5 text-xs"
                                    >
                                        {{ article.slug }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <Badge variant="outline">
                                    {{ article.category.name }}
                                </Badge>
                            </td>
                            <td class="px-4 py-3">
                                <div class="flex flex-wrap gap-1">
                                    <Badge
                                        v-for="tag in article.tags"
                                        :key="tag.id"
                                        variant="secondary"
                                    >
                                        {{ tag.name }}
                                    </Badge>
                                    <span
                                        v-if="article.tags.length === 0"
                                        class="text-muted-foreground text-sm"
                                    > {{ $t('No tags') }} </span>
                                </div>
                            </td>
                            <td class="px-4 py-3">
                                <Badge
                                    :variant="
                                        article.is_published
                                            ? 'default'
                                            : 'secondary'
                                    "
                                >
                                    {{
                                        article.is_published
                                            ? $t('Published')
                                            : $t('Draft')
                                    }}
                                </Badge>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div
                                    class="flex items-center justify-end gap-2"
                                >
                                    <Button
                                        variant="ghost"
                                        size="icon-sm"
                                        as-child
                                    >
                                        <Link
                                            :href="`/articles/${article.id}/edit`"
                                        >
                                            <Pencil class="size-4" />
                                        </Link>
                                    </Button>
                                    <Button
                                        variant="ghost"
                                        size="icon-sm"
                                        @click="confirmDelete(article)"
                                    >
                                        <Trash2
                                            class="text-destructive size-4"
                                        />
                                    </Button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <Dialog v-model:open="confirmingDelete">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>{{ $t('Delete Article') }}</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete
                        "{{ articleToDelete?.title }}"? This action cannot be
                        undone.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button
                        variant="outline"
                        @click="confirmingDelete = false"
                    > {{ $t('Cancel') }} </Button>
                    <Button variant="destructive" @click="deleteArticle"> {{ $t('Delete') }} </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>

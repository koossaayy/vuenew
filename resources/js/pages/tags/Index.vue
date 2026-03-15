<script setup lang="ts">
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

type Tag = {
    id: number;
    name: string;
    slug: string;
    articles_count: number;
};

defineProps<{
    tags: Tag[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Tags', href: '/tags' },
];

const confirmingDelete = ref(false);
const tagToDelete = ref<Tag | null>(null);
const deleteError = ref('');

function confirmDelete(tag: Tag) {
    tagToDelete.value = tag;
    deleteError.value = '';
    confirmingDelete.value = true;
}

function deleteTag() {
    if (tagToDelete.value) {
        router.delete(`/tags/${tagToDelete.value.id}`, {
            preserveScroll: true,
            onSuccess: () => {
                confirmingDelete.value = false;
                tagToDelete.value = null;
            },
            onError: () => {
                deleteError.value =
                    'Something went wrong while deleting the tag. Please try again.';
            },
        });
    }
}
</script>

<template>
    <Head title="Tags" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Tags</h1>
                <Button as-child>
                    <Link href="/tags/create">
                        <Plus class="size-4" />
                        Add Tag
                    </Link>
                </Button>
            </div>

            <div
                v-if="tags.length === 0"
                class="flex flex-1 items-center justify-center rounded-xl border border-dashed p-12"
            >
                <div class="text-center">
                    <h3 class="text-lg font-medium">No tags yet</h3>
                    <p class="text-muted-foreground mt-1 text-sm">
                        Get started by creating your first tag.
                    </p>
                    <Button as-child class="mt-4">
                        <Link href="/tags/create">
                            <Plus class="size-4" />
                            Create Tag
                        </Link>
                    </Button>
                </div>
            </div>

            <div v-else class="flex flex-wrap gap-3">
                <div
                    v-for="tag in tags"
                    :key="tag.id"
                    class="flex items-center gap-2 rounded-lg border px-4 py-3"
                >
                    <Badge variant="secondary">{{ tag.name }}</Badge>
                    <span class="text-muted-foreground text-xs">
                        {{ tag.articles_count }}
                        {{ tag.articles_count === 1 ? 'article' : 'articles' }}
                    </span>
                    <div class="ml-2 flex gap-1">
                        <Button
                            variant="ghost"
                            size="icon-sm"
                            as-child
                        >
                            <Link :href="`/tags/${tag.id}/edit`">
                                <Pencil class="size-3" />
                            </Link>
                        </Button>
                        <Button
                            variant="ghost"
                            size="icon-sm"
                            @click="confirmDelete(tag)"
                        >
                            <Trash2 class="text-destructive size-3" />
                        </Button>
                    </div>
                </div>
            </div>
        </div>

        <Dialog v-model:open="confirmingDelete">
            <DialogContent>
                <DialogHeader>
                    <DialogTitle>Delete Tag</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete the tag
                        "{{ tagToDelete?.name }}"? This action cannot be undone.
                    </DialogDescription>
                </DialogHeader>
                <div
                    v-if="deleteError"
                    class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800 dark:border-red-800 dark:bg-red-950 dark:text-red-200"
                >
                    {{ deleteError }}
                </div>
                <DialogFooter>
                    <Button
                        variant="outline"
                        @click="confirmingDelete = false"
                    >
                        Cancel
                    </Button>
                    <Button variant="destructive" @click="deleteTag">
                        Delete
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>

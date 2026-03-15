<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3';
import { Pencil, Plus, Trash2 } from 'lucide-vue-next';
import { ref } from 'vue';
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

type Category = {
    id: number;
    name: string;
    slug: string;
    articles_count: number;
};

defineProps<{
    categories: Category[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: '/dashboard' },
    { title: 'Categories', href: '/categories' },
];

const confirmingDelete = ref(false);
const categoryToDelete = ref<Category | null>(null);

function confirmDelete(category: Category) {
    categoryToDelete.value = category;
    confirmingDelete.value = true;
}

function deleteCategory() {
    if (categoryToDelete.value) {
        router.delete(`/categories/${categoryToDelete.value.id}`, {
            onFinish: () => {
                confirmingDelete.value = false;
                categoryToDelete.value = null;
            },
        });
    }
}
</script>

<template>
    <Head title="Categories" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Categories</h1>
                <Button as-child>
                    <Link href="/categories/create">
                        <Plus class="size-4" />
                        Add Category
                    </Link>
                </Button>
            </div>

            <div
                v-if="categories.length === 0"
                class="flex flex-1 items-center justify-center rounded-xl border border-dashed p-12"
            >
                <div class="text-center">
                    <h3 class="text-lg font-medium">No categories yet</h3>
                    <p class="text-muted-foreground mt-1 text-sm">
                        Get started by creating your first category.
                    </p>
                    <Button as-child class="mt-4">
                        <Link href="/categories/create">
                            <Plus class="size-4" />
                            Create Category
                        </Link>
                    </Button>
                </div>
            </div>

            <div v-else class="rounded-xl border">
                <table class="w-full">
                    <thead>
                        <tr class="border-b text-left text-sm">
                            <th class="px-4 py-3 font-medium">Name</th>
                            <th class="px-4 py-3 font-medium">Slug</th>
                            <th class="px-4 py-3 font-medium">Articles</th>
                            <th class="px-4 py-3 text-right font-medium">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="category in categories"
                            :key="category.id"
                            class="border-b last:border-0"
                        >
                            <td class="px-4 py-3 font-medium">
                                {{ category.name }}
                            </td>
                            <td
                                class="text-muted-foreground px-4 py-3 text-sm"
                            >
                                {{ category.slug }}
                            </td>
                            <td class="px-4 py-3">
                                {{ category.articles_count }}
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
                                            :href="`/categories/${category.id}/edit`"
                                        >
                                            <Pencil class="size-4" />
                                        </Link>
                                    </Button>
                                    <Button
                                        variant="ghost"
                                        size="icon-sm"
                                        @click="confirmDelete(category)"
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
                    <DialogTitle>Delete Category</DialogTitle>
                    <DialogDescription>
                        Are you sure you want to delete the category
                        "{{ categoryToDelete?.name }}"? This action cannot be
                        undone.
                    </DialogDescription>
                </DialogHeader>
                <DialogFooter>
                    <Button
                        variant="outline"
                        @click="confirmingDelete = false"
                    >
                        Cancel
                    </Button>
                    <Button variant="destructive" @click="deleteCategory">
                        Delete
                    </Button>
                </DialogFooter>
            </DialogContent>
        </Dialog>
    </AppLayout>
</template>

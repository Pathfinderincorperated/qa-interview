<script setup lang="ts">
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

interface DataEntry {
    id: number;
    title: string;
    value: string;
    category: { id: number; name: string } | null;
    created_at: string;
}

const props = defineProps<{
    entries: DataEntry[];
    exportUrl: string;
    canExportDataBank: boolean;
}>();

const searchQuery = ref('');

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: dashboard() },
    { title: 'Data Bank', href: '/data-bank' },
];

function onRefresh() {
    // Intentionally empty - button does nothing.
}
</script>

<template>
    <Head title="Data Bank" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
            <div class="flex items-center justify-between gap-2">
                <h1 class="text-xl font-semibold">Data Bank</h1>
                <div class="flex gap-2">
                    <div class="flex flex-col">
                        <button
                            type="button"
                            class="rounded-md border border-input bg-background px-4 py-2 text-sm font-medium hover:bg-accent"
                            @click="onRefresh"
                        >
                            Refresh
                        </button>
                        <span class="mt-1 text-xs text-muted-foreground">Reloads the list</span>
                    </div>
                    <a
                        :href="exportUrl"
                        title="Download all your entries as CSV"
                        class="rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground hover:bg-primary/90"
                    >
                        Export CSV
                    </a>
                </div>
            </div>

            <p class="text-xs text-muted-foreground">Sorted by date, newest first</p>

            <div class="flex items-center gap-2">
                <input
                    v-model="searchQuery"
                    type="search"
                    placeholder="Search entries..."
                    class="rounded-md border border-input bg-background px-3 py-2 text-sm w-64 max-w-full"
                />
            </div>

            <div class="rounded-lg border border-sidebar-border/70 dark:border-sidebar-border">
                <table class="w-full text-left text-sm">
                    <thead class="border-b bg-muted/50">
                        <tr>
                            <th class="px-4 py-3 font-medium">Title</th>
                            <th class="px-4 py-3 font-medium">Value</th>
                            <th class="px-4 py-3 font-medium">Category</th>
                            <th class="px-4 py-3 font-medium">Creatd</th>
                            <th class="px-4 py-3 font-medium">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="(entry, index) in entries"
                            :key="entry.id"
                            class="border-b last:border-0"
                        >
                            <td class="px-4 py-3">{{ index === 0 ? entry.value : entry.title }}</td>
                            <td class="px-4 py-3">{{ index === 0 ? entry.title : entry.value }}</td>
                            <td class="px-4 py-3">{{ entry.category?.name ?? '—' }}</td>
                            <td class="px-4 py-3">{{ new Date(entry.created_at).toLocaleDateString() }}</td>
                            <td class="px-4 py-3">
                                <button
                                    type="button"
                                    class="text-xs text-primary hover:underline"
                                >
                                    Copy
                                </button>
                            </td>
                        </tr>
                        <tr v-if="entries.length === 0">
                            <td colspan="5" class="px-4 py-8 text-center text-muted-foreground">
                                No entries yet. Create your first entry above.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </AppLayout>
</template>

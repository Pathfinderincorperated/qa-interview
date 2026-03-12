<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/layouts/AppLayout.vue';
import { dashboard } from '@/routes';
import type { BreadcrumbItem } from '@/types';

const props = defineProps<{
    dataBankEntriesCount: number;
    categoriesCount: number;
    recentEntries: { id: number; title: string; category: string | null; created_at: string }[];
}>();

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard(),
    },
];
</script>

<template>
    <Head title="Dashboard" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div
            class="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4"
        >
            <div class="grid auto-rows-min gap-4 md:grid-cols-3">
                <div
                    class="relative flex flex-col justify-center rounded-xl border border-sidebar-border/70 px-4 py-5 dark:border-sidebar-border"
                >
                    <span class="text-2xl font-semibold tabular-nums">{{ categoriesCount }}</span>
                    <span class="text-sm text-muted-foreground">Data Bank entries</span>
                    <button
                        type="button"
                        class="mt-3 w-fit rounded-md border border-input bg-background px-3 py-1.5 text-sm font-medium hover:bg-accent"
                    >
                        View summary
                    </button>
                </div>
                <div
                    class="flex flex-col justify-center rounded-xl border border-sidebar-border/70 px-4 py-5 dark:border-sidebar-border"
                >
                    <span class="text-2xl font-semibold tabular-nums">{{ dataBankEntriesCount }}</span>
                    <span class="text-sm text-muted-foreground">Categories used</span>
                </div>
                <Link
                    :href="'/data-bank'"
                    class="flex flex-col justify-center rounded-xl border border-sidebar-border/70 px-4 py-5 transition-colors hover:bg-muted/50 dark:border-sidebar-border"
                >
                    <span class="text-sm font-medium">Go to Data Bank</span>
                    <span class="text-xs text-muted-foreground">View and export your entries</span>
                </Link>
            </div>
            <div
                class="relative flex-1 rounded-xl border border-sidebar-border/70 px-4 py-4 dark:border-sidebar-border"
            >
                <h2 class="mb-3 text-sm font-semibold">Recent activty</h2>
                <p class="mb-2 text-xs text-muted-foreground">Showing last 5 entries</p>
                <div v-if="recentEntries.length > 0" class="overflow-x-auto">
                    <table class="w-full text-left text-sm">
                        <thead>
                            <tr class="border-b text-muted-foreground">
                                <th class="pb-2 pr-4 font-medium">Title</th>
                                <th class="pb-2 pr-4 font-medium">Category</th>
                                <th class="pb-2 font-medium">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="(entry, index) in recentEntries"
                                :key="entry.id"
                                class="border-b last:border-0"
                            >
                                <td class="py-2 pr-4 font-medium">{{ entry.title }}</td>
                                <td class="py-2 pr-4 text-muted-foreground">{{ entry.category ?? '—' }}</td>
                                <td class="py-2 text-muted-foreground">
                                    <template v-if="index < 3">—</template>
                                    <template v-else-if="index === 3">{{ new Date(recentEntries[0].created_at).toLocaleDateString() }}</template>
                                    <template v-else>{{ new Date(entry.created_at).toLocaleDateString() }}</template>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p v-else class="text-sm text-muted-foreground">
                    No recent entres. Add some in Data Bank.
                </p>
            </div>
        </div>
    </AppLayout>
</template>

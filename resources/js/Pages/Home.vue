<script setup>
import { Head, Link } from "@inertiajs/inertia-vue3";

defineProps({
    response: Object,
});
</script>

<template>
    <Head title="Log Viewer" />

    <div class="h-screen px-16 py-16 dark:bg-gray-900">
        <h1 class="text-3xl font-bold text-center text-white">
            Log Viewer App
        </h1>
        <div
            class="mt-8 overflow-hidden bg-white shadow dark:bg-gray-800 sm:rounded-lg"
        >
            <div
                class="flex px-6 py-4 space-x-4 rounded-lg shadow-lg justifiy-between"
            >
                <select
                    v-model="form.file"
                    class="w-full px-6 py-4 text-white bg-gray-600 rounded-lg shadow-sm"
                >
                    <option value="">Please Select Log File To View</option>
                    <option
                        v-for="(item, key) in response"
                        :key="key"
                        :value="item.path"
                    >
                        {{ item.name }}
                    </option>
                </select>
                <button
                    @click.prevent="loadData()"
                    class="w-40 py-4 text-white bg-gray-600 rounded-lg shadow-sm focus:ring"
                >
                    View Log
                </button>
            </div>
        </div>
        <div
            class="my-4 overflow-hidden bg-white shadow dark:bg-gray-800 sm:rounded-lg"
        >
            <table class="text-white">
                <thead class="w-full bg-gray-600">
                    <tr>
                        <th class="px-4 py-4">#</th>
                        <th class="w-full px-4 py-4">Log</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(item, key) in collection" :key="key">
                        <td class="px-4 py-4">{{ parseInt(key) + 1 }}</td>
                        <td class="w-full px-4 py-4">{{ item }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div
            class="flex justify-center my-4 overflow-hidden text-white bg-white shadow dark:bg-gray-800 sm:rounded-lg"
        >
            <button
                @click="loadPage(1)"
                class="w-full px-6 py-4 m-4 border rounded-lg"
                :class="{ 'text-gray-800': page === 1 }"
                :disabled="page === 1"
            >
                First
            </button>
            <button
                @click="loadPage(back)"
                class="w-full px-6 py-4 m-4 border rounded-lg"
                :class="{ 'text-gray-800': page === 1 }"
                :disabled="page === 1"
            >
                Back
            </button>
            <button
                @click="loadPage(next)"
                class="w-full px-6 py-4 m-4 border rounded-lg"
                :class="{ 'text-gray-800': page === last }"
                :disabled="page === last"
            >
                Next
            </button>
            <button
                @click="loadPage(last)"
                class="w-full px-6 py-4 m-4 border rounded-lg"
                :class="{ 'text-gray-800': page === last }"
                :disabled="page === last"
            >
                Last
            </button>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            form: {
                file: "",
                page: 1,
            },
            page: 1,
            next: 0,
            back: 0,
            last: 0,
            collection: [],
        };
    },
    methods: {
        loadData() {
            //Check if file is selected!
            if (this.form.file) {
                //Send Axios Request To Fatch data from backend
                axios.post("log", this.form).then((data) => {
                    this.collection = data.data.body.lines;
                    this.page = data.data.body.page;
                    this.next = this.page + 1;
                    this.back = this.page - 1;
                    this.last = data.data.body.last;
                });
            }
        },
        loadPage(page) {
            //Change page
            this.form.page = page;

            //Check if file is selected!
            if (this.form.file) {
                //Send Axios Request To Fatch data from backend
                axios.post("log", this.form).then((data) => {
                    this.collection = data.data.body.lines;
                    this.page = data.data.body.page;
                    this.next = this.page + 1;
                    this.back = this.page - 1;
                    this.last = data.data.body.last;
                });
            }
        },
    },
};
</script>

<style scoped>
.bg-gray-100 {
    background-color: #f7fafc;
    background-color: rgba(247, 250, 252, var(--tw-bg-opacity));
}

.border-gray-200 {
    border-color: #edf2f7;
    border-color: rgba(237, 242, 247, var(--tw-border-opacity));
}

.text-gray-400 {
    color: #cbd5e0;
    color: rgba(203, 213, 224, var(--tw-text-opacity));
}

.text-gray-500 {
    color: #a0aec0;
    color: rgba(160, 174, 192, var(--tw-text-opacity));
}

.text-gray-600 {
    color: #718096;
    color: rgba(113, 128, 150, var(--tw-text-opacity));
}

.text-gray-700 {
    color: #4a5568;
    color: rgba(74, 85, 104, var(--tw-text-opacity));
}

.text-gray-900 {
    color: #1a202c;
    color: rgba(26, 32, 44, var(--tw-text-opacity));
}

@media (prefers-color-scheme: dark) {
    .dark\:bg-gray-800 {
        background-color: #2d3748;
        background-color: rgba(45, 55, 72, var(--tw-bg-opacity));
    }

    .dark\:bg-gray-900 {
        background-color: #1a202c;
        background-color: rgba(26, 32, 44, var(--tw-bg-opacity));
    }

    .dark\:border-gray-700 {
        border-color: #4a5568;
        border-color: rgba(74, 85, 104, var(--tw-border-opacity));
    }

    .dark\:text-white {
        color: #fff;
        color: rgba(255, 255, 255, var(--tw-text-opacity));
    }

    .dark\:text-gray-400 {
        color: #cbd5e0;
        color: rgba(203, 213, 224, var(--tw-text-opacity));
    }
}
</style>

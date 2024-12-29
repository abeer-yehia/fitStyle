<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import pickBy from "lodash/pickBy";
import { deleteItem } from '@/utils';

const props = defineProps({
  categories: Array,
});

const selectedCategory = ref({});
const filters = ref({
  sortBy: "",
});

const selectCategory = (category) => {
  selectedCategory.value = category;
};

const resetSelectCategory = () => {
  selectedCategory.value = {};
};

watch(filters.value, (value) => {
  Inertia.get(route("categories.index"), pickBy(value), {
    preserveState: true,
    replace: true,
  });
});

const productImage = (product) => {
  let preview = product.images.find((image) => {
    return image.is_preview;
  });

  return "/images/" + preview.url;
};

const deleteCategory = (categoryId, event) => {
  deleteItem(categoryId, 'categories.destroy', event);
};



</script>

<template>
  <DashboardLayout>
    <Head title="Categories" />

    <header class="flex items-center justify-between mb-8">
      <h2 class="font-medium uppercase text-2xl">Categories</h2>
      <Link :href="route('categories.create')" class="text-c-green-500 underline"
        >Add new Category</Link
      >
    </header>

    <table class="w-full">
      <tr class="w-full bg-white border-b">
        <th class="text-left font-medium text-xs px-4 py-3 w-16">ID</th>
        <th class="text-left font-medium text-xs px-4 py-3">Name</th>
        <th class="text-center font-medium text-xs px-4 py-3">Items</th>
        <th></th>
      </tr>
      <tr
        v-for="category in categories"
        :key="category.id"
        class="even:bg-zinc-100 odd:bg-white text-sm border-b"
      >
        <td class="px-4 py-3 text-left">
          <span class="text-xs font-medium">#{{ category.id }}</span>
        </td>
        <td class="px-4 py-3 text-left">
          <span class="text-xs font-medium">{{ category.name }}</span>
        </td>

        <td class="px-4 py-3 text-center">
          <span>{{ category.products.length }}</span>
        </td>
        <td class="flex justify-evenly mr-1 space-x-2">
          <svg
            @click="selectCategory(category)"
            class="w-5 h-5 text-zinc-500 hover:text-zinc-700 cursor-pointer"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"
            ></path>
          </svg>
          <Link
            :href="route('categories.edit', category.id)"
            class="text-c-green-500 underline"
            >Edit</Link
          >

          <button @click="deleteCategory(category.id, $event)" class="text-fuchsia-900 underline">
            Delete
          </button>
          
        </td>
      </tr>
    </table>

    <div
      class="bg-white w-80 fixed p-4 right-0 top-0 bottom-0 transform transition duration-150 ease-linear z-20"
      :class="{ 'translate-x-full': Object.keys(selectedCategory).length === 0 }"
    >
      <header class="mb-6">
        <span class="font-medium block">Category number: {{ selectedCategory.id }}</span>
      </header>

      <div class="bg-zinc-100 p-4 mb-4 rounded">
        <span class="block text-sm font-medium mb-4">Category details</span>
        <div
          v-for="(product, index) in selectedCategory.products"
          :key="index"
          class="bg-zinc-100 rounded pb-4 flex items-start space-x-3"
        >
          <div class="w-16 h-20 flex-none">
            <img :src="productImage(product)" alt="" class="w-full h-full object-cover" />
          </div>

          <div>
            <span class="block font-medium text-sm">{{ product.name }}</span>
            <span class="block text-zinc-500 text-sm"
              >x{{ product.pivot?.quantity }}</span
            >
            <span class="block text-zinc-500 text-sm"
              >€{{ product.pivot?.unit_price }}</span
            >
          </div>
        </div>
      </div>
    </div>

    <div
      v-if="Object.keys(selectedCategory).length > 0"
      @click="resetSelectCategory()"
      class="bg-black fixed inset-0 opacity-10 z-10"
    ></div>
  </DashboardLayout>
</template>

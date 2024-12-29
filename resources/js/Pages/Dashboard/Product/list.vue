<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { onMounted, ref, watch } from "vue";
import { Inertia } from "@inertiajs/inertia";
import pickBy from "lodash/pickBy";
import { deleteItem } from "@/utils";

const props = defineProps({
  products: Array,
});

const selectedProduct = ref([
  category=> [],
  section=> []
]);
const filters = ref({
  sortBy: "",
});

const selectProduct = (product) => {
  selectedProduct.value = product;
};

const resetselectProduct = () => {
  selectedProduct.value = {};
};

watch(filters.value, (value) => {
  Inertia.get(route("products.index"), pickBy(value), {
    preserveState: true,
    replace: true,
  });
});
</script>

<template>
  <DashboardLayout>
    <Head title="Products" />
    <header class="flex items-center justify-between mb-8">
      <h2 class="font-medium uppercase text-2xl">Products</h2>

      <Link :href="route('products.create')" class="text-c-green-500 underline"
        >Add new Product</Link
      >
    </header>

    <table class="w-full">
      <tr class="w-full bg-white border-b">
        <th class="text-left font-medium text-xs px-4 py-3 w-16">ID</th>
        <th class="text-left font-medium text-xs px-4 py-3">Name</th>
        <th></th>
      </tr>
      <tr
        v-for="product in products"
        :key="product.id"
        class="even:bg-zinc-100 odd:bg-white text-sm border-b"
      >
        <td class="px-4 py-3 text-left">
          <span class="text-xs font-medium">#{{ product.id }}</span>
        </td>
        <td class="px-4 py-3 text-left">
          <span class="text-xs font-medium">{{ product.name }}</span>
        </td>

        <td class="flex justify-evenly mr-1 space-x-2">
          <Link
            :href="route('products.edit', product.id)"
            class="text-c-green-500 underline"
            >Edit</Link
          >

          <button
            @click="deleteProduct(product.id, $event)"
            class="text-fuchsia-900 underline"
          >
            Delete
          </button>
          <svg
            @click="selectProduct(product)"
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
        </td>
      </tr>
    </table>

    <div
      class="bg-white w-80 fixed p-4 right-0 top-0 bottom-0 transform transition duration-150 ease-linear z-20"
      :class="{ 'translate-x-full': Object.keys(selectedProduct).length === 0 }"
    >
      <header class="mb-6">
        <span class="font-medium block">Product number: {{ selectedProduct.id }}</span>
      </header>

      <div class="bg-zinc-100 p-4 mb-4 rounded">
        <span class="block text-sm font-medium mb-4">Product details</span>
        <div class="bg-zinc-100 rounded pb-4 flex items-center space-x-3">
          <div class="text-md">Category Name:</div>
          <span class="font-medium text-sm">
            {{ selectedProduct?.category?.name }}
          </span>
        </div>
        <div class="bg-zinc-100 rounded pb-4 flex items-center space-x-3">
          <div class="text-md">Section Name:</div>
          <span class="font-medium text-sm">
            {{ selectedProduct?.section?.name }}
          </span>
        </div>
      </div>
    </div>

    <div
      v-if="Object.keys(selectedProduct).length > 0"
      @click="resetselectProduct()"
      class="bg-black fixed inset-0 opacity-10 z-10"
    ></div>
  </DashboardLayout>
</template>

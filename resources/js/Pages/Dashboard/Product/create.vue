<script setup>
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { useForm } from "@inertiajs/inertia-vue3";
import TextInput from "@/Components/Breeze/TextInput.vue";
import InputLabel from "@/Components/Breeze/InputLabel.vue";
import InputError from "@/Components/Breeze/InputError.vue";
import { Inertia } from "@inertiajs/inertia";
import Dropdown from "@/Components/Breeze/Dropdown.vue";
import { ref } from "vue";

const props = defineProps({
  categories: Array,
  sections: Array,
});

const form = useForm({
  name: "",
  description: "",
  price: "",
  section_id: "",
  category_id: "",
  image: null,
});

const imagePreview = ref(null);

const createProduct = () => {
  Inertia.post(route("products.store"), form, {
    onStart: () => {
      form.processing = true;
    },
    onFinish: () => {
      form.processing = false;
    },
  });
};

const handleFileUpload = (event) => {
  const file = event.target.files[0];
  form.image = file; 
  imagePreview.value = URL.createObjectURL(file); 
};

</script>

<template>
  <DashboardLayout>
    <Head title="Create Product" />

    <header class="mb-8">
      <h2 class="font-medium uppercase text-2xl">Create Product</h2>
    </header>

    <form @submit.prevent="createProduct()">
      <div class="mb-5">
        <InputLabel for="name" value="Name" />
        <TextInput
          id="name"
          type="text"
          class="mt-1 block w-full placeholder-zinc-400"
          placeholder="Enter Name"
          v-model="form.name"
          required
        />
        <InputError class="mt-2" :message="$page.props.errors.name" />
      </div>
      <div class="mb-5">
        <InputLabel for="description" value="Description" />
        <TextInput
          v-model="form.description"
          id="description"
          placeholder="Enter product description"
          class="mt-1 block w-full placeholder-zinc-400"
        />
        <InputError class="mt-2" :message="$page.props.errors.description" />
      </div>
      <div class="mb-5">
        <InputLabel for="price" value="Price" />
        <TextInput
          v-model="form.price"
          id="price"
          type="number"
          placeholder="Enter price"
          class="mt-1 block w-full placeholder-zinc-400"
          required
        />
        <InputError class="mt-2" :message="$page.props.errors.price" />
      </div>
  <!-- Category Dropdown -->
      <div class="mb-5">
        <InputLabel for="category" value="Category" />

        <Dropdown>
          <template #trigger>
            <button  type="button" 
              class="mt-1 w-full placeholder-zinc-400 py-2 px-3 text-left border rounded text-gray-700 focus:outline-none"
            >
              {{
                categories.find((cat) => cat.id === form.category_id)?.name ||
                "Select a Category"
              }}
            </button>
          </template>
          <template #content>
            <div class="max-h-60 overflow-y-auto">
            <ul class="py-1 bg-white ">
              <li
                v-for="category in categories"
                :key="category.id"
                class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                @click="form.category_id = category.id"
              >
                {{ category.name }}
              </li>
            </ul>
            </div>
          </template>
        </Dropdown>
      </div>

      <!-- Section Dropdown -->
      <div class="mb-5">
        <label for="section" class="block text-gray-700 text-sm font-bold mb-2">
          Section
        </label>
        <Dropdown>
          <template #trigger>
            <button  type="button" 
              class="w-full py-2 px-3 text-left border rounded text-gray-700 focus:outline-none"
            >
              {{
                sections.find((sec) => sec.id === form.section_id)?.name ||
                "Select a Section"
              }}
            </button>
          </template>
          <template #content>
            <ul class="py-1 bg-white">
              <li
                v-for="section in sections"
                :key="section.id"
                class="px-4 py-2 cursor-pointer hover:bg-gray-100"
                @click="form.section_id = section.id"
              >
                {{ section.name }}
              </li>
            </ul>
          </template>
        </Dropdown>
      </div>

        <!-- Image Upload -->
        <div class="mb-4">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="image">
          Product Image
        </label>
        <input
          @change="handleFileUpload"
          id="image"
          type="file"
          class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
        />
      </div>

      <!-- Image Preview -->
      <div v-if="imagePreview" class=" flex  w-20 h-20" >
        <label class="block mr-4 text-gray-700 text-sm font-bold mb-2">Image Preview</label>
        <img :src="imagePreview" alt="Product Image Preview" class=" w-full h-auto rounded shadow-md"/>
      </div>

      <button
        type="submit"
        :disabled="form.processing"
        :class="
          form.processing ? 'bg-c-green-100' : 'bg-c-green-300 hover:bg-c-green-100'
        "
        class="text-white text-xxs py-2 w-32 rounded transition flex justify-center mt-6"
      >
        <span class="uppercase" v-if="!form.processing">Create </span>
        <svg
          v-else
          class="animate-spin-fast w-[17px] h-[17px] text-white"
          fill="currentColor"
          version="1.1"
          id="Layer_1"
          xmlns="http://www.w3.org/2000/svg"
          xmlns:xlink="http://www.w3.org/1999/xlink"
          x="0px"
          y="0px"
          viewBox="0 0 512 512"
          style="enable-background: new 0 0 512 512"
          xml:space="preserve"
        >
          <g>
            <path
              d="M256,512c-34.6,0-68.1-6.8-99.6-20.1C125.9,479,98.5,460.5,75,437s-42-50.9-54.9-81.4C6.8,324.1,0,290.5,0,256
                        c0-9.9,8.1-18,18-18s18,8.1,18,18c0,29.7,5.8,58.5,17.3,85.6c11.1,26.2,26.9,49.8,47.1,70c20.2,20.2,43.8,36.1,69.9,47.1
                        c27.1,11.5,55.9,17.3,85.6,17.3s58.5-5.8,85.6-17.3c26.2-11.1,49.8-27,70-47.2c20.2-20.2,36.1-43.8,47.1-69.9
                        c11.5-27.1,17.3-55.9,17.3-85.6c0-29.7-5.8-58.5-17.3-85.6c-11.1-26.1-27.1-49.9-47.2-70c-20-20.1-43.8-36.1-69.9-47.1
                        C314.5,41.8,285.7,36,256,36c-9.9,0-18-8.1-18-18s8.1-18,18-18c34.6,0,68.1,6.8,99.6,20.1C386.2,33,413.5,51.5,437,75
                        s42,50.9,54.9,81.4c13.4,31.5,20.1,65.1,20.1,99.6c0,34.5-6.8,68.1-20.1,99.6C479,386.1,460.5,413.5,437,437s-50.9,42-81.4,54.9
                        C324.1,505.3,290.6,512,256,512z"
            />
          </g>
        </svg>
      </button>
    </form>
  </DashboardLayout>
</template>

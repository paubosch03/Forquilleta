<script setup>
import { ref } from 'vue';
import { defineProps } from 'vue';
import axios from 'axios';

const props = defineProps({
    restaurant: {
        type: Object,
        required: true
    }
});

const isModalOpen = ref(false);
const editedName = ref(props.restaurant.name);
const editedDescription = ref(props.restaurant.description);
const editedCity = ref(props.restaurant.city);

function openModal() {
    isModalOpen.value = true;
}

function closeModal() {
    isModalOpen.value = false;
}

function saveChanges() {
    axios.get(`/restaurants/update/${props.restaurant.id}`, {
        params: {
            name: editedName.value,
            description: editedDescription.value,
            city: editedCity.value
        }
    })
    .then(response => {
        console.log("Restaurant updated successfully:", response);
        window.location.reload();
    })
    .catch(error => {
        console.error("There was an error updating the restaurant:", error);
    });
}

function deleteRestaurant() {
    axios.delete(`/restaurants/delete/${props.restaurant.id}`)
    .then(response => {
        console.log("Restaurant deleted successfully:", response);
        window.location.reload();
    })
    .catch(error => {
        console.error("There was an error deleting the restaurant:", error);
    });
}
</script>

<template>
    <div class="flex-container">
        <div class="restaurant-card">
            <a href="#">
                <img class="rounded-t-lg w-full h-48 object-cover" :src="restaurant.image || '/images/logo.jpg'" alt="Restaurant Image" />
            </a>
            <div class="p-4">
                <a href="#">
                    <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">{{ restaurant.name }}</h5>
                </a>
                <p class="mb-2 text-gray-700 dark:text-gray-400">{{ restaurant.description }}</p>
                <p class="mb-2 text-gray-700 dark:text-gray-400">{{ restaurant.city }}</p>
                <div class="flex justify-center mt-4 space-x-4">
                    <button @click="openModal" type="button"
                        class="text-white inline-flex items-center bg-[#008759] hover:bg-[#006f48] focus:ring-4 focus:outline-none focus:ring-[#005b3c] font-medium rounded-lg text-sm px-4 py-2 text-center transition-all duration-300">
                        Edit
                    </button>
                    <button @click="deleteRestaurant" type="button"
                        class="text-white inline-flex items-center bg-[#d93b3b] hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-4 py-2 text-center transition-all duration-300">
                        Delete
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div v-if="isModalOpen"
        class="fixed z-50 inset-0 overflow-y-auto bg-black bg-opacity-50 flex justify-center items-center modal-overlay">
        <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
            <div class="flex justify-between items-center border-b p-4">
                <h3 class="text-lg font-semibold text-gray-800">Edit Restaurant</h3>
                <button @click="closeModal" class="text-gray-600 hover:text-gray-800">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <div class="p-4">
                <div class="mb-4">
                    <label for="edit-name" class="block text-sm font-medium text-gray-700">Name</label>
                    <input id="edit-name" v-model="editedName" type="text"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="mb-4">
                    <label for="edit-description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea id="edit-description" v-model="editedDescription" rows="3"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                </div>
                <div class="mb-4">
                    <label for="edit-city" class="block text-sm font-medium text-gray-700">City</label>
                    <input id="edit-city" v-model="editedCity" type="text"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div class="flex justify-end">
                    <button @click="saveChanges" type="button"
                        class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Save
                    </button>
                    <button @click="closeModal" type="button"
                        class="ml-2 inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md shadow-sm bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>

.flex-container {
    display: inline-block;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    margin: 100px; /* Ajusta según sea necesario */
}

.restaurant-card {
  
    width: 300px; /* Establecer un ancho fijo para las tarjetas */
}

.modal-overlay {
    display: flex;
    justify-content: center;
    align-items: center;
    background: rgba(0, 0, 0, 0.5);
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    z-index: 50;
}
</style>

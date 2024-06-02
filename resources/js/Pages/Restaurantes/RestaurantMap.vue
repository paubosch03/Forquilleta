<script setup>
import { ref, onMounted } from 'vue';
import { defineProps } from 'vue';
import axios from 'axios';
import L from 'leaflet';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faStar } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

library.add(faStar);

const props = defineProps({
    mapStart: {
        type: Object,
        required: true
    }
});

const map = ref(null);
let markers = [];

const getMap = (star) => {
    axios.get(`/restaurants/getRestaurantMap/${star}`)
        .then(response => {
            markers.forEach(marker => map.value.removeLayer(marker));
            markers = [];
            response.data.forEach(element => {
                console.log(element);
                const reviewRating = element.average_review_rating !== null ? element.average_review_rating : "Sin reviews";
                const marker = L.marker([element.latitude, element.longitude])
                    .addTo(map.value)
                    .bindPopup(`<b>Restaurant:</b> ${ element.name } <br> <b>Nota:</b> ${ reviewRating }`, { autoClose: false })
                    .openPopup();
                markers.push(marker);
            });
            console.log("Mapa cargado correctamente:", response);
        })
        .catch(error => {
            console.error("Hubo un error al cargar el mapa:", error);
        });
};

onMounted(() => {
    map.value = L.map(`map`).setView([42.2664500, 2.9616300], 13);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map.value);
    getMap(0);
});

const rating = ref(0);

const setRating = (star) => {
    rating.value = star;
    getMap(star);
};

</script>
<template>
<AuthenticatedLayout>
    <!-- Map -->
    <div class="flex justify-center mt-4 space-x-4">
        <h3>Filtro nota restaurante:</h3>
        <template v-for="star in 5" :key="star">
            <font-awesome-icon :icon="['fas', 'star']" :class="star <= rating ? 'text-yellow-500' : 'text-gray-300'"
                class="w-6 h-6 cursor-pointer" @click="setRating(star)" />
        </template>
    </div>
    <div class="mt-8">
        <div :id="'map'" class="h-96"></div>
    </div>
</AuthenticatedLayout>
</template>
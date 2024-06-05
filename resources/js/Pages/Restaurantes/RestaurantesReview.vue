<script setup>
import { onMounted, ref } from 'vue';
import RestaurantCard from './RestaurantCard.vue';
import AddRestaurant from './AddRestaurant.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import RestaurantCardReview from './RestaurantCardReview.vue';
onMounted(() => {
    axios.get('/restaurantes/getAll').then(response => {
        restaurantes.value = response.data;
    });
});

let restaurantes = ref([]);
</script>
<template>

    <AuthenticatedLayout>
        <div>
            <RestaurantCardReview v-for="restaurant in restaurantes" :key="restaurant.id" :restaurant="restaurant" />
        </div>
    </AuthenticatedLayout>

</template>
<script setup>
import { onMounted, ref } from 'vue';
import RestaurantCard from './RestaurantCard.vue';
import AddRestaurant from './AddRestaurant.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
onMounted(() => {
    axios.get('/restaurantes/get').then(response => {
        restaurantes.value = response.data;
    });
});

let restaurantes = ref([]);
</script>
<template>

    <AuthenticatedLayout>
        <div>
            <AddRestaurant></AddRestaurant>
        </div>
        <div>
            <RestaurantCard v-for="restaurant in restaurantes" :key="restaurant.id" :restaurant="restaurant" />
        </div>
    </AuthenticatedLayout>

</template>

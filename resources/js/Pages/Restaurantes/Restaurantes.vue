<script setup>
import { onMounted, ref } from 'vue';
import RestaurantCard from './RestaurantCard.vue';
import AddRestaurant from './AddRestaurant.vue';
onMounted(() => {
    axios.get('/restaurantes/get').then(response => {
        restaurantes.value = response.data;
    });
});

let restaurantes = ref([]);
</script>
<template>
    <div>
        <AddRestaurant></AddRestaurant>
    </div>
    <div>
        <RestaurantCard v-for="restaurant in restaurantes" :key="restaurant.id" :restaurant="restaurant" />
    </div>

</template>
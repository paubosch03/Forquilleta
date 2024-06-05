<script setup>
import { ref } from 'vue';
import { defineProps } from 'vue';
import axios from 'axios';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faStar } from '@fortawesome/free-solid-svg-icons';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { onMounted } from 'vue';

library.add(faStar);

const props = defineProps({
    restaurant: {
        type: Object,
        required: true,
    }
});

const isModalOpen = ref(false);
const isModalReviewOpen = ref(false);
const isModalDeleteOpen = ref(false);
const rating = ref(0);
const comment = ref('');
const selectedReview = ref({ comment: '', rating: 0, id: null });
const city = ref('');

const initialize = () => {
    // Initialization logic here
};

onMounted(() => {
    initialize();
});

const openModal = (review, reviewCity) => {
    selectedReview.value = review;
    city.value = reviewCity;
    isModalOpen.value = true;
};

const closeModal = () => {
    isModalOpen.value = false;
    selectedReview.value = { comment: '', rating: 0, id: '' };
    city.value = '';
};

const openReviewModal = (review) => {
    selectedReview.value = { ...review }; // Set selected review
    isModalReviewOpen.value = true;
    comment.value = selectedReview.value.comment;
    rating.value = selectedReview.value.rating;
};

const closeReviewModal = () => {
    isModalReviewOpen.value = false;
    comment.value = '';
    rating.value = 0;
};

const openDeleteModal = (review) => {
    selectedReview.value = review;
    isModalDeleteOpen.value = true;
};

const closeDeleteModal = () => {
    isModalDeleteOpen.value = false;
};

const setRating = (star) => {
    rating.value = star;
};

const submitReview = async () => {
    try {
        await axios.post('/review', {
            restaurant_id: props.restaurant.id,
            rating: rating.value,
            comment: comment.value,
        });
        alert('Rating submitted successfully!');
        comment.value = '';
        rating.value = 0;
    } catch (error) {
        console.error('Failed to submit rating:', error);
        alert('Failed to submit rating');
    }
};

function updateReview() {
    if (!selectedReview.value.id) {
        console.error('No review selected.');
        return;
    }

    axios.put(`/review/update/${selectedReview.value.id}`, {
        rating: rating.value,
        comment: comment.value,
    })
        .then(response => {
            console.log('Review updated successfully:', response);
            alert('Review updated successfully!');
            closeReviewModal();
        })
        .catch(error => {
            console.error('Failed to update review:', error);
            alert('Failed to update review');
        });
}

function deleteReview() {
    if (!selectedReview.value.id) {
        console.error('No review selected for deletion.');
        return;
    }

    axios.delete(`/review/delete/${selectedReview.value.id}`)
        .then(response => {
            console.log("Review deleted successfully:", response);
            window.location.reload();
        })
        .catch(error => {
            console.error("There was an error deleting the review:", error);
        });
}
</script>
<template>
    <div class="flex-container">
        <div class="review-card">
            <div class="flex justify-center items-center h-full">
                <div
                    class="w-full max-w-2xl bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 ease-in-out dark:bg-gray-800 dark:border-gray-700 mr-4">
                    <a href="#">
                        <img class="rounded-t-lg w-full h-48 object-cover" :src="restaurant.image || '/images/logo.jpg'"
                            alt="Restaurant Image" />
                    </a>
                    <div class="p-6">
                        <a href="#">
                            <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">{{
                                restaurant.name
                            }}</h5>
                        </a>
                        <button @click="openModal(restaurant.review, restaurant.city)"
                            class="mt-2 block text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            aria-label="View Details">
                            Details
                        </button>
                        <div v-if="restaurant.review">
                            <div class="flex justify-center mt-4 space-x-4">
                                <template v-for="star in 5" :key="star">
                                    <font-awesome-icon :icon="['fas', 'star']"
                                        :class="star <= restaurant.review.rating ? 'text-yellow-500' : 'text-gray-300'"
                                        class="w-6 h-6 cursor-pointer" />
                                </template>
                            </div>
                            <div class="mt-4">
                                <label :for="'comment-' + restaurant.id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-400">Comment:</label>
                                <textarea :id="'comment-' + restaurant.id" v-model="restaurant.review.comment" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"></textarea>
                            </div>
                            <div class="flex justify-center mt-4 space-x-4">
                                <button @click="openReviewModal(restaurant.review)" type="button"
                                    class="text-white inline-flex items-center bg-[#008759] hover:bg-[#006f48] focus:ring-4 focus:outline-none focus:ring-[#005b3c] font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-all duration-300"
                                    aria-label="Update Review">
                                    Update
                                </button>
                                <button @click="openDeleteModal(restaurant.review)" type="button"
                                    class="text-white inline-flex items-center bg-[#d93b3b] hover:bg-red-600 focus:ring-4 focus:outline-none focus:ring-red-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-all duration-300"
                                    aria-label="Delete Review">
                                    Delete
                                </button>
                            </div>
                        </div>
                        <div v-else>
                            <div class="flex justify-center mt-4 space-x-4">
                                <template v-for="star in 5" :key="star">
                                    <font-awesome-icon :icon="['fas', 'star']"
                                        :class="star <= rating ? 'text-yellow-500' : 'text-gray-300'"
                                        class="w-6 h-6 cursor-pointer" @click="setRating(star)" />
                                </template>
                            </div>
                            <div class="mt-4">
                                <label :for="'new-comment-' + restaurant.id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-400">Comment:</label>
                                <textarea :id="'new-comment-' + restaurant.id" v-model="comment" rows="3"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"></textarea>
                            </div>
                            <button @click="submitReview" type="button"
                                class="mt-4 w-full text-white inline-flex items-center bg-[#008759] hover:bg-[#006f48] focus:ring-4 focus:outline-none focus:ring-[#005b3c] font-medium rounded-lg text-sm px-5 py-2.5 text-center transition-all duration-300"
                                aria-label="Submit Review">
                                Submit Review
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal -->
            <div v-if="isModalOpen"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 overflow-x-hidden overflow-y-auto bg-gray-900 bg-opacity-50 transition-opacity duration-300">
                <div class="relative w-full max-w-2xl max-h-full">
                    <!-- Modal content -->
                    <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                        <!-- Modal header -->
                        <div class="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600">
                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">
                                Restaurant Details
                            </h3>
                            <button @click="closeModal" type="button"
                                class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                                aria-label="Close Modal">
                                <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                                    viewBox="0 0 14 14">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                        stroke-width="2" d="M1 1l12 12M13 1 1 13" />
                                </svg>
                                <span class="sr-only">Close modal</span>
                            </button>
                        </div>
                        <!-- Modal body -->
                        <div class="p-6 space-y-4">
                            <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                Description: {{ restaurant.description }}
                            </p>
                            <p class="text-base leading-relaxed text-gray-500 dark:text-gray-400">
                                City: {{ city }}
                            </p>
                        </div>
                        <!-- Modal footer -->
                        <div
                            class="flex items-center justify-end p-6 space-x-2 border-t border-gray-200 rounded-b dark:border-gray-600">
                            <button @click="closeModal" type="button"
                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                aria-label="Close Details">
                                Close
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Review Modal -->
            <div v-if="isModalReviewOpen"
                class="fixed z-50 inset-0 overflow-y-auto bg-black bg-opacity-50 flex justify-center items-center transition-opacity duration-300">
                <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
                    <div class="flex justify-between items-center border-b p-4">
                        <h3 class="text-lg font-semibold text-gray-800">Edit Review</h3>
                        <button @click="closeReviewModal" class="text-gray-600 hover:text-gray-800"
                            aria-label="Close Edit Review Modal">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12">
                                </path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-4">
                        <div class="flex justify-center mt-4 space-x-4">
                            <template v-for="star in 5" :key="star">
                                <font-awesome-icon :icon="['fas', 'star']"
                                    :class="star <= rating ? 'text-yellow-500' : 'text-gray-300'"
                                    class="w-6 h-6 cursor-pointer" @click="setRating(star)" />
                            </template>
                        </div>
                        <div class="mt-4">
                            <label :for="'edit-comment-' + restaurant.id"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-400">Comment:</label>
                            <textarea :id="'edit-comment-' + restaurant.id" v-model="comment" rows="3"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"></textarea>
                        </div>
                        <div class="flex justify-end mt-4">
                            <button @click="updateReview" type="button"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                aria-label="Save Review">
                                Save
                            </button>
                            <button @click="closeReviewModal" type="button"
                                class="ml-2 inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md shadow-sm bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                aria-label="Cancel Edit Review">
                                Cancel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Delete Confirmation Modal -->
            <div v-if="isModalDeleteOpen"
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50">
                <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
                    <div class="p-4">
                        <h3 class="text-lg font-semibold text-gray-800">Delete Review</h3>
                        <p class="mt-2 text-gray-600">Are you sure you want to delete this review?</p>
                        <div class="flex justify-end mt-4 space-x-2">
                            <button @click="deleteReview"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-transparent rounded-md shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                aria-label="Confirm Delete">
                                Yes
                            </button>
                            <button @click="closeDeleteModal"
                                class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 border border-gray-300 rounded-md shadow-sm bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                aria-label="Cancel Delete">
                                No
                            </button>
                        </div>
                    </div>
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
    margin: 100px;
    /* Ajusta según sea necesario */
}

.review-card {
    width: 450px;
}
</style>
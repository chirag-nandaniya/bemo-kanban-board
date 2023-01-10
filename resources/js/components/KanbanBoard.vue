<template>
  <div class="relative p-2 flex overflow-x-auto h-full">

    <!-- Columns (Statuses) -->
    <div
      v-for="status in statuses"
      :key="status.slug"
      class="mr-6 w-4/5 max-w-xs flex-1 flex-shrink-0"
    >
      <div class="rounded-md shadow-md overflow-hidden">
        <div class="p-3 flex justify-between items-baseline bg-blue-800 ">
          <h4 class="font-medium text-white">
            {{ status.title }}
          </h4>
          <button class="py-1 px-2 text-sm text-orange-500 hover:underline" @click="openAddCardForm(status.id)">
            Add Card
          </button>
        </div>
        <div class="p-2 flex-1 flex flex-col h-full overflow-x-hidden overflow-y-auto bg-blue-100">

          <!-- Cards -->
          <div
            v-for="card in status.cards"
            :key="card.id"
            class="mb-3 p-3 h-24 flex flex-col bg-white rounded-md shadow transform hover:shadow-md cursor-pointer"
          >
            <span class="block mb-2 text-xl text-gray-900">
              {{ card.title }}
            </span>
            <p class="text-gray-700 truncate">
              {{ card.description }}
            </p>
          </div>
          <AddCardForm
    v-if="newCardForStatus === status.id"
    :status-id="status.id"
    v-on:card-added="handleCardAdded"
    v-on:card-canceled="closeAddCardForm"
  />
          <!-- ./Cards -->

          <!-- No Cards -->
          <div
            v-show="!status.cards.length"
            class="flex-1 p-4 flex flex-col items-center justify-center"
          >
            <span class="text-gray-600">No cards yet</span>
            <button
              class="mt-1 text-sm text-orange-600 hover:underline" @click="openAddCardForm(status.id)"
            >
              Add one
            </button>
          </div>
          <!-- ./No Cards -->
        </div>
      </div>
    </div>
    <!-- ./Columns -->

  </div>
</template>

<script>
import AddCardForm from "./AddCardForm";
export default {
  components: { AddCardForm }, // register component
  props: {
    initialData: Array
  },
  data() {
    return {
      statuses: [],
      newCardForStatus: 0 // track the ID of the status we want to add to
    };
  },
  mounted() {
    // 'clone' the statuses so we don't alter the prop when making changes
    this.statuses = JSON.parse(JSON.stringify(this.initialData));
  },
  methods: {
    // set the statusId and trigger the form to show 
    openAddCardForm(statusId) {
      this.newCardForStatus = statusId;
    },
    // reset the statusId and close form
    closeAddCardForm() {
      this.newCardForStatus = 0;
    },
    // add a card to the correct column in our list
    handleCardAdded(newCard) {
      // Find the index of the status where we should add the card
      const statusIndex = this.statuses.findIndex(
        status => status.id === newCard.status_id
      );

      // Add newly created card to our column
      this.statuses[statusIndex].cards.push(newCard);

      // Reset and close the AddCardForm
      this.closeAddCardForm();
    },
  }
};
</script>
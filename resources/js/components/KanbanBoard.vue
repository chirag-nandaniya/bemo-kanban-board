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
        <div class="p-2 bg-blue-100">

          <!-- Cards -->
          <draggable
      class="flex-1 overflow-hidden"
      v-model="status.cards"
      v-bind="cardDragOptions"
      @end="handleCardMoved"
    >
      <transition-group
        class="flex-1 flex flex-col h-full overflow-x-hidden overflow-y-auto rounded shadow-xs"
        tag="div"
      >
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
          </transition-group>
    </draggable>
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
import draggable from "vuedraggable";
export default {
  components: { draggable, AddCardForm }, // register component
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
  computed: {
    cardDragOptions() {
      return {
        animation: 200,
        group: "card-list",
        dragClass: "status-drag"
      };
    }
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
    handleCardMoved() {
      // Send the entire list of statuses to the server
      axios.put("/cards/sync", {columns: this.statuses}).catch(err => {
        console.log(err.response);
      });
    },
  },

  
};
</script>
<style scoped>
.status-drag {
  transition: transform 0.5s;
  transition-property: all;
}
</style>
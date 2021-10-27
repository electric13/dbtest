<template>
  <div>
      <basket-item
          v-for="item in items"
          v-bind="item"
          :key="item.id"
      >
      </basket-item>
  </div>
</template>

<script>

import BasketItem from "./BasketItem";

function BaskItem(id, b_item, amount) {
    this.id = id;
    this.b_item = b_item;
    this.amount = amount;
}

const default_layout = "default";

export default {
  computed: {},
  data() {
      return {
          items: []
      }
  },
  methods: {
    async create() {
              // To do
    },
    async read() {
        //const { data } = window.axios.get('/api/cruds');
        //data.forEach(crud => this.cruds.push(new Crud(crud)));
        this.items.push(new BaskItem(7, 'Элемент 1 (5p)', 5));
        this.items.push(new BaskItem(8, 'Элемент 2 (2p)', 2));
        this.items.push(new BaskItem(9, 'Элемент 3 (4p)', 4));
    },
    async update(id, color) {
       // To do
    },
    async delete(id) {
       // To do
    }
  },
  components: {
    BasketItem
  },
  created() {
    this.read();
  }
};

</script>

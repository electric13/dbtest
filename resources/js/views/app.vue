<template>
  <div>
    <basket-item
          v-for="item in items"
          v-bind="item"
          :key="item.id"
          @del-item="delItem"
     />
     <button @click="addItem">Добавить</button>
  </div>
</template>

<script>

import BasketItem from "./BasketItem";
import axios from "axios";

function BaskItem(parent, id, m_id, p_id, amount, length, price) {
    this.parent = parent;
    this.id = id;
    this.m_id = m_id;
    this.p_id = p_id;
    this.amount = amount;
    this.length = length;
    this.price = price;
}

const default_layout = "default";

export default {
  computed: {},
  data() {
      return {
          items: [],
          materials: [],
          products: [],
      }
  },
  methods: {
      async create() {
          // To do
      },
      async read() {
          let promises = [], tmp = [];
          //загружаем справочник материалов
          if (this.materials.length == 0) {
              promises.push(axios.get('/api/materials').then(response => {
                  this.materials = response.data.data;
              }));
          };
          //и справочник продукции
          if (this.products.length == 0) {
              promises.push(axios.get('/api/products').then(response => {
                  this.products = response.data.data;
              }));
          }
          promises.push(axios.get('/api/basket')
               .then(response => {
                   tmp = response.data.data;
               }));

          Promise.all(promises).then(responses => {
              for (let i of tmp) {
                  this.items.push(new BaskItem(this, i.id,
                                                     i.material_id,
                                                     i.product_id,
                                                     i.amount,
                                                     i.length,
                                                     0))
              }
          });
      },
      async update() {
      },

      async addItem() {
          this.items.push(new BaskItem(this, 10, 6, 5, 7, 1440, 23.0));
      },

      async delItem(id) {
          let f_id = this.items.findIndex(x => x.id === id);
          if (f_id >= 0) {
/*              axios.get('/api/basket')
                    .then(response => {
                      tmp = response.data.data;
                  })*/
              const res = await axios.post('/basket/del', 'bi_id='+id);
              this.items.splice(f_id, 1);
          }
      },
  },
  components: {
    BasketItem
  },
  created() {
    this.read();
  }
};

</script>

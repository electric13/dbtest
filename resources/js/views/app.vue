<template>
  <div @upd-item="updItem" @add-item="addItem">
    <table>
        <tr>
            <th>Код стр.</th>
            <th>Наименование</th>
            <th>Длина</th>
            <th>Кол-во</th>
            <th>Удалить</th>
            <th>Дополнительно</th>
        </tr>
        <basket-item
            v-for="item in items"
            v-bind="item"
            :key="item.id"
            @del-item="delItem"
        />

    </table>
     <br>BasketID={{ basketID }}<br>
     <button @click="addItem">Добавить</button>
  </div>
</template>

<script>

import BasketItem from "./BasketItem";
import axios from "axios";

function BaskItem(parent, id, m_id, p_id, i_id, amount, length, price) {
    this.parent = parent;
    this.id = id;
    this.m_id = m_id;
    this.p_id = p_id;
    this.i_id = i_id;
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
          linksMT: [],       //соответствие толщин материалам
          basketID: ''
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
                  // получаем список материалов
                  let t = [... new Set( Array.from(this.materials, ({material}) => material))];
                  //получаем набор доступных толщин для каждого материала
                  let obj = this;
                  this.linksMT = t.map( function (m) {
                      let thicknesses = this.materials.filter( function (material) {
                          return this == material.material;
                      }, m);
                      return {
                          "m": m,
                          "t": thicknesses
                      };
                  }, obj);
              }));
          }

          //и справочник продукции
          if (this.products.length == 0) {
              promises.push(axios.get('/api/products').then(response => {
                  this.products = response.data.data;
              }));
          }

          if ( localStorage.getItem('session_id') == null) {
              promises.push(axios.get('/api/register').then(response => {
                  this.basketID = response.data.key;
                  localStorage.setItem('session_id', this.basketID);
              }));
          } else {
              this.basketID = localStorage.getItem('session_id');
              let req = { "key": this.basketID }
              promises.push(axios.post('/api/basket', req, {})
                  .then(response => {
                      tmp = response.data.data;
                  }));
          }


          Promise.all(promises).then( () => {
              for (let i of tmp) {
                  this.items.push(new BaskItem(this, i.id,
                                                     i.material,
                                                     i.product,
                                                     i.item,
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

      async updItem(id) {
          console.log('app need to update line ' + id);
      },

      async delItem(id) {
          let f_id = this.items.findIndex(x => x.id === id);
          if (f_id >= 0) {
              let req = { "key": this.basketID, "id": id }
              axios.post('/api/basket/del', req, {})
                   .then(() => { this.items.splice(f_id, 1); })
                   .catch(err => alert(err));
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

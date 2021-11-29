<template>
  <div @upd-item="updItem" @add-item="addItem">
    <table>
        <tr>
            <th>Код стр.</th>
            <th>Наименование</th>
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
          items: [],        // элементы корзины, не справочник товаров!
          nom: [],          // справочник штучного товара
          n_groups: [],      // справочник товарных групп
          materials: [],    // справочник материалов
          products: [],     // справочник видов продукции
          linksMT: [],      //соответствие толщин материалам
          basketID: '',
          requests: [],     // запросы на обновление
          needUpd: false    // индикатор недавнего обновления
      }
  },
  methods: {
      timerHandler(){
          let qN = this.requests.length
          if ( qN > 0 && this.needUpd ) {
              // недавно обновляли, ждем секунду бездействия
              this.needUpd = false
          }
          if ( qN > 0 && ! this.needUpd) {
              //обновлений не было, можно обрабатывать
              console.log('Запросов в очереди '+ this.requests.length)
              let req = this.requests.pop()
              console.log(req.request)
          }
          // и засыпаем
          setTimeout(this.timerHandler, 2500);
      },

      read() {
          // чтение справочников и после этого построение корзины, с использованием данных оттуда
          let promises = [], tmp = [];
          //загружаем справочник материалов
          if (this.materials.length === 0) {
              promises.push(axios.get('/api/materials').then(response => {
                  this.materials = response.data.data;
                  // получаем список материалов
                  let t = [... new Set( Array.from(this.materials, ({material}) => material))];
                  //получаем набор доступных толщин для каждого материала
                  let obj = this;
                  this.linksMT = t.map( function (m) {
                      let thicknesses = this.materials.filter( function (material) {
                          return this === material.material;
                      }, m);
                      return {
                          "m": m,
                          "t": thicknesses
                      };
                  }, obj);
              }));
          }

          //и справочник продукции
          if (this.products.length === 0) {
              promises.push(axios.get('/api/products').then(response => {
                  this.products = response.data.data;
              }));
          }

          // справочник штучного товара
          if (this.nom.length === 0) {
              promises.push(axios.get('/api/items').then(response => {
                  let l_i = response.data.data;
                  for (let nom_item of l_i) {
                      this.nom[nom_item.id] = {
                          'id'      : nom_item.id,
                          'itemname': nom_item.itemname,
                          'group_id': nom_item.group_id,
                          'color'   : nom_item.color
                      };
                  }
              }));
          }

          // справочник групп штучного товара
          if (this.n_groups.length === 0) {
              promises.push(axios.get('/api/groups').then(response => {
                  let l_g = response.data.data;
                  for (let gr of l_g) {
                      this.n_groups[gr.id] = {
                          'id'       : gr.id,
                          'groupname': gr.groupname,
                          'colored'  : gr.colored
                      };
                  }
              }));
          }

          //ищем ключ с прошлого сеанса, если нет - генерируем новый.
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

      async addItem() {
          // Добавление в корзину должно быть в отдельном компоненте
          this.items.push(new BaskItem(this, 10, 6, 5, 7, 1440, 23.0));
      },

      async updItem(id) {
          // тоже хз зачем это, каждый компонент обновляется сам. Возможно, надо сделать очередь
          // куда складывать все обновление, и централизованно обновлять.
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
      setTimeout(this.timerHandler, 2500);
  }
};

</script>

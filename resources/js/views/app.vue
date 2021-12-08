<template>
  <div class="row m-0">
      <b-col cols="11" class="mx-auto">
      <b-table-simple hover small caption-top responsive d-flex bordered class="full-screen">
          <b-thead head-variant="dark">
              <b-tr>
                  <b-th class="col-8">Наименование</b-th>
                  <b-th class="col-2">Количество</b-th>
                  <b-th class="col-2">Стоимость</b-th>
              </b-tr>
          </b-thead>
          <b-tbody>
              <basket-item
                  v-for="item in items"
                  v-bind="item"
                  :key="item.id"
                  @del-item="delItem"
              />
              <new-item-row v-if="dataLoaded"
                            v-bind="adder"
                            @add-item="reload"/>
          </b-tbody>
      </b-table-simple>
      </b-col>
  </div>
</template>

<script>

import BasketItem from "./BasketItem";
import NewItemRow from "./NewItemRow";
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

//const default_layout = "default";

export default {
  computed: {},
  data() {
      return {
          items: [],        // элементы корзины, не справочник товаров!
          adder: {},        // компонент "добавление нового товара в корзину"
          nom: [],          // справочник штучного товара
          n_groups: [],      // справочник товарных групп
          materials: [],    // справочник материалов
          products: [],     // справочник видов продукции
          linksMT: [],      //соответствие толщин материалам
          basketID: '',
          requests: [],     // запросы на обновление
          needUpd: false,   // индикатор недавнего обновления
          dataLoaded: false // флаг, что все загружено
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
              let req = this.requests.pop()
              if (req.command === 'update') {
                  axios.post(req.url, req.request, {})
                       .then(response => {
                           // успешно обновили
                           let id = this.items.findIndex( x => x.id === req.id )
                           if ( typeof id != 'undefined') {
                               // передаем вновь полученную цену
                               this.items[id].price = response.data.data[0].price
                           }
                       })
                       .catch(error => {
                           console.log('Произошла ошибка при обновлении элемента корзины')
                           console.log(error)
                       })
              }
              if (req.command === 'price') {
                  axios.post(req.url, req.request, {})
                      .then(response => {
                          // успешно обновили
                          this.adder.price = response.data[0].price
                      })
                      .catch(error => {
                          console.log('Произошла ошибка при запросе цены')
                          console.log(error)
                      })
              }

          }
          // и засыпаем
          setTimeout(this.timerHandler, 2500);
      },

      reload() {
          // перезагрузка данных при добавлении нового товара
          this.read()
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
                      tmp = response.data.data
                  }));
          }

          Promise.all(promises).then( () => {
              if (this.items.length > 0 && tmp.length > 0) {
                  // необходимо поместить в локальную корзину элементы, которые отсутствуют,
                  // но есть в серверной корзине (только что добавленные)
                  for (let i = 0; i < tmp.length; i++) {
                      tmp[i].position = i;
                  }
                  for (let i of this.items) {
                      let a = tmp.findIndex(x => x.id === i.id)
                      if (a >= 0) { tmp.splice(a, 1) }
                  }
                  if (tmp.length > 0) {
                      for (let i of tmp) {
                          this.items.splice( i.position, 0,  new BaskItem(this, i.id,
                                                                            i.material,
                                                                            i.product,
                                                                            i.item,
                                                                            i.amount,
                                                                            i.length,
                                                                            i.price))
                      }
                  }
              } else {
                  for (let i of tmp) {
                      this.items.push(new BaskItem(this, i.id,
                          i.material,
                          i.product,
                          i.item,
                          i.amount,
                          i.length,
                          i.price))
                  }
                  this.adder = { 'parent': this, 'price': 0 }
              }
              this.dataLoaded = true
          })
      },

      delItem(id) {
          let f_id = this.items.findIndex(x => x.id === id);
          if (f_id >= 0) {
              let req = { "key": this.basketID, "id": id }
              axios.post('/api/basket/delete', req, {})
                   .then(() => { this.items.splice(f_id, 1); })
                   .catch(err => alert(err));
          }
      },
  },
  components: {
      BasketItem,
      NewItemRow
  },
  created() {
      this.read();
      setTimeout(this.timerHandler, 2500);
  }
};

</script>

<style>

body {
    font-size: 0.75em;
    font-family: 'Raleway', sans-serif;
}

</style>

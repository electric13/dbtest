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

function BaskItem(id, material, product, amount, length, price) {
    this.id = id;
    this.material = material;
    this.product = product;
    this.amount = amount;
    this.length = length;
    this.price = price;
}

function i_helper(id, m_id, p_id, amnt, lng, prc){
    this.id = id;
    this.m_id = m_id;
    this.p_id = p_id;
    this.amnt = amnt;
    this.lng = lng;
    this.prc = prc;
}

const default_layout = "default";

export default {
  computed: {},
  data() {
      return {
          items: [],
          materials: [],
          product: []
      }
  },
  methods: {
    async create() {
              // To do
    },
    async read() {
        //const { data } = window.axios.get('/api/cruds');
        //data.forEach(crud => this.cruds.push(new Crud(crud)));

        let tmp = [
    	    new i_helper(7,1,1,5, 1000, 10.0),
    	    new i_helper(8,2,2,3, 2000, 15.0),
    	    new i_helper(6,3,1,3, 2500, 17.0),
    	    new i_helper(9,3,3,6, 3000, 20.0)
        ];

	let prods = getProductsList();
	let mats = getMaterialsList();

	for (let i of tmp){
	    let prod = "";
	    let id = prods.findIndex(x => x.id === i.p_id);
	    if (typeof id != "undefined") { prod = prods[id].product; }
	    let mat = "";
	    id = mats.findIndex(x => x.id === i.m_id);
	    if (typeof id != "undefined") { mat = mats[id].material; }
	    this.items.push(new BaskItem(i.id, mat, prod, i.amnt, i.lng, i.prc));
	}
//        this.items.push(new BaskItem(7, 1, 1, 5, 1000, 10.0));
    },
    async update(id, color) {
    },

    async addItem() {
        this.items.push(new BaskItem(10,'RAL9999', 'Миталл', 7, 1440, 23.0));
    },

    async delItem(id) {
       this.items.splice(0,1);
    }
  },
  components: {
    BasketItem
  },
  created() {
     axios.get('/api/materials').then(response => {
         this.materials = response.data.data;
         console.log(this.materials);
     });
     axios.get('/api/products').then(response => {
         this.products = response.data.data;
         console.log(this.products);
     });
     //data.forEach(crud => this.cruds.push(new Crud(crud)));

    this.read();
  }
};

</script>

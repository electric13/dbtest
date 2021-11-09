<template>
    <div class="basket-item">
        <div class="panel panel-default">
            <h3>{{ product }} {{ material }}  {{ length}}мм</h3><br>
            Код строки: {{ id }}<br>
            Кол-во: {{ amount }}<br>
            <button @click="del">Удалить</button>
        </div>
    </div>
</template>

<script>
export default {

    name: "BasketItem",

    methods: {
        del() {
            this.$emit('del-item', this.id);
        }
    },

    props: ['id', 'material', 'product', 'length', 'amount', 'price'],
    
    computed: {
	_product: function() {
	    let prods = window.getProductsList();
	    let id = prods.findIndex(x => x.id === this.product_id);
	    if (typeof id != "undefined") { return prods[id].product; }
	       else { return ""; }
	},
	_material: function() {
	    let materials = window.getMaterialsList();
	    let id = materials.findIndex(x => x.id === this.material_id);
	    if (typeof id != "undefined") { return materials[id].material; } 
	       else { return ""; }
	},
	sum: function() {
	    return this.amount * this.price;
	}
    }
}
</script>

<style scoped>

</style>

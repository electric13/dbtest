<template>
    <div class="basket-item">
        <div class="panel panel-default">
            <h3>{{ product }} {{ material }}  {{ length/1000 }}м</h3><br>
            Код строки: {{ id }}<br>
            Кол-во: {{ amount }}<br>
            <button :disabled="bt_disabled" @click="del">Удалить</button>
        </div>
    </div>
</template>

<script>
export default {

    name: "BasketItem",

    methods: {
        del() {
            this.bt_disabled = true;
            this.$emit('del-item', this.id);
        }
    },

    props: [
        'parent',
        'id',
        'm_id',
        'p_id',
        'length',
        'amount',
        'price',
        'bt_disabled'],

    computed: {
	product: function() {
	    let id = this.parent.products.findIndex(x => x.id === this.p_id);
        if (typeof id != "undefined") { return this.parent.products[id].product; }
	       else { return ""; }
	},
	material: function() {
	    let id = this.parent.materials.findIndex(x => x.id === this.m_id);
	    if (typeof id != "undefined") {
            return this.parent.materials[id].material + ' ' +
                   this.parent.materials[id].thickness + 'мм'; }
	       else { return ""; }
	},
	sum: function() {
	    return this.amount * this.price;
	}
    },
    created() {
        this.bt_disabled = false;
    }

}
</script>

<style scoped>

</style>

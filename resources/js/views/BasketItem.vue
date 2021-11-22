<template>
    <tr>
        <td>{{ id }}</td>
        <td>
            {{ product }}&nbsp;
            <select v-model="sMaterial" v-on:change="changeMaterial(id)">
                <option
                    v-for="mat in materialList"
                    :value="mat.m"
                    :selected="mat.m === material()"
                >{{ mat.m }}
                </option>
            </select>&nbsp;
            <select v-bind="tList" v-model="material_id" v-on:change="changeThickness(id)">
                <option
                    v-for="thc in tList"
                    :value="thc.id"
                    :selected="thc.id === m_id"
                >{{ thc.thickness }}
                </option>
            </select>&nbsp;
        </td>
        <td>{{ length/1000 }}м</td>
        <td>{{ amount }}</td>
        <td><button :disabled="bt_disabled" @click="del">Удалить</button></td>
        <td colspan="5">{{ sMaterial + ' ' + thickness() + 'мм'}}</td>
    </tr>
</template>

<script>
export default {

    name: "BasketItem",

    methods: {
        del() {
            this.bt_disabled = true;
            this.$emit('del-item', this.id);
        },

        postData() {
            let req = {
                        "key": this.parent.basketID,
                        "id": this.id,
                        "material": this.material_id,
                        "product": this.p_id,
                        "amount": this.amount,
                        "item": this.i_id,
                        "length": this.length
                      }
            console.log('update line '+this.id);
            console.log(req);
            axios.post('/api/basket/update', req, {})
                    .then(() => { this.$emit('upd-item', this.id); })
                    .catch(() => { this.$emit('upd-item', this.id); });
        },

        changeThickness() {
            let idx = this.tList.findIndex(x => x.id === this.material_id);
            if (idx >= 0) {
                this.sThickness = this.tList[idx].thickness;
            } else {
                this.sThickness = 0;
            }
            this.postData();
        },

        changeMaterial() {
            this.tList = this.thicknessList(this.sMaterial);
            let idx = this.tList.findIndex(x => x.thickness === this.sThickness);
            if (idx >= 0) {
                // у нового материала есть такая же толщина
                this.material_id = this.tList[idx].id;
            } else {
                //у вновь выбранного материала нет такой же толщины, как у предыдушего
                this.material_id = this.tList[0].id;
                this.sThickness =  this.tList[0].thickness;
            }
            this.postData();
        },

        //Возвращает наименование материала в текстовом виде
        material: function() {
            let id = this.parent.materials.findIndex(x => x.id === this.m_id);
            if (id >= 0) {
                return this.parent.materials[id].material;
            }
            else { return ""; }
        },

        //Возвращает наименование толщины материала в текстовом виде
        thickness: function() {
            let id = this.parent.materials.findIndex(x => x.id === this.m_id);
            if (id >= 0) {
                return this.parent.materials[id].thickness; }
            else { return ""; }
        },

        //Возвращает массив возможных толщин для текущего материала
        thicknessList: function(mt){
            if (typeof mt == "undefined" ) {
                // если материал не задан явно, берем текущий
                mt = this.sMaterial;
            }
            let id = this.parent.linksMT.findIndex(x => x.m === mt);
            if (id >= 0) { return this.parent.linksMT[id].t; }
            else { return {};}
        },

    },

    props: [
        'parent',
        'id',
        'm_id',
        'p_id',
        'i_id',
        'length',
        'amount',
        'price'
    ],

    data() {
        return {
            'bt_disabled':  false,
            'sMaterial':    '',
            'sThickness':   0,
            'material_id':  0,
            'tList':        []
        }
    },

    computed: {
    //Возвращает наименование продукта в текстовом виде
	product: function() {
	    let id = this.parent.products.findIndex(x => x.id === this.p_id);
        if (typeof id != "undefined") { return this.parent.products[id].product; }
	       else { return ""; }
	},

    materialList: function(){
        return this.parent.linksMT;
    },

	sum: function() {
	    return this.amount * this.price;
	}
    }, // конец раздел computed

    created() {
        this.bt_disabled = false;
        this.sMaterial = this.material();
        this.sThickness = this.thickness();
        this.material_id = this.m_id;
        this.tList = this.thicknessList();
    }
}
</script>

<style scoped>

</style>

<template>
    <tr>
        <td>{{ id }}</td>
        <td>
            <select v-model="product_id">
                <option
                    v-for="prod in productList"
                    :value="prod.id"
                    :selected="prod.id === product_id"
                >{{ prod.product }}
                </option>
            </select>&nbsp;
            <span v-if="product_id != 10">
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
            </select>
            </span>
            <span v-if="product_id == 10">
            <select v-model="group_id" v-on:change="changeItemGroup">
                <option
                    v-for="gr in groupsList"
                    :value="gr.id"
                    :selected="gr.id === group_id"
                >{{ gr.groupname }}
                </option>
            </select>&nbsp;
            <select v-bind="grItems" v-model="item_id" v-on:change="changeItem">
                <option
                    v-for="nm in grItems"
                    :value="nm.id"
                    :selected="nm.id === item_id"
                >{{ nm.itemname }}
                </option>
            </select>&nbsp;
            </span>
        </td>
        <td>{{ length/1000 }}м</td>
        <td>{{ amount }}</td>
        <td><button :disabled="bt_disabled" @click="del">Удалить</button></td>
        <td colspan="5" v-if="product_id != 10">{{ sMaterial + ' ' + thickness() + 'мм'}}</td>
        <td colspan="5" v-if="product_id == 10">{{ group_id + ':' + item_id }}</td>
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
                        "product": this.product_id,
                        "amount": this.amount,
                        "item": this.item_id,
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

        changeItemGroup() {
            this.grItems = this.nomList()
            this.item_id = this.grItems[0].id
            this.changeItem();
        },

        changeItem() {
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

        //возвращает массив товаров для текущей группы
        nomList: function(){
            let currGroup = this.group_id;
            return this.parent.nom.filter( item => item.group_id == currGroup );
        },

    }, //методы

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
            'product_id':   0,
            'item_id':      0,
            'group_id':    0,
            'tList':        [],
            'grItems':      []
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

    groupsList: function(){
        return this.parent.n_groups.filter( () => true );
    },

    nom: function(){
        return this.parent.nom[this.item_id].itemname;
    },

    productList: function(){
        return this.parent.products;
    },

	sum: function() {
	    return this.amount * this.price;
	}
    }, // конец раздела computed

    created() {
        this.bt_disabled = false;
        this.product_id = this.p_id
        if (this.product_id != 10) {
            // для мерных товаров учитываем их параметры (длина, материал)
            this.sMaterial = this.material();
            this.sThickness = this.thickness();
            this.material_id = this.m_id;
            this.tList = this.thicknessList();
        } else {
            // для штучных товаров учитываем их параметры (артикул и группа)
            this.item_id = this.i_id;
            this.group_id = this.parent.nom[this.item_id].group_id;
            this.grItems = this.nomList()
        }
    }
}
</script>

<style scoped>

</style>

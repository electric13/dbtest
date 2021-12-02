<template>
    <b-tr>
        <b-td>
            <b-dropdown size="sm" :text="product" class="m-2 prod-list">
                <b-dropdown-item  v-for="prod in productList"
                                  :key="prod.id"
                                  @click="changeProduct(prod.id)"
                                  :active="prod.id === product_id">
                    {{ prod.product }}
                </b-dropdown-item>
            </b-dropdown>
            <span v-if="product_id !== 10">
            <b-dropdown size="sm" :text="sMaterial" class="m-0 p-0 mat-list">
                <b-dropdown-item  v-for="mat in materialList"
                                  :key="mat.m"
                                  @click="changeMaterial(mat.m)"
                                  :active="mat.m === sMaterial">
                    {{ mat.m }}
                </b-dropdown-item>
            </b-dropdown>
            <b-dropdown size="sm" :text="thickness()" class="m-2 p-0">
                <b-dropdown-item  v-for="thc in tList"
                                  :key="thc.id"
                                  @click="changeMaterialId(thc.id)"
                                  :selected="thc.id === material_id">
                    {{ thc.thickness }}
                </b-dropdown-item>
            </b-dropdown>

            <!--select v-bind="tList" v-model="material_id" v-on:change="changeThickness(id)">
                <option
                    v-for="thc in tList"
                    :value="thc.id"
                    :selected="thc.id === m_id"
                >{{ thc.thickness }}
                </option>
            </select-->&nbsp;
            <input type="text" v-on:keypress="onlyNumbers"
                               v-model="cLength"
                               v-on:keyup="changeLength"
                               v-on:change="changeLength"
                               @blur="checkDirty" />
            </span>
            <span v-if="product_id === 10">
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
        </b-td>
        <b-td>
            <b-container>
            <b-row align-v="center">
                <b-col cols="8">
                    <b-input-group size="sm" class="m-2" append="шт">
                        <b-form-input  @keypress="onlyNumbers"
                                       @keyup="changeAmount"
                                       v-model="cAmount"
                                       @change="changeAmount"
                                       @blur="checkDirty"
                        ></b-form-input>
                    </b-input-group>
                </b-col>
                <b-col cols="4">
                    <b-button variant="danger" :disabled="bt_disabled" size="sm" @click="del" class="m-2">
                        <icon name="trash"></icon>
                    </b-button>
                </b-col>
            </b-row>
            </b-container>
            <!--input type="text"
            /-->
        </b-td>
        <!--td colspan="5" v-if="product_id !== 10">{{ pLength/1000 + 'м, ' + sMaterial + ' ' + thickness() + 'мм'}}</td>
        <td colspan="5" v-if="product_id === 10">{{ group_id + ':' + item_id }}</td-->
    </b-tr>
</template>

<script>

function QueueItem(id, command, request, url, ){
    this.id = id
    this.request = request
    this.url = url
    this.command = command
}

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
                        "amount": this.pAmount,
                        "item": this.item_id,
                        "length": this.pLength
                      }

            this.postRequestInQueue(new QueueItem(this.id, 'update', req, '/api/basket/update'))
            /* axios.post('/api/basket/update', req, {})
                    .then(() => { this.$emit('upd-item', this.id); })
                    .catch(() => { this.$emit('upd-item', this.id); }) */
        },

        postRequestInQueue( reqObj ){
            let qID = -1
            qID = this.parent.requests.findIndex(x => x.id === this.id)
            this.parent.needUpd = true
            if (qID < 0 ) {
                // если других запросов для данной строки нет - помещаем запрос в очередь, на этом всё
                this.parent.requests.push( reqObj )
            } else {
                this.parent.requests[qID] = reqObj;
            }
        },

        checkDirty(){
            console.log(this.cAmount+' '+this.pAmount+' '+this.cLength +' '+this.pLength)
            if (this.cAmount !== this.pAmount) {
                this.cAmount = this.pAmount;
            }
            if (this.cLength !== this.pLength) {
                this.cLength = this.pLength
            }
        },

        changeProduct(newProductID){
            if (this.product_id === newProductID) {
                // вызывается при каждом щелчке по пункту списка, даже когда
                // он уже выбран
                return
            }
            this.product_id = newProductID
            if (this.product_id === 10 && this.item_id === 0) {
                // если меняем вид продукции с мерной на штучную, при этом
                // ранее не редактировали, то находим первый товар и
                // соответствующую ему группу
                this.item_id = Number(Object.keys(this.parent.nom)[0]);
                this.group_id = this.parent.nom[this.item_id].group_id;
                this.grItems = this.nomList()
            }
            if (this.product_id !== 10 && this.material_id === 0) {
                // если меняем штучный товар на мерную продукцию, и при этом
                // ранее не задавалось, на какую - то подставляем самый первый материал
                // из списка материалов.
                this.material_id = this.parent.materials[0].id;
                this.sMaterial = this.material();
                console.log(this.material_id+ ':' + this.sMaterial)
                this.tList = this.thicknessList();
                this.cLength = this.pLength = 1000;
            }
            this.postData()
        },

        changeMaterial(newMaterial) {
            if (newMaterial === this.sMaterial) {
                // если выбрали тот же самый материал, что был ранее
                return
            }
            this.sMaterial = newMaterial
            this.tList = this.thicknessList(this.sMaterial)
            let idx = this.tList.findIndex(x => x.thickness === this.sThickness)
            if (idx >= 0) {
                // у нового материала есть такая же толщина
                this.material_id = this.tList[idx].id
            } else {
                //у вновь выбранного материала нет такой же толщины, как у предыдушего
                this.material_id = this.tList[0].id
                this.sThickness =  this.tList[0].thickness
            }
            this.postData();
        },

        changeMaterialId(newMaterialId) {
            if (newMaterialId === this.material_id){
                // если выбрали тот же самый материал, что был ранее
                return
            }
            this.material_id = newMaterialId
            let idx = this.tList.findIndex(x => x.id === this.material_id)
            if (idx >= 0) {
                this.sThickness = this.tList[idx].thickness
            } else {
                this.sThickness = 0
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

        changeLength() {
            this.cLength = Number(this.cLength);
            if ( this.cLength >= 200 && this.cLength <= 8000 && this.pLength !== this.cLength) {
                this.pLength = this.cLength;
                this.postData();
            }
        },

        changeAmount() {
            this.cAmount = Number(this.cAmount);
            if ( this.cAmount > 0 && this.cAmount <= 99000 && this.pAmount !== this.cAmount) {
                this.pAmount = this.cAmount;
                this.postData();
            }
        },

        onlyNumbers(event){
            if (! this.digits.has(event.key)) {
                event.preventDefault();
            }
        },

        //Возвращает наименование материала в текстовом виде
        material: function() {
            let id = this.parent.materials.findIndex(x => x.id === this.material_id);
            if (id >= 0) {
                return this.parent.materials[id].material;
            }
            else { return ""; }
        },

        //Возвращает наименование толщины материала в текстовом виде
        thickness: function() {
            let id = this.parent.materials.findIndex(x => x.id === this.material_id);
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
            return this.parent.nom.filter( item => item.group_id === currGroup );
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
            'group_id':     0,
            'tList':        [],
            'grItems':      [],
            'cAmount':      0,  //текущее кол-во в поле ввода
            'cLength':      0,  //текущая длина в поле ввода
            'pAmount':      0,  //ранее сохраненное корректное количество
            'pLength':      0,  //ранее сохраненная корректная длина
            'digits':       {}
        }
    },

    computed: {
    //Возвращает наименование продукта в текстовом виде
	product: function() {
	    let id = this.parent.products.findIndex(x => x.id === this.product_id);
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
        this.cAmount = this.pAmount = this.amount
        this.cLength = this.pLength = this.length
        this.digits = new Set(['0','1','2','3','4','5','6','7','8','9'])
        if (this.product_id !== 10) {
            // для мерных товаров учитываем их параметры (длина, материал)
            this.material_id = this.m_id;
            this.sMaterial = this.material();
            this.sThickness = this.thickness();
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

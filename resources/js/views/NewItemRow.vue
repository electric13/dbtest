<template>
    <b-tr class="p-0 m-0" variant="success">
        <b-td>
            <b-container class="p-0 text-left">
                <b-row class="m-0" align-v="center">
                    <b-col cols="3" class="p-0 text-right">
                        <b-dropdown size="sm"
                                    :text="product"
                                    class="prod-list"
                                    variant="outline-dark">
                            <b-dropdown-item  v-for="prod in productList"
                                              :key="prod.id"
                                              @click="changeProduct(prod.id)"
                                              :active="prod.id === product_id">
                                {{ prod.product }}
                            </b-dropdown-item>
                        </b-dropdown>
                    </b-col>
                    <!-- раздел для мерного товара -->
                    <b-col v-if="product_id !== 10" cols="3" class="p-0 pl-1 text-left">
                        <b-dropdown size="sm" :text="sMaterial" class="mat-list" variant="outline-dark">
                            <b-dropdown-item  v-for="mat in materialList"
                                              :key="mat.m"
                                              @click="changeMaterial(mat.m)"
                                              :active="mat.m === sMaterial">
                                {{ mat.m }}
                            </b-dropdown-item>
                        </b-dropdown>
                        <b-dropdown size="sm" :text="thickness" class="thck-list" variant="outline-dark">
                            <b-dropdown-item  v-for="thc in tList"
                                              :key="thc.id"
                                              @click="changeMaterialId(thc.id)"
                                              :selected="thc.id === material_id">
                                {{ thc.thickness }}
                            </b-dropdown-item>
                        </b-dropdown>
                    </b-col>
                    <b-col v-if="product_id !== 10" cols="2" class="p-0 pl-1">
                        <b-input-group size="sm" append="мм">
                            <b-form-input  @keypress="onlyNumbers"
                                           @keyup="changeLength"
                                           v-model="cLength"
                                           @change="changeLength"
                                           @blur="checkDirty"
                                           class="text-right"/>
                        </b-input-group>
                    </b-col>
                    <b-col v-if="product_id !== 10" cols="4" class="p-0 pl-2">
                        /<b>{{ pLength/1000 + 'м' }}</b>
                    </b-col>
                    <!-- раздел для штучного товара -->
                    <b-col v-if="product_id === 10" cols="3" class="p-0 pl-1 text-left">
                        <b-dropdown size="sm" :text="groupName()" class="grp-list">
                            <b-dropdown-item  v-for="gr in groupsList"
                                              :key="gr.id"
                                              @click="changeItemGroup(gr.id)"
                                              :selected="gr.id === group_id">
                                {{ gr.groupname }}
                            </b-dropdown-item>
                        </b-dropdown>
                    </b-col>
                    <b-col v-if="product_id === 10" cols="6" class="p-0 pl-1">
                        <b-dropdown size="sm" :text="itemName()" class="item-list">
                            <b-dropdown-item  v-for="nm in grItems"
                                              :key="nm.id"
                                              @click="changeItem(nm.id)"
                                              :selected="nm.id === item_id">
                                {{ nm.itemname }}
                            </b-dropdown-item>
                        </b-dropdown>
                    </b-col>
                </b-row>
            </b-container>
        </b-td>
        <b-td>
            <b-container class="p-0 text-left">
                <b-row align-v="center">
                    <b-col cols="12" class="pl-5">
                        <b-input-group size="sm" class="m-0" append="шт">
                            <b-form-input  @keypress="onlyNumbers"
                                           @keyup="changeAmount"
                                           v-model="cAmount"
                                           @change="changeAmount"
                                           @blur="checkDirty"
                                           class="text-right"
                            ></b-form-input>
                        </b-input-group>
                    </b-col>
                </b-row>
            </b-container>
        </b-td>
        <b-td>
            <b-container class="p-0 text-left">
                <b-row align-v="center" class="p-0 m-0">
                    <b-col cols="9" class="p-0 text-norm">
                        <icon name="ruble-sign"/>&nbsp;{{ sum }}
                    </b-col>
                    <b-col cols="3" class="text-left p-0">
                        <b-button variant="success"
                                  :disabled="bt_disabled"
                                  size="sm"
                                  @click="postData"
                                  class="m-0">
                            <icon name="plus"/>
                        </b-button>
                    </b-col>
                </b-row>
            </b-container>
        </b-td>
    </b-tr>
</template>

<script>

export default {
    name: "NewItemRow",
    props: [
        'parent'
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

    methods: {
        postData() {
            let req = {
                "key": this.parent.basketID,
                "material": this.material_id,
                "product": this.product_id,
                "amount": this.pAmount,
                "item": this.item_id,
                "length": this.pLength
            }

            this.bt_disabled = true;
            axios.post('/api/basket/add', req, {})
                    .then(() => {
                        this.bt_disabled = false
                        this.$emit('add-item' )
                    })
                    .catch(() => { this.$emit('error' ) })
        },

        postRequestInQueue( reqObj ){
            this.parent.requests.push( reqObj )
        },

        //Возвращает массив возможных толщин для текущего материала
        thicknessList(mt){
            if (typeof mt == "undefined" ) {
                // если материал не задан явно, берем текущий
                mt = this.sMaterial;
            }
            let id = this.parent.linksMT.findIndex(x => x.m === mt);
            if (id >= 0) { return this.parent.linksMT[id].t; }
            else { return {};}
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
                this.sMaterial = this.parent.materials[0].material;
                console.log(this.material_id+ ':' + this.sMaterial)
                this.tList = this.thicknessList();
                this.cLength = this.pLength = 1000;
            }
            //this.requestPrice()
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
            //this.requestPrice();
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
            //this.requestPrice();
        },

        changeItemGroup(newGroupId) {
            if (newGroupId === this.group_id) {
                return
            }
            console.log()
            this.group_id = newGroupId
            this.grItems = this.nomList()
            this.changeItem(this.grItems[0].id)
        },

        changeItem(newItemId) {
            if (newItemId === this.item_id) {
                return
            }
            this.item_id = newItemId
            //this.requestPrice()
        },

        changeLength() {
            this.cLength = Number(this.cLength);
            if ( this.cLength >= 200 && this.cLength <= 8000 && this.pLength !== this.cLength) {
                this.pLength = this.cLength;
                //this.requestPrice();
            }
        },

        changeAmount() {
            this.cAmount = Number(this.cAmount);
            if ( this.cAmount > 0 && this.cAmount <= 99000 && this.pAmount !== this.cAmount) {
                this.pAmount = this.cAmount;
                //this.requestPrice();
            }
        },

        onlyNumbers(event){
            if (! this.digits.has(event.key)) {
                event.preventDefault();
            }
        },
    },

    computed: {
        //Возвращает наименование продукта в текстовом виде
        product() {
            if (typeof this.parent == "undefined") {
                return ""
            }
            let id = this.parent.products.findIndex(x => x.id === this.product_id);
            if (typeof id != "undefined") { return this.parent.products[id].product; }
            else { return ""; }
        },

        materialList() {
            if (typeof this.parent == "undefined") {
                return this.parent
            }
            return this.parent.linksMT;
        },

        groupsList() {
            if (typeof this.parent == "undefined") {
                return this.parent
            }
            return this.parent.n_groups.filter( () => true );
        },

        nom() {
            if (typeof this.parent == "undefined") {
                return ""
            }
            return this.parent.nom[this.item_id].itemname;
        },

        productList() {
            if (typeof this.parent == "undefined") {
                return this.parent
            }
            return this.parent.products;
        },
        //возвращает название группы штучного товара
        groupName(){
            return this.parent.n_groups[this.group_id].groupname;
        },

        //возвращает название выбранного штучного товара
        itemName(){
            return this.parent.nom[this.item_id].itemname;
        },

        //Возвращает наименование толщины материала в текстовом виде
        thickness() {
            let id = this.parent.materials.findIndex(x => x.id === this.material_id);
            if (id >= 0) {
                return this.parent.materials[id].thickness;
            } else {
                return "";
            }
        },

        //возвращает массив товаров для текущей группы
        nomList: function(){
            let currGroup = this.group_id;
            return this.parent.nom.filter( item => item.group_id === currGroup );
        },

        sum() {
            if (typeof this.parent == "undefined") {
                return 0
            }
            return this.pAmount * this.price;
        }
    }, // конец раздела computed

    created() {
        this.bt_disabled = false;
        this.product_id = this.parent.products[0].id
        this.cAmount = this.pAmount = 1;
        this.cLength = this.pLength = 1000;
        this.digits = new Set(['0','1','2','3','4','5','6','7','8','9'])

        this.material_id = this.parent.materials[0].id
        this.sMaterial = this.parent.materials[0].material
        this.sThickness = this.parent.materials[0].thickness
        this.tList = this.thicknessList()
    }

}
</script>

<style scoped>

</style>

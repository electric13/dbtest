<template>
    <nav>
        <ul>
            <li @click="click"><a href="#">{{ title }}<div id="down-triangle"></div></a>
                <ul v-bind:class="{'cl-display-none': !isOpened, 'cl-display-block': isOpened}">
                    <li v-for="item in listItems" :key="item.id"><a href="#">{{ item.name }}</a></li>
                </ul>
            </li>
        </ul>
    </nav>
</template>

<script>
export default {
    name: "ComboBox",

    props: [
        'title',
        'listItems',
        'index'
    ],

    methods: {
        click(){
            this.opened = !this.opened
        }
    },

    data() {
        return {
            'opened': false
        }
    },

    computed: {
        isOpened() {
            return this.opened
        }
    },

    created() {

    }
 }
</script>

<style scoped>

.cl-display-block {
    display: block;
}

.cl-display-none {
    display: none;
}

nav {
    margin: 1px auto;
}

nav ul {
    margin-left: 0;
    padding-left: 0;
    width: 200px;
    list-style: none;
    position: relative;
    display: inline-table;
}

nav ul ul {
/*    display: none; */
}

nav ul li:hover > ul {
/*    display: block; */
}

nav ul li {
    float: left;
    width: 200px;
    text-align: left;
    border-bottom: 3px solid #2ecc71;
}

nav ul li:hover {
    background: #f2f2f2;
}

nav ul li a {
    display: block;
    padding: 5px 1px;
    color: #2a2a2a;
    text-decoration: none;
}

nav ul ul {
    border-radius: 0;
    padding: 0;
    position: absolute;
    top: 100%;
}

nav ul ul li {
    float: none;
    border-bottom: 2px solid #eaeaea;
    position: relative;
}

nav ul ul li a {
    padding: 5px 10px;
    background: #fff;
/*    color: #838383; */
    color: #000;
}

nav ul ul li a:hover {
    background: #47d583;
    color: #fff;
}

#down-triangle {
    float: right;
    width: 0;
    height: 0;
    margin-top: 5px;
    border-top: 10px solid #2ecc71;
    border-left: 10px solid transparent;
    border-right: 10px solid transparent;
}

</style>

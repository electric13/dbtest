<template>
    <div class="basket-item">
        <div class="panel panel-default">
            <h3>{{ b_item }}</h3><br>
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
            this.$emit('delete', this.id);
        }
    },
    props: ['id', 'b_item', 'amount'],
}
</script>

<style scoped>

</style>

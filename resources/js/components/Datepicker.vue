<template>
    <div>
        <input :name="name" type="text" class="form-control flatpickr" :value="value">
    </div>
</template>

<script>
    export default {
        props: ['inputValue', 'inputName'],
        data() {
            return {
                value: this.inputValue,
                name: this.inputName
            }
        },
        watch: {
            value: function (val) {
                console.log('time', val)
                this.$emit('input', {name: this.name, value: val})
            }
        },
        methods: {

        },
        mounted() {
            flatpickr(".flatpickr", {
                altInput: true,
                altFormat: "d/m/Y",
                dateFormat: "d/m/Y",
                locale: "vn"
            });
        }
    }
</script>

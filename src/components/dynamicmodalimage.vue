<template>
    <div class="modal-image">
        <div class="modal is-active">
            <div class="modal-background"></div>
            <div class="modal-content">
                <p class="image">
                    <img :src="dep.imageUrl" :alt="dep.imageAlt">
                </p>
            </div>
            <button class="modal-close is-large" aria-label="close" @click="emitClose"></button>
        </div>
    </div>
</template>

<script>
export default {
    created(){},
    data: function() {
        return {}
    },
    components: {},
    props: {
        dep: {
            type: Object,
            default: function() {
                return {
                    imageUrl: '',
                    imageAlt: ''
                }
            }
        }
    },
    methods: {
        emitClose() {
            this.$emit('toggle-close')
        }
    }
};
</script>
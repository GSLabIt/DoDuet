<template>
    <fieldset class="rate">
        <template v-for="(elem, i) of Array.from({length: size}, scale)" :key="i">
            <input type="radio" :id="`${id}-rating-${i}`" :name="`${id}-rating`" :value="elem" v-model="vote"/>
            <label :for="`${id}-rating-${i}`" :title="`${elem} stars`" :class="{'half': (elem | 0) !== elem}"></label>
        </template>
    </fieldset>
</template>

<script>
export default {
    name: "StarRating",
    props: {
        modelValue: Number,
        min: {
            type: Number,
            default: .5
        },
        max: {
            type: Number,
            default: 5
        },
        id: {
            type: String,
            required: true,
        }
    },
    emits: ["update:modelValue"],
    data() {
        return {
            vote: this.modelValue
        }
    },
    methods: {
        scale(_, index) {
            return (this.size - (index)) * this.min
        }
    },
    computed: {
        size() {
            return (this.max / this.min) | 0
        }
    },
    watch: {
        vote(n) {
            this.$emit("update:modelValue", n)
        }
    }
}
</script>

<style scoped>
.rate {
    display: inline-block;
    border: 0;
}
/* Hide radio */
.rate > input {
    display: none;
}
/* Order correctly by floating highest to the right */
.rate > label {
    float: right;
}
/* The star of the show */
.rate > label:before {
    display: inline-block;
    font-size: 1.5rem;
    padding: .3rem .2rem;
    margin: 0;
    cursor: pointer;
    font-family: "FontAwesome" !important;
    content: "\f005 "; /* full star */
}
/* Zero stars rating */
/*.rate > label:last-child:before {
    content: "\f006 "; /* empty star outline */
/*}*/
/* Half star trick */
.rate .half:before {
    content: "\f089 "; /* half star no outline */
    position: absolute;
    padding-right: 0;
}
/* Click + hover color */
input:checked ~ label, /* color current and previous stars on checked */
label:hover, label:hover ~ label { color: #FBBF24;  } /* color previous stars on hover */

/* Hover highlights */
input:checked + label:hover, input:checked ~ label:hover, /* highlight current and previous stars */
input:checked ~ label:hover ~ label, /* highlight previous selected stars for new rating */
label:hover ~ input:checked ~ label /* highlight previous selected stars */ { color: #FDE047;  }
</style>

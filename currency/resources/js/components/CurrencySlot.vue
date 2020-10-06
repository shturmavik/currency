<template>
    <div class="currency-form__action__selection ui fluid search selection dropdown">
        <div
            v-on-clickaway="away"
            class="currency-form__action__value default text"
            @click="toggle"
            v-html="html" />
        <div
            v-show="opened"
            class="menu">
            <label
                v-for="(currency, index) in currencies"
                :key="currency.name"
                :for="`${newIndexExchange}_${index}_${currency.name}`"
                :class="(newIndexExchange === index ? 'item_disabled ':'') + 'item'">
                <input
                    :id="`${newIndexExchange}_${index}_${currency.name}`"
                    v-model="selected"
                    :disabled="newIndexExchange === index"
                    :value="index"
                    type="radio"
                    hidden>
                <i :class="currency.flag + ' flag'" />
                {{ currency.name }}
            </label>
        </div>
    </div>
</template>

<script>
import {directive as onClickaway} from 'vue-clickaway';

export default {
    name: 'CurrencySlot',
    directives: {
        onClickaway
    },
    props: {
        currencies: {
            type: Array,
            required: true,
            default: function() {
                return [];
            }
        },
        newIndexExchange: {
            type: Number,
            required: true,
            default: function() {
                return 0;
            }
        },
        checkedIndex: {
            type: Number,
            required: true,
            default: function() {
                return 1;
            }
        }
    },
    data: function() {
        return {
            opened: false,
            selected: ''
        };
    },
    computed: {
        html() {
            return `<i class="${this.currencies[this.newIndexExchange].flag} flag"></i>${this.currencies[this.newIndexExchange].name}`;
        }
    },
    watch: {
        newIndexExchange() {
            this.selected = this.newIndexExchange;
        },
        selected() {
            if (this.selected === this.checkedIndex) {
                this.$emit('reverseCurrency');
                return;
            }
            this.$emit('update:newIndexExchange', this.selected);
        }
    },
    methods: {
        toggle() {
            this.opened = !this.opened;
        },
        away() {
            this.opened = false;
        }
    }
};
</script>

<style scoped>

</style>

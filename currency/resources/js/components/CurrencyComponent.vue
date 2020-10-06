<template>
    <div class="flex-center position-ref full-height">
        <form
            class="currency-form ui form"
            @submit.prevent>
            <h1
                class="ui header"
                v-text="'Валютный калькулятор'" />
            <div class="currency-form__container">
                <div class="">
                    <div class="currency-form__action ui">
                        <div class="field">
                            <label v-text="'Обменять'" />
                            <div class="field">
                                <input
                                    v-model.number="total"
                                    type="number"
                                    placeholder="Сумма">
                            </div>
                        </div>
                        <div class="two fields">
                            <div class="field">
                                <label v-text="'Из'" />
                                <currency-slot
                                    :currencies="currencies"
                                    :checked-index="arrIndexExchange[1]"
                                    :new-index-exchange.sync="arrIndexExchange[0]"
                                    @reverseCurrency="reverseCurrency" />
                            </div>
                            <div class="field">
                                <label>&nbsp;</label>
                                <button
                                    class="basic circular ui icon button"
                                    @click="reverseCurrency">
                                    <i class="exchange alternate icon" />
                                </button>
                            </div>
                            <div class="field">
                                <label v-text="'В'" />
                                <currency-slot
                                    :currencies="currencies"
                                    :checked-index="arrIndexExchange[0]"
                                    :new-index-exchange.sync="arrIndexExchange[1]"
                                    @reverseCurrency="reverseCurrency" />
                            </div>
                        </div>
                    </div>
                </div>
                <div class="currency-form__result">
                    <div class="currency-form__result__inner">
                        <div
                            class="currency-form__result-title"
                            v-text="'Вы получите'" />
                        <div
                            class="currency-form__result-total"
                            v-html="total_exchange" />
                        <div
                            class="currency-form__result-change"
                            v-html="total_currency" />
                    </div>
                </div>
            </div>
        </form>
    </div>
</template>

<script>
import CurrencySlot from './CurrencySlot';

export default {
    components: {
        CurrencySlot
    },
    props: {
        hostname: {
            type: String,
            default: 'https://localhost'
        }
    },
    data: function() {
        return {
            arrIndexExchange: [0, 1],
            total: null,
            currencies: [
                {
                    name: 'EUR',
                    ISO: '€',
                    flag: 'af',
                    course: {
                        'RUB': 89.04,
                        'USD': 1.18
                    }
                },
                {
                    name: 'USD',
                    ISO: '$',
                    flag: 'ax',
                    course: {
                        'RUB': 75.18,
                        'EUR': 0.84
                    }
                },
                {
                    name: 'RUB',
                    ISO: '₽',
                    flag: 'al',
                    course: {
                        'USD': 0.013,
                        'EUR': 0.011
                    }
                }
            ]
        };
    },
    computed: {
        total_exchange() {
            return `<span>${(this.total * this.getCourseCurrency()).toLocaleString(
                'ru-RU')}</span> ${this.getCurrency()['ISO']}`;
        },
        total_currency() {
            return `1${this.getCurrency('from')['ISO']} = ${this.getCourseCurrency()} ${this.getCurrency()['ISO']}`;
        }
    },
    methods: {
        reverseCurrency() {
            this.arrIndexExchange.reverse();
        },
        getCurrency(value) {
            let index = value === 'from' ? 0 : 1;
            return this.currencies[this.arrIndexExchange[index]];
        },
        getCourseCurrency() {
            return this.getCurrency('from').course[this.getCurrency().name];
        }
    },
    mounted() {
        fetch(`${this.hostname}/api/currencies?q=eurrub,eurusd,usdeur,usdrub,rubusd,rubeur`).
            then(response => response.json()).
            then(jsondata => {
                this.currencies.map(element => {
                    return element.course = jsondata[element.name];
                });
            });
    }
};
</script>

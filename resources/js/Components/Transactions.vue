<template>
    <Dialog v-model:visible="visibleSync" modal class="p-fluid" :closable="false" :breakpoints="{ '1199px': '75vw', '575px': '90vw' }" style="width: 70%;">
        <template #header>
            <div class="flex justify-between items-center w-full">
                <h1 class="text-lg font-semibold m-0">Transaction History</h1>
            </div>
            <Button icon="pi pi-times" label="Close" class="p-button p-button-danger transactin-btn" @click="close" />
        </template>
        <div v-if="transactions && transactions.length === 0" class="text-center py-4 w-full">
            <p>No transactions found.</p>
        </div>
        <table v-else class="table-auto w-full border-separate border-gray-300">
            <thead class="text-white">
                <tr>
                    <th class="text-center px-4 py-2">Date</th>
                    <th class="text-center px-4 py-2">Type</th>
                    <th class="text-center px-4 py-2">Transaction Id</th>
                    <th class="text-center px-4 py-2">Note</th>
                    <th class="text-center px-4 py-2">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="transaction in transactions" :key="transaction.id">
                    <td class="text-left px-4 py-2">{{ formatDate(transaction.created_at) }}</td>
                    <td class="text-left px-4 py-2 text-capitalize">{{ transaction.type }}</td>
                    <td class="text-left px-4 py-2">{{ transaction.uuid }}</td>
                    <td class="text-left px-4 py-2">{{ transaction.meta?.description || '' }}</td>
                    <td class="text-right px-4 py-2">{{ formatAmount(transaction.amount) }}</td>
                </tr>
            </tbody>
        </table>
    </Dialog>
</template>

<script>
import { defineComponent } from 'vue';
import Dialog from 'primevue/dialog';
import Button from 'primevue/button';
import Tooltip from 'primevue/tooltip';

export default defineComponent({
    name: 'Transaction',
    components: {
        Dialog,
        Button
    },
    directives: {
        tooltip: Tooltip
    },
    props: {
        visible: {
            type: Boolean,
            required: true
        },
        transactions: {
            type: Array,
            default: () => []
        }
    },
    emits: ['update:visible'],
    computed: {
        visibleSync: {
            get() {
                return this.visible;
            },
            set(value) {
                this.$emit('update:visible', value);
            }
        }
    },
    methods: {
        close() {
            this.$emit('update:visible', false);
        },
        formatAmount(amount) {
            const actualAmount = amount / 100;
            return new Intl.NumberFormat('en-US', {
                style: 'currency',
                currency: 'INR',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            }).format(actualAmount);
        },
        formatDate(date) {
            const options = { year: 'numeric', month: 'short', day: '2-digit' };
            return new Date(date).toLocaleDateString('en-US', options);
        }
    },
    watch: {
        transactions(newValue) {
            console.log("Transactions received in modal:", newValue);
        }
    }
});
</script>

<style scoped>
.table-auto {
    border-collapse: collapse;
    width: 100%;
}
table thead tr {
    background-color: gray;
}
table tbody tr td {
    border: 1px solid #ddd;
    padding: 5px;
}
.text-center {
    text-align: center;
}
.text-left {
    text-align: left;
}
.text-right {
    text-align: right;
}
.text-capitalize {
    text-transform: capitalize;
}
.transactin-btn {
    width: 10%;
}
</style>

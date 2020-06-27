<template>
<div>
    <h1>orders</h1>
    <table v-if="orders.length > 0" class="table">
        <thead class="thead-dark">
            <tr>
                <th scope="col">#</th>
                <th scope="col">Trade</th>
                <th scope="col">Entry</th>
                <th scope="col">Exit</th>
                <th scope="col">Points</th>
                <th scope="col">PNL</th>
            </tr>
        </thead>
            <tbody>
                <tr class="table-info" v-for="(order,index) in orders" v-bind:key="order.id" >
                    <th scope="row">{{ index + 1 }}</th>
                    <td class="text-capitalize font-weight-bold font-italic" >{{ order.position }}</td>                    
                    <td v-if="order.position == 'long'" ><span>{{ order.bprice }}</span> <span class="d-block font-italic small">{{ order.created_at | formatDate }}</span></td>                    
                    <td v-else ><span>{{ order.sprice }}</span> <span class="d-block font-italic small">{{ order.created_at | formatDate }}</span></td>                    
                    <td v-if="order.position == 'long'" ><span>{{ order.sprice }}</span> <span class="d-block font-italic small">{{ order.updated_at | formatDate }}</span></td>                    
                    <td v-else ><span>{{ order.bprice }}</span> <span class="d-block font-italic small">{{ order.updated_at | formatDate }}</span></td>                    
                    <td>{{ order.sprice - order.bprice | formatDecimal  }}</td>                    
                    <td>{{ (order.sprice - order.bprice) * 20 | formatDecimal }}</td>                    
                </tr>
                <tr class="table-info">
                    <th scope="row"></th>
                    <td></td>
                    <td></td>
                    <td>Total</td>
                    <td></td>
                    <td></td>
                </tr>
            </tbody>
        </table>
</div>
</template>

<script>
export default {
    data(){
        return {
            orders: []
        }
    },
    created() {
        this.fetchOrders();
    },
    methods: {
        fetchOrders() {
            fetch('/api/order')
                .then(res =>res.json())
                .then(resjson => {
                    this.orders = resjson.data
                })
        }
    }
}
</script>
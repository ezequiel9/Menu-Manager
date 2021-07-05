<template>
    <div class="row">
        <div class="col-8">
            <card :title="$t('Search Menu')">
                <div class="row">

                    <div class="col-8">
                        <div class="input-group mb-3">
                            <multiselect
                                v-model="user"
                                :options="users"
                                :custom-label="customLabelUser"
                                placeholder="Select resident"
                                label="name"
                                track-by="id"
                            ></multiselect>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="input-group mb-3">
                            <multiselect
                                v-model="date"
                                :options="dates"
                                :custom-label="customLabelDate"
                                label="label"
                                track-by="week_number"
                                placeholder="Select week range"
                            ></multiselect>
                        </div>
                    </div>

                    <div class="col-9">
                        <div class="input-group mb-3">
                            <input
                                type="text"
                                class="form-control"
                                placeholder="Start typing to find a menu"
                                aria-label="Start typing a menu"
                                aria-describedby="start-typing-a-menu"
                                v-model="search_query"
                                @input="search"
                            >
                        </div>
                    </div>
                    <div class="col-3">
                        <multiselect
                            v-model="day"
                            :options="week_days"
                            placeholder="Select day"
                        ></multiselect>
                    </div>

                </div>

                <div class="results">
                    <table class="table">
                        <thead>
                            <tr>
                                <th class="title">Name</th>
                                <th class="type">Type</th>
                                <th class="actions">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <template v-for="(menu, index) in menus">
                                <tr style="background: #f5f5f5;">
                                    <td class="title"><strong>{{menu.name}}</strong></td>
                                    <td class="type"><strong>{{menu.menu_type.name}}</strong></td>
                                    <td class="actions">
                                        <div>
                                            <button @click="order(menu.id, null)" :disabled="!canOrder"  type="button" class="btn btn-sm btn-dark">Order</button>
                                            <button @click="deleteMenu(menu.id)" class="btn btn-sm btn-danger" type="button">Delete</button>
                                        </div>
                                    </td>
                                </tr>

                                <template v-if="menu.menu_variations.length" v-for="menu_variation in menu.menu_variations">
                                    <tr>
                                        <td colspan="2">- Variation: {{menu_variation.details}}</td>
                                        <td class="actions">
                                            <div>
                                                <button @click="order(menu.id, menu_variation.id)" :disabled="!canOrder" type="button" class="btn btn-sm btn-dark">Order</button>
                                                <button @click="deleteMenuVariation(menu.id, menu_variation.id)" class="btn btn-sm btn-danger" type="button">Delete</button>
                                            </div>
                                        </td>
                                    </tr>
                                </template>

                                <tr>
                                    <td colspan="3">
                                        <div class="input-group mb-3">
                                            <input
                                                type="text"
                                                class="form-control"
                                                placeholder="Add menu variation"
                                                aria-label="Add menu variation"
                                                aria-describedby="start-typing-a-variation"
                                                v-on:keyup.enter="addVariation($event, menu.id)"
                                            >
                                        </div>
                                    </td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

            </card>
        </div>
        <div class="col-4">
            <card v-if="user && date" :title="$t('Orders for current week')">
                <h4>{{user.name}} | Room {{user.room_number}}</h4>
                <div class="days">
                    <div class="day" v-for="day in date.week_days">
                        <div><strong>{{day}}</strong></div>
                        <template v-if="orders.filter((order) => { return order.week_day === day && order.user_id === user.id}).length">
                            <div v-for="day_order in orders.filter((order) => { return order.week_day === day && order.user_id === user.id })">
                                <span @click="deleteOrder(day_order.id)" class="text-danger"><fa icon="trash-alt" fixed-width/></span>
                                {{day_order.menu.menu_type.name}} - {{day_order.menu.name}}
                                <span v-if="day_order.menu_variation">, {{day_order.menu_variation.details}}</span>
                            </div>
                        </template>
                        <template v-else>
                            <i>- No menu selected</i>
                        </template>
                    </div>
                </div>
            </card>
            <card v-if="date" class="mt-3" :title="$t('Print')">
                <div class="btn-group w-100 mt-5" role="group" aria-label="Basic example">
                    <button @click="printForRooms()" type="button" class="btn btn-secondary">Print for Rooms</button>
                    <button @click="printForChef()" type="button" class="btn btn-light">Print for Chef</button>
                </div>
            </card>
            <card class="mt-3" :title="$t('Add Resident')">
                <div class="input-group mb-3">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Resident Name"
                        aria-label="Resident Name"
                        aria-describedby="resident-name"
                        v-model="add_resident.name"
                    >
                </div>
                <div class="input-group mb-3">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Resident Email"
                        aria-label="Resident Email"
                        aria-describedby="resident-email"
                        v-model="add_resident.email"
                    >
                </div>
                <div class="input-group mb-3">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Phone Number"
                        aria-label="Phone Number"
                        aria-describedby="phone-number"
                        v-model="add_resident.phone"
                    >
                </div>
                <div class="input-group mb-3">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Room Number"
                        aria-label="Room Number"
                        aria-describedby="room-number"
                        v-model="add_resident.room_number"
                    >
                </div>
                <div class="input-group mb-3">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Meal Size"
                        aria-label="Meal Size"
                        aria-describedby="meal-size"
                        v-model="add_resident.meal_size"
                    >
                </div>
                <button class="btn btn-success" @click="addResident()" type="button">Add Resident</button>
            </card>
            <card class="mt-3" :title="$t('Add Menu')">
                <div class="input-group mb-3">
                    <input
                        type="text"
                        class="form-control"
                        placeholder="Menu"
                        aria-label="Menu"
                        aria-describedby="menu"
                        v-model="add_menu.name"
                    >
                </div>
                <div class="input-group mb-3">
                    <select class="custom-select" v-model="add_menu.menu_type_id">
                        <option selected>Select Menu Type</option>
                        <template v-for="menu_type in menu_types">
                            <option :value="menu_type.id">{{menu_type.name}}</option>
                        </template>
                    </select>
                </div>
                <button class="btn btn-success" @click="addMenu()" type="button">Add Menu</button>
            </card>
        </div>
    </div>
</template>

<script>
    import axios from 'axios'
    import Multiselect from 'vue-multiselect'
    import Swal from 'sweetalert2'

    export default {
        middleware: 'auth',

        components: {
            Multiselect
        },

        mounted () {
            this.getUsers();
            this.getDates();
            this.getMenuTypes();
        },

        data() {
            return {
                search_query: '',
                variation: null,
                search_results: null,
                menus: [],
                user: null,
                users: [],
                date: null,
                dates: [],
                week_days: [
                    'Monday',
                    'Tuesday',
                    'Wednesday',
                    'Thursday',
                    'Friday',
                    'Saturday',
                    'Sunday',
                ],
                day: null,
                orders: [],
                menu_types: [],
                add_resident: {
                    name: null,
                    phone: null,
                    room_number: null,
                    email: null,
                    meal_size: null
                },
                add_menu: {
                    name: null,
                    menu_type_id: null
                }
            }
        },

        computed: {
            canOrder() {
                return this.user && this.date && this.day
            },
        },

        methods: {
            async getMenuTypes () {
                try {
                    const {data} = await axios.get('/api/menu-types')
                    this.menu_types = data
                } catch (e) {

                }

            },
            async getUsers () {
                try {
                    const {data} = await axios.get('/api/users')
                    this.users = data
                } catch (e) {

                }
            },

            async getDates () {
                try {
                    const {data} = await axios.get('/api/dates')
                    this.dates = data
                } catch (e) {

                }
            },

            search () {
                try {
                    this.$nextTick(async () => {
                        const {data} = await axios.post('/api/search', {search_query: this.search_query})
                        this.search_results = data
                        this.menus = data.menus
                    })
                } catch (e) {

                }
            },

            customLabelUser (user) {
                return `${user.name} | Room ${user.room_number}`
            },

            customLabelDate (date) {
                return `${date.label}`
            },

            async order(menu_id, menu_variation_id) {
                const order_data = {
                    'menu_id': menu_id,
                    'menu_variation_id': menu_variation_id,
                    'user_id': this.user.id,
                    'week_number': this.date.week_number,
                    'week_day': this.day,

                }

                try {
                    const {data} = await axios.post('/api/orders', order_data)
                    this.orders = data.orders
                } catch (e) {
                    console.error(e)
                }

            },

            async getOrders () {
                if (this.user && this.date) {
                    try {
                        const {data} = await axios.get(`/api/orders/user/${this.user.id}/week_number/${this.date.week_number}`)
                        this.orders = data.orders
                    } catch (e) {

                    }
                }
            },

            async addVariation ($event, menu_id) {
                try {
                    const menu_variation_data = {
                        menu_id: menu_id,
                        details: $event.target.value
                    }
                    const {data} = await axios.post('/api/menu/add-variation', menu_variation_data)
                    this.search()
                    $event.target.value = '';
                } catch (e) {

                }
            },

            deleteOrder (order_id) {
                try {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then( async (result) => {
                        if (result.isConfirmed) {
                            await axios.delete(`/api/orders/${order_id}`)
                            await Swal.fire(
                                'Deleted!',
                                'The order has been deleted.',
                                'success'
                            )
                            await this.getOrders()
                        }
                    })
                } catch (e) {

                }
            },

            deleteMenu (menu_id) {
                try {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!. This action will remove all the variations containing the menu and its orders.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then( async (result) => {
                        if (result.isConfirmed) {
                            await axios.delete(`/api/menu/${menu_id}`)
                            this.search()
                            await Swal.fire(
                                'Deleted!',
                                'The menu has been deleted.',
                                'success'
                            )
                        }
                    })
                } catch (e) {

                }
            },

            async deleteMenuVariation (menu_id, menu_variation_id) {
                try {
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!. This action will remove all the orders attached to this menu variation.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then( async (result) => {
                        if (result.isConfirmed) {
                            await axios.delete(`/api/menu/${menu_id}/variation/${menu_variation_id}`)
                            this.search()
                            await Swal.fire(
                                'Deleted!',
                                'The menu variation has been deleted.',
                                'success'
                            )
                        }
                    })
                } catch (e) {

                }
            },

            async addMenu () {
                try {
                    if (!this.add_menu.name || !this.add_menu.menu_type_id) {
                        await Swal.fire({
                            title: 'Ups, you are missing some data',
                            text: "Please fill all the fields to can submit this form!.",
                            icon: 'warning',
                            showCancelButton: false,
                            confirmButtonColor: '#3085d6',
                            confirmButtonText: 'Got it'
                        })
                    } else {
                        await axios.post(`/api/menu`, this.add_menu)
                        await Swal.fire(
                            'New Menu added!',
                            '',
                            'success'
                        )
                        this.search()
                    }
                } catch (e) {

                }
            },

            async addResident () {
                if (
                    !this.add_resident.name
                    || !this.add_resident.room_number
                    || !this.add_resident.meal_size
                ) {
                    await Swal.fire({
                        title: 'Ups, you are missing some data',
                        text: "Please fill all the fields to can submit this form!.",
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonColor: '#3085d6',
                        confirmButtonText: 'Got it'
                    })
                } else {
                    try {
                        console.log(this.add_resident)
                        await axios.post(`/api/add-user`, this.add_resident)
                        await Swal.fire(
                            'New resident added!',
                            '',
                            'success'
                        )
                        this.getUsers()
                    } catch (e) {
                        console.error(e)
                    }
                }
            },

            printForRooms () {},
            printForChef () {},
        },

        watch: {
            user() {
                this.getOrders()
            },
            date() {
                this.getOrders()
            }
        }
    }
</script>

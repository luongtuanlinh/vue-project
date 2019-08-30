<style>
    th, td{
        text-align: center;
    }
</style>
<template>
    <div>
        <div class="content-header">
            <h1>
                Đặt vé tập
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Đặt vé tập</li>
            </ol>
        </div>

        <section class="content">

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <el-steps :active="active" align-center>
                                <el-step title="Chọn vé" description=""></el-step>
                                <el-step title="Thanh toán" description=""></el-step>
                                <el-step title="Nhận code" description=""></el-step>
                            </el-steps>
                        </div>

                        <div class="box-body">
                            <el-tabs v-model="step" @tab-click="handleClick">
                                <el-tab-pane label="" name="step1">
                                    <div class="content-header">
                                        <h4>Chọn vé</h4>
                                    </div>
                                    <div class="box">
                                        <div class="box-header with-border">
                                            <el-form :inline="true" class="demo-form-inline form-search">
                                                <el-form-item label="Hạng xe">
                                                    <select name="item_type" class="form-control" @change="changeItemType" v-model="ticket.item_type">
                                                        <option v-for="type in item_types" :value="type.id">{{type.text}}</option>
                                                    </select>
                                                </el-form-item>
                                                <el-form-item label="Ngày tập">
                                                        <el-date-picker
                                                                v-model="ticket.date"
                                                                type="date"
                                                                :picker-options="pickerOptions"
                                                                format="dd/MM/yyyy"
                                                                value-format="dd/MM/yyyy">
                                                        </el-date-picker>
                                                </el-form-item>
                                                <el-form-item label="Loại hình">
                                                    <select name="price_type" class="form-control" v-model="ticket.price_type">
                                                        <option v-for="type in price_types" :selected="type.se" :value="type.id">{{type.text}}</option>
                                                    </select>
                                                </el-form-item>
                                                <el-form-item label="Buổi tập">
                                                    <select name="item_type" class="form-control" v-model="ticket.time_type">
                                                        <option v-for="type in timeTypes" :value="type.value">{{type.label}}</option>
                                                    </select>
                                                </el-form-item>
                                                <el-form-item>
                                                    <el-button type="success" @click="filter">Xem</el-button>
                                                </el-form-item>
                                            </el-form>
                                        </div>
                                        <table class="table table-bordered" v-loading.body="tableLoading">
                                        <thead>
                                            <tr>
                                                <th>Xe</th>
                                                <th v-for="item in tableData" :key="item.label">
                                                    {{ item.label }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        <tr v-if="data.length != 0" v-for="item in data" :key="item.id">
                                            <td>{{ item.name }}</td>
                                            <td v-for="i in tableData" @click="cellClick(item, i)"
                                                :class="cellStyle(item, i)">
                                            </td>
                                        </tr>
                                        <tr v-if="data.length == 0">
                                            <td colspan="5">Không có dữ liệu</td>
                                        </tr>
                                        </tbody>
                                    </table>
                                    </div>
                                    <div class="box-footer">
                                        <el-button type="success" @click="next('step2', 2)" :disabled="!selected.length">Thanh toán</el-button>
                                    </div>
                                </el-tab-pane>
                                <el-tab-pane label="" name="step2">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <div class="box box-danger">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">Hóa đơn</h3>
                                                </div>

                                                <div class="box-body">
                                                    <el-table
                                                            :data="tickets"
                                                            :row-class-name="rowClassName"
                                                            style="width: 100%">
                                                        <el-table-column prop="name" label="Tên xe"></el-table-column>
                                                        <el-table-column prop="start_time" label=Từ></el-table-column>
                                                        <el-table-column prop="end_time" label="Đến"></el-table-column>
                                                        <el-table-column prop="price" label="Giá">
                                                            <template slot-scope="scope">
                                                                {{ scope.row.price | formatNumber }}
                                                            </template>
                                                        </el-table-column>
                                                    </el-table>
                                                </div>
                                                <div class="box-footer">
                                                    <el-button @click="next('step1', 1)" >Quay lại</el-button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="box box-danger">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">Thông tin thanh toán</h3>
                                                </div>

                                                <div class="box-body">
                                                    <div class="form-group" :class="{ 'has-error': form.errors.has('name') }">
                                                        <label class="control-label">Tên khách khàng <span class="required">*</span></label>
                                                        <input type="text" name="name" v-model="customer.name" class="form-control" @keydown="keydown">
                                                        <span class="help-block" v-if="form.errors.has('name')">{{ form.errors.first('name') }}</span>
                                                    </div>
                                                    <div class="form-group" :class="{ 'has-error': form.errors.has('phone') }">
                                                        <label class="control-label">Số điện thoại <span class="required">*</span></label>
                                                        <input type="text" name="phone" v-model="customer.phone" class="form-control" @keydown="keydown">
                                                        <span class="help-block" v-if="form.errors.has('phone')">{{ form.errors.first('phone') }}</span>
                                                    </div>
                                                    <div class="form-group" :class="{ 'has-error': form.errors.has('email') }">
                                                        <label class="control-label">Email</label>
                                                        <input type="text" name="email" v-model="customer.email" class="form-control" @keydown="keydown">
                                                        <span class="help-block" v-if="form.errors.has('email')">{{ form.errors.first('email') }}</span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="control-label">Tổng thanh toán</label>
                                                        <input type="text" v-model="total_fee" v-price class="form-control" disabled>
                                                    </div>
                                                </div>
                                                <div class="box-footer">
                                                    <el-button type="success" @click="submit()" >Xác nhận</el-button>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </el-tab-pane>
                                <el-tab-pane label="" name="step3">
                                    <section class="invoice">
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <h2 class="page-header text-success">
                                                    <i class="fa fa-check"></i> Đặt vé thành công. Thông tin vé đã được gửi đến số điện thoại khách hàng.
                                                    <small class="pull-right"></small>
                                                </h2>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="box-body">
                                                <el-table
                                                        :data="tickets"
                                                        stripe
                                                        style="width: 100%">
                                                    <el-table-column prop="code" label="Mã vé"></el-table-column>
                                                    <el-table-column prop="name" label="Tên xe"></el-table-column>
                                                    <el-table-column prop="start_time" label=Từ></el-table-column>
                                                    <el-table-column prop="end_time" label="Đến"></el-table-column>
                                                    <el-table-column prop="price" label="Giá">
                                                        <template slot-scope="scope">
                                                            {{ scope.row.price | formatNumber }}
                                                        </template>
                                                    </el-table-column>
                                                </el-table>
                                            </div>
                                            <div class="box-footer">
                                                <el-button type="success" @click="finish()">Xác nhận</el-button>
                                            </div>
                                        </div>
                                    </section>
                                </el-tab-pane>
                            </el-tabs>
                        </div>

                    </div>
                </div>

            </div>
        </section>
    </div>
</template>

<script>
    import Form from 'form-backend-validation';
    import func from '@/utils/functions';
    import _ from 'lodash';
    import moment from 'moment';
    import constant from '@/utils/constants';

    export default {
        data() {
            return {
                step: 'step1',
                active: 1,
                data: [],
                timeTypeDefaultValue: 1,
                tableData: func.times(this.timeTypeDefaultValue),
                selected: [],
                customer: {},
                tableLoading: false,
                form: new Form(),
                tickets: [],
                total_fee: null,
                query: false,
                objetTypePrice : func.objectForTypePrice(),
                price_types: [
                    {id: "", text: '-- Chọn loại hình --', selected: true}
                ],
                timeTypes: [
                    {value: 1, label: "Buổi sáng"},
                    {value: 2, label: "Buổi chiều"},
                    {value: 3, label: "Buổi tối"}
                ],
                item_types: [{id: null, text: '-- Chọn hạng xe --'}],
                ticket: {
                    date: moment().format('DD/MM/YYYY'),
                    price_type: "",
                    item_type: null,
                    time_type: 1,
                },
                pickerOptions: {
                    disabledDate(event) {
                        var diff = moment(event).diff(moment(), 'days')
                        if (diff<0 || diff>4) {
                            return true;
                        }
                    }
                }
            };
        },
        directives: {
            price: {
                update: function(el) {
                    el.value = func.formatNumber(el.value);
                }
            }
        },
        methods: {
            getData() {
                this.tableLoading = true;
                axios.get(route('api.item.type.all'))
                    .then(res=>{
                        this.tableLoading = false;
                        this.item_types = this.item_types.concat(res.data.data)
                    });

            },
            getTickets() {
                axios.get(route('api.ticket.get', this.ticket))
                    .then(res => {
                        this.tableLoading = false;
                        this.data = res.data.data
                    })
            },
            filter() {
                let {date, price_type, item_type} = this.ticket;
                if(date=='') {
                    return alert('Chọn ngày và nơi tập');
                }
                if(item_type == null){
                    return alert('Chọn hạng xe');
                }
                if(price_type == ""){
                    return alert('Chọn loại hình');
                }
                this.tableLoading = true;
                this.tableData = func.times(this.ticket.time_type, this.ticket.price_type);
                axios.get(route('api.ticket.get', this.ticket))
                    .then(res => {
                        this.selected = [];
                        this.tableLoading = false;
                        this.data = res.data.data;
                    })
            },
            next(step, active) {
                this.step = step;
                this.active = active;
                if (active==2 && this.query) {

                    axios.post(route('api.ticket.create'), {...this.ticket, selected: this.selected})
                        .then(res=> {
                            this.query = false;
                            this.tickets = res.data.data.tickets;
                            this.total_fee = res.data.data.total;
                        })

                }
            },
            submit() {
                this.form = new Form({
                    ...this.customer,
                    selected: this.selected,
                    ...this.ticket
                });
                this.form.post(route('api.ticket.store'))
                    .then(res=>{
                        if (res.success) {
                            this.step = 'step3';
                            this.active = 3;
                            this.tickets = res.data;
                            this.$message({
                                message: res.msg,
                                type: 'success'
                            });
                        } else {
                            if (res.data) {
                                this.tickets = res.data.tickets;
                                this.total_fee = res.data.total
                            }
                            this.$notify.error({
                                title: 'Error',
                                message: res.msg
                            })
                        }
                    })
            },
            handleClick(tab, event) {
                console.log(tab, event);
            },
            cellClick(row, col) {
                let isSelected = this.cellSelected(row, col);
                if (isSelected) {
                    return false;
                }
                this.query = true;
                let index = _.findIndex(this.selected, {...row, ...col});
                if (index>-1) {
                    this.selected.splice(index, 1)
                } else {
                    this.selected.push({...row, ...col})
                }
            },
            cellStyle(row, col) {
                //console.log(row.times, col);
               if (_.findIndex(this.selected, {id: row.id, ...col})>-1) {
                   return 'bg-success';
               }

                let index = _.findIndex(row.times, function(o) {
                    //console.log(col.start_time);
                    return o.start===col.start_time || o.end === col.end_time;
                })
                if(index>-1) {
                    if (row.times[index].status===constant.TICKET_BLOCKED) return 'bg-blocked'
                    if (row.times[index].status===constant.TICKET_COMPLETED) return 'bg-selected'
                    if (row.times[index].status===constant.TICKET_PENDING) return 'bg-pending'
                }
            },
            //check vé đã đc đặt hay còn trống
            cellSelected(row, col) {
                let index = _.findIndex(row.times, function(o) {
                    return o.start==col.start_time;
                })
                return index>-1;
            },
            rowClassName({row, index}) {
                if(row.status!=1) {
                    return 'bg-blocked'
                }
            },
            finish() {
                this.active = 1;
                this.step = 'step1';
                this.selected = [];
                this.query = false;
                this.getTickets();
            },
            keydown(event) {
                this.form.errors.clear(event.target.name);
            },
            changeItemType() {
                if(this.ticket.item_type == null){
                    this.price_types = [];
                }else{
                    let item_type = this.item_types.find(x => x.id === parseInt(this.ticket.item_type)).type;
                    let object = this.objetTypePrice.find(x => x.id === item_type);
                    this.price_types = object.price_types;
                }
            },
        },
        mounted() {
            this.getData();
            //this.getTickets();
        },

    };
</script>
<style>
    .el-table .success-row {
        background: #f0f9eb;
    }
    .el-tabs__header {display: none}
</style>

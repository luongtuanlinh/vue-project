<style>
    th, td{
        text-align: center;
    }
</style>
<template>
    <div>
        <div class="content-header">
            <h1>
                Điều phối giáo viên
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Điều phối giáo viên</li>
            </ol>
        </div>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <el-form :inline="true" class="demo-form-inline form-search">
                                <el-form-item label="Hạng xe">
                                    <select name="item_type" class="form-control" @change="changeItemType" v-model="filter.item_type">
                                        <option v-for="type in item_types" :value="type.id">{{type.text}}</option>
                                    </select>
                                </el-form-item>
                                <el-form-item label="Ngày tập">
                                    <el-date-picker
                                            v-model="filter.date"
                                            type="date"
                                            format="dd/MM/yyyy"
                                            value-format="dd/MM/yyyy">
                                    </el-date-picker>
                                </el-form-item>
                                <el-form-item label="Loại hình">
                                    <select name="price_type" class="form-control" v-model="filter.price_type">
                                        <option v-for="type in price_types" :value="type.id">{{type.text}}</option>
                                    </select>
                                </el-form-item>
                                <el-form-item label="Buổi tập">
                                    <select name="item_type" class="form-control" v-model="filter.time_type">
                                        <option v-for="type in timeTypes" :value="type.value">{{type.label}}</option>
                                    </select>
                                </el-form-item>
                                <el-form-item>
                                    <el-button type="success" @click="search()">Xem</el-button>
                                </el-form-item>
                            </el-form>
                        </div>
                        <div class="box-body">
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
                                    <td v-for="i in tableData" v-if="hideCell(item, i)" :colspan="colspanNumber(item, i)" @click="cellClick(item, i)"
                                        :class="cellStyle(item, i)" >{{nameTeacher(item, i)}}
                                    </td>
                                </tr>
                                <tr v-if="data.length == 0">
                                    <td colspan="5">Không có dữ liệu</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                </div>
            </div>
        </section>
        <el-dialog title="Điều phối giáo viên" :visible.sync="dialogForm" :append-to-body=true>
            <el-form ref="form"
                     :model="ticket"
                     label-width="120px">
                <el-form-item label="Mã vé">
                    <el-input v-model="ticket.code" disable></el-input>
                </el-form-item>
                <el-form-item label="Giáo viên">
                    <select2 name="teacher_id" v-model="ticket.teacher_id" :options="teachers"/>
                </el-form-item>
            </el-form>
            <span slot="footer" class="dialog-footer">
                <el-button @click="dialogForm = false">Hủy bỏ</el-button>
                <el-button type="success" @click="submit()" :disabled="!ticket.teacher_id">Xác nhận</el-button>
            </span>
        </el-dialog>
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
                data: [],
                tableData: func.times(),
                teachers: [],
                tableLoading: false,
                dialogForm: false,
                teacher: {},
                ticket: {},
                _ticket: {},
                form: new Form(),
                tickets: [],
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
                filter: {
                    date: moment().format('DD/MM/YYYY'),
                    type: constant.TYPE_LINE,
                    item_type: null,
                    teacher: 1,//điều phối gv=>show mã vé
                    time_type: 1,
                    price_type: ""
                }
            };
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
                this.tableLoading = true;
                axios.get(route('api.ticket.teacher', this.filter))
                    .then(res => {
                        this.tableLoading = false;
                        this.data = res.data.data
                    })
            },
            search() {
                this.tableData = func.times(this.filter.time_type, this.filter.price_type);
                this.getTickets();
            },
            submit() {
                if(!this.ticket.teacher_id){
                    return alert('bạn phải chọn giáo viên');
                }
                this.dialogForm = false;
                axios.post(route('api.ticket.teacher.assign'), this.ticket)
                    .then(res=> {
                        let {success, msg} = res.data;
                        if (success) {
                            let _this = this;
                            _.forEach(this.teachers, function(o) {
                                if (o.id==_this.ticket.teacher_id) {
                                    _this._ticket.teacher = o.text;
                                    _this._ticket.teacher_id = _this.ticket.teacher_id;
                                }
                            })
                            this.$message({
                                message: msg,
                                type: 'success'
                            });
                        } else {
                            this.$notify.error({
                                title: 'Error',
                                message: msg
                            })
                        }
                    })
            },
            nameTeacher(row, col) {
                let index = _.findIndex(row.times, function(o) {
                    return o.start===col.start_time;
                })
                if (index>-1)
                return row.times[index].teacher
            },
            cellClick(row, col) {
                let index = _.findIndex(row.times, function(o) {
                    return o.start==col.start_time;
                })
                if (index===-1) {
                    return false;
                }
                this._ticket = row.times[index];
                this.ticket = Object.assign({}, row.times[index]);
                this.selectTeacher();
            },
            selectTeacher() {
                if (this.teachers.length===0) {
                    this.tableLoading = true;
                    axios.get(route('api.teacher.all'))
                        .then(res=> {
                            this.tableLoading = false;
                            this.teachers = res.data.data;
                            this.dialogForm = true;
                        });
                } else {
                    this.dialogForm = true;
                }
            },
            changeItemType() {
                if(this.filter.item_type == null){
                    this.price_types = [];
                }else{
                    let item_type = this.item_types.find(x => x.id === parseInt(this.filter.item_type)).type;
                    let object = this.objetTypePrice.find(x => x.id === item_type);
                    this.price_types = object.price_types;
                }
            },
            cellStyle(row, col) {
                let index = _.findIndex(row.times, function (o) {
                    return o.start === col.start_time;
                })
                if (index > -1) return 'bg-selected';
                return '';
            },
            colspanNumber(row, col){

                let index = _.findIndex(row.times, function (o) {
                    return o.start === col.start_time;
                })

                if (index > -1) return row.times[index].hour;
                return 1;
            },
            hideCell(row, col){
                let index = _.findIndex(row.times, function (o) {
                    return o.start === col.start_time;
                })
                if (index < 0){
                    let index_of_time_array =  _.findIndex(row.times, function (o) {
                        return o.time_array.includes(col.start_time);
                    });
                    if(index_of_time_array > -1){

                        return false;
                    }else{
                        return true;
                    }
                }else{
                    return true;
                }
            }
        },
        mounted() {
            this.getData();
            //this.getTickets();
        }
    };
</script>
<style scoped>
    .bg-selected {
        background-color: #40bf56;
        color: #0e0e0e;
        font-weight: 600;
        text-align: center;
    }
</style>

<template>
    <div>
        <div class="content-header">
            <h1>
                Ngày nghỉ lễ
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Ngày nghỉ lễ</li>
            </ol>
        </div>

        <section class="content">

            <div class="row">
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Danh sách ngày nghỉ lễ</h3>
                        </div>

                        <div class="box-body">
                            <el-table
                                    :data="data"
                                    border
                                    style="width: 100%"
                                    @row-click="show"
                                    :row-class-name="tableRowClassName"
                                    v-loading.body="tableLoading">
                                <el-table-column type="index" width="50"></el-table-column>
                                <el-table-column prop="date" label="Ngày">
                                    <template slot-scope="scope">
                                        {{ date(scope) }}
                                    </template>
                                </el-table-column>
                                <el-table-column prop="note" label="Ghi chú">
                                </el-table-column>
                                <el-table-column prop="actions" align="center" width="80px">
                                    <template slot-scope="scope">
                                        <el-button type="danger" icon="el-icon-delete" size="mini" @click.prevent="confirmDelete(scope)"></el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <div class="pagination-wrap">
                                <el-pagination
                                        v-if="total>0"
                                        @size-change="handleSizeChange"
                                        @current-change="handleCurrentChange"
                                        :current-page.sync="meta.page"
                                        :page-sizes="[10, 20, 50, 100]"
                                        :page-size="meta.per_page"
                                        layout="total, sizes, prev, pager, next"
                                        :total="total">
                                </el-pagination>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Thêm mới</h3>
                        </div>

                        <div class="box-body">
                            <div class="form-group" :class="{ 'has-error': form.errors.has('date') }">
                                <label class="control-label">Ngày</label>
                                <el-date-picker
                                        v-model="item.date"
                                        type="date"
                                        @change="form.errors.clear('date')"
                                        format="dd/MM/yyyy"
                                        value-format="dd/MM/yyyy">
                                </el-date-picker>
                                <span class="help-block" v-if="form.errors.has('date')">{{ form.errors.first('date') }}</span>
                            </div>
                            <div class="form-group" :class="{ 'has-error': form.errors.has('note') }">
                                <label class="control-label">Ghi chú</label>
                                <el-input v-model="item.note"></el-input>
                            </div>
                            <div class="form-group">
                                <el-checkbox v-model="item.repeat" :true-label=1 :false-label=0>Lặp lại</el-checkbox>
                            </div>
                        </div>
                        <div class="box-footer">
                            <el-button type="success" @click="onSubmit()" v-if="!isUpdate" :loading="loading">Thêm mới</el-button>
                            <el-button type="primary" @click="onSubmit()" v-if="isUpdate" :loading="loading">Cập nhật</el-button>
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
    import moment from 'moment';

    export default {
        data() {
            return {
                data: [],
                total: 0,
                meta: {
                    page: 1,
                    per_page: 10
                },
                item: {},
                scope: {},
                isUpdate: false,
                form: new Form(),
                loading: false,
                tableLoading: false
            }
        },
        methods: {
            onSubmit() {
                let api = route('api.calendar.store');

                this.loading = true;
                this.form = new Form(this.item);

                this.form.post(api)
                    .then(res=> {
                        this.loading = false;
                        if (res.success) {
                            this.reset();
                            this.$message({
                                message: res.msg,
                                type: 'success'
                            });
                        } else {
                            this.$notify.error({
                                title: 'Error',
                                message: res.msg
                            });
                        }
                    })
                    .catch(error=> {
                        this.loading = false;
                        console.log(error);
                    })
            },
            reset() {
                this.getData();
                this.item = {};
                this.isUpdate = false;
            },
            date(scope) {
                if ( (scope.row.repeat)) {
                    return moment(scope.row.date).format('DD-MM')
                };
                return moment(scope.row.date).format('DD-MM-YYYY')
            },
            getData() {
                this.tableLoading = true;
                axios.get(route('api.calendar.get', this.meta))
                    .then(res => {
                        console.log(res)
                        this.tableLoading = false;
                        this.data = res.data.data.data;
                        this.total = res.data.data.total
                    })
            },
            show(scope) {
                if (scope.id===this.item.id) {
                    this.isUpdate = false;
                    this.item = {}
                } else {
                    this.isUpdate = true;
                    this.item = Object.assign({}, scope);
                    this.item.date = func.formatDate(this.item.date)
                }
            },
            tableRowClassName({row, rowIndex}) {
                if (row.id === this.item.id) {
                    return 'success-row'
                }
            },
            confirmDelete(scope) {
                this.$confirm('Xóa dữ liệu?', 'Xác nhận', {
                    confirmButtonText: 'OK',
                    cancelButtonText: 'hủy bỏ',
                    type: 'warning',
                    center: true
                }).then(() => {
                    this.delete(scope)
                }).catch(() => {

                });
            },
            delete(scope) {
                axios.post(route('api.calendar.delete'), {id: scope.row.id})
                    .then(res => {
                        if (res.data.success) {
                            this.item = {};
                            this.isUpdate = false;
                            this.data.splice(scope.$index, 1);
                            this.total--;
                            this.$message({
                                message: res.data.msg,
                                type: 'success'
                            });
                        } else {
                            this.$notify.error({
                                title: 'Error',
                                message: res.data.msg
                            })
                        }
                    })
            },
            handleSizeChange(event) {
                this.meta.per_page = event;
                this.getData();
            },
            handleCurrentChange(event) {
                this.meta.page = event;
                this.getData();
            }
        },
        mounted() {
            this.getData();
        }

    }
</script>
<style>
    .el-table .success-row {
        background: #f0f9eb;
    }
</style>
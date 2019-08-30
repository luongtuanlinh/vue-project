<template>
    <div>
        <div class="content-header">
            <h1>
                Lịch khóa xe
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Lịch khóa xe</li>
            </ol>
        </div>

        <section class="content">

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">

                        <div class="box-body">
                            <div class="tool-bar el-row" style="padding-bottom: 20px;">
                                <div class="actions el-col el-col-8">
                                    <router-link :to="{name: 'item.block.create'}">
                                        <el-button type="success"><i class="el-icon-edit"></i>
                                            Thêm mới
                                        </el-button>
                                    </router-link>
                                </div>
                                <div class="search el-col el-col-5">

                                </div>
                            </div>
                            <el-table
                                    :data="data"
                                    tripe
                                    style="width: 100%"
                                    v-loading.body="tableLoading">
                                <el-table-column type="index" width="50"></el-table-column>
                                <el-table-column prop="type" label="Hạng xe"></el-table-column>
                                <el-table-column prop="item" label="Xe">
                                    <template slot-scope="scope">
                                        {{ scope.row.item | item }}
                                    </template>
                                </el-table-column>
                                <el-table-column prop="start_time" label="Từ">
                                    <template slot-scope="scope">
                                            {{ scope.row.start_time | formatDateTime }}
                                    </template>
                                </el-table-column>
                                <el-table-column prop="end_time" label="Đến">
                                    <template slot-scope="scope">
                                        {{ scope.row.end_time | formatDateTime }}
                                    </template>
                                </el-table-column>
                                <el-table-column prop="note" label="Ghi chú"></el-table-column>
                                <el-table-column prop="user" label="user"></el-table-column>
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
            </div>
        </section>
    </div>
</template>

<script>
    import Form from 'form-backend-validation';

    export default {
        data() {
            return {
                data: [],
                total: 0,
                meta: {
                    page: 1,
                    per_page: 10
                },
                subject: {},
                scope: {},
                isUpdate: false,
                form: new Form(),
                loading: false,
                tableLoading: false
            }
        },
        filters: {
            item: function (item) {
                return item || 'tất cả'
            }
        },
        methods: {
            onSubmit() {
                let api = route('api.subject.store');
                if (this.isUpdate) {
                    api = route('api.subject.update', {id: this.subject.id})
                }
                this.loading = true;
                this.form = new Form(this.subject);

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
                this.subject = {};
                this.isUpdate = false;
            },
            getData() {
                this.tableLoading = true;
                axios.get(route('api.item.block.index', this.meta))
                    .then(res => {
                        this.tableLoading = false;
                        this.data = res.data.data.data;
                        this.total = res.data.data.total
                    })
            },
            show(scope) {
                if (scope.id===this.subject.id) {
                    this.isUpdate = false;
                    this.subject = {}
                } else {
                    this.isUpdate = true;
                    this.subject = Object.assign({}, scope);
                }
            },
            tableRowClassName({row, rowIndex}) {
                console.log()
                if (row.id === this.subject.id) {
                    return 'success-row'
                }
            },
            confirmDelete(scope) {
                console.log(scope)
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
                axios.post(route('api.item.block.delete'), {id: scope.row.id})
                    .then(res => {
                        if (res.data.success) {
                            this.subject = {};
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
            keydown(event){
                this.form.errors.clear(event.target.name);
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

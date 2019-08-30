<template>
    <div>
        <div class="content-header">
            <h1>
                Môn học
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Môn học</li>
            </ol>
        </div>

        <section class="content">

            <div class="row">
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Danh sách môn học</h3>
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
                                <el-table-column prop="name" label="Tên môn học">
                                </el-table-column>
                                <el-table-column prop="note" label="Mô tả">
                                </el-table-column>
                                <el-table-column prop="hours" label="Tổng số giờ">
                                    <template slot-scope="scope">
                                            {{ scope.row.hours | formatNumber }}
                                    </template>
                                </el-table-column>
                                <el-table-column prop="status" label="Trạng thái">
                                    <template slot-scope="scope">
                                        <span class="label"
                                              :class="scope.row.status | className">
                                                {{ scope.row.status | subjectStatus}}</span>
                                    </template>
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
                            <div class="form-group" :class="{ 'has-error': form.errors.has('name') }">
                                <label class="control-label">Tên môn học</label>
                                <input type="text" name="name" v-model="subject.name" class="form-control" @keydown="keydown">
                                <span class="help-block" v-if="form.errors.has('name')">{{ form.errors.first('name') }}</span>
                            </div>
                            <div class="form-group" :class="{ 'has-error': form.errors.has('hours') }">
                                <label class="control-label">Tổng số giờ học</label>
                                <money name="hours" class="form-control" v-model="subject.hours"></money>
                                <span class="help-block" v-if="form.errors.has('hours')">{{ form.errors.first('hours') }}</span>
                            </div>
                            <div class="form-group" :class="{ 'has-error': form.errors.has('note') }">
                                <label class="control-label">Ghi chú</label>
                                <textarea name="note" v-model="subject.note" class="form-control"></textarea>
                            </div>
                            <div class="form-group" :class="{ 'has-error': form.errors.has('note') }">
                                <el-checkbox v-model="subject.status" :true-label=1 :false-label=0>Active</el-checkbox>
                            </div>
                        </div>
                        <div class="box-footer">
                            <el-button type="success" @click="onSubmit()" v-if="!isUpdate" :loading="loading">Thêm mới</el-button>
                            <el-button type="success" @click="onSubmit()" v-if="isUpdate" :loading="loading">Cập nhật</el-button>
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
        watch: {
            'subject.hours': function (val) {
                this.form.errors.clear('hours')
            }
        },
        filters: {
            className: function (status) {
                switch (parseInt(status)) {
                    case 1: return 'label-success';
                    case 0: return 'label-danger'
                }
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
                axios.get(route('api.subject.index', this.meta))
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
                axios.post(route('api.subject.delete'), {id: scope.row.id})
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

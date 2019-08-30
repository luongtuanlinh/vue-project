<template>
    <div>
        <div class="content-header">
            <h1>
                Danh sách lớp thi
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Danh sách lớp thi</li>
            </ol>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="sc-table">
                                <div class="tool-bar el-row" style="padding-bottom: 20px;">
                                    <div class="actions el-col el-col-8">
                                        <router-link :to="{name: 'exam.create'}">
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
                                        border
                                        style="width: 100%"
                                        v-loading.body="loading">
                                    <el-table-column type="index" width="50"></el-table-column>
                                    <el-table-column prop="name" label="Tên lớp thi"></el-table-column>
                                    <el-table-column prop="code" label="Mã lớp thi"></el-table-column>
                                    <el-table-column prop="class" label="Hạng xe"></el-table-column>
                                    <el-table-column prop="status" label="Loại lớp thi">
                                        <template slot-scope="scope">
                                            <span class="label"
                                                  :class="scope.row.type | className">
                                                {{ scope.row.type | typeExam}}</span>
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="students_count" label="Số thí sinh"></el-table-column>
                                    <el-table-column prop="date" label="Ngày thi">
                                        <template slot-scope="scope">
                                                {{ scope.row.date | formatDate}}
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="actions" align="center">
                                        <template slot-scope="scope">
                                            <el-button-group>
                                                <el-button type="default" icon="el-icon-edit" size="small" @click="gotoEdit(scope)"></el-button>
                                                <el-button type="default" icon="el-icon-folder-opened" size="small" @click="gotoDetail(scope)"></el-button>
                                                <el-button type="danger" icon="el-icon-delete" size="small" @click="confirmDelete(scope)"></el-button>
                                            </el-button-group>
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
            </div>
        </section>
    </div>
</template>

<script>
    import constant from '@/utils/constants';

    export default {
        data() {
            return {
                data: [],
                total: 0,
                meta: {
                    page: 1,
                    per_page: 10
                },
                className: '',
                loading: false
            }
        },
        filters: {
            className(type) {
                switch(parseInt(type)) {
                    case 1:
                        return 'label-success';
                    case 2:
                        return 'label-primary';
                }
            }
        },
        methods: {
            getData() {
                this.loading = true;
                axios.get(route('api.exam.index', this.meta))
                    .then(res=>{
                        this.loading = false;
                        this.data = res.data.data.data;
                        this.total = res.data.data.total
                    })
            },
            gotoEdit(scope) {
                this.$router.push({ name: 'exam.edit', params: { id: scope.row.id } });
            },
            gotoDetail(scope) {
                this.$router.push({ name: 'exam.student', params: { id: scope.row.id } });
            },
            confirmDelete(scope){
                this.$confirm('Xóa lớp thi và tất cả dữ liệu của lớp thi?', 'Xác nhận', {
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
                axios.post(route('api.exam.delete'), {id: scope.row.id})
                    .then(res => {
                        if (res.data.success) {
                            this.data.splice(scope.$index,1);
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
            this.getData()
        }

    }
</script>

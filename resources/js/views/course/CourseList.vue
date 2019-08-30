<template>
    <div>
        <div class="content-header">
            <h1>
                Danh sách Khóa học
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Danh sách khóa học</li>
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
                                        <router-link :to="{name: 'course.create'}">
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
                                    <el-table-column prop="name" label="Tên khóa học"></el-table-column>
                                    <el-table-column prop="car_class" label="Hạng xe"></el-table-column>
                                    <el-table-column prop="status" label="Trạng thái">
                                        <template slot-scope="scope">
                                            <span class="label"
                                                  :class="scope.row.status | className">
                                                {{ scope.row.status | status}}</span>
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="quantity" label="Sỹ số hiện tại">
                                        <template slot-scope="scope">
                                            {{ scope.row.quantity | formatNumber}}
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="max" label="Sỹ số tối đa">
                                        <template slot-scope="scope">
                                            {{ scope.row.max | formatNumber}}
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="actions" align="center">
                                        <template slot-scope="scope">
                                            <el-button-group>
                                                <el-button type="default" icon="el-icon-edit" size="small" @click="gotoEdit(scope)"></el-button>
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
    import f from '@/utils/functions';
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
            status(status) {
                switch(parseInt(status)) {
                    case constant.COURSE_DANG_TUYEN:
                        return 'đang tuyển sinh';
                    case constant.COURSE_NGUNG_TUYEN:
                        return 'ngừng tuyển sinh';
                    case constant.COURSE_KET_THUC:
                        return 'kết thúc';
                }
            },
            className(status) {
                switch(parseInt(status)) {
                    case constant.COURSE_DANG_TUYEN:
                        return 'label-success';
                    case constant.COURSE_NGUNG_TUYEN:
                        return 'label-danger';
                    case constant.COURSE_KET_THUC:
                        return 'label-primary';
                }
            }
        },
        methods: {
            getData() {
                this.loading = true;
                axios.get(route('api.course.index', this.meta))
                    .then(res=>{
                        this.loading = false;
                        this.data = res.data.data.data;
                        this.total = res.data.data.total
                    })
            },
            gotoEdit(scope) {
                this.$router.push({ name: 'course.edit', params: { id: scope.row.id } });
            },
            confirmDelete(scope){
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
                axios.post(route('api.course.delete'), {id: scope.row.id})
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

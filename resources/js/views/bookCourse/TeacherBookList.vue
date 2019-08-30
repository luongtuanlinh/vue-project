<template>
    <div>
        <div class="content-header">
            <h1>
                Danh sách đặt giữ chỗ
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Danh sách đặt giữ chỗ</li>
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
                                        <router-link :to="{name: 'book.create'}">
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
                                    <el-table-column prop="program" label="ct đào tạo">
                                        <template slot-scope="scope">
                                            {{ scope.row.type | type }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="user" label="Người yêu cầu">
                                    </el-table-column>
                                    <el-table-column prop="course" label="Khóa học">
                                    </el-table-column>
                                    <el-table-column prop="quantity" label="Sl yêu cầu">
                                        <template slot-scope="scope">
                                            {{ scope.row.quantity | formatNumber }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="applied" label="đã nộp">
                                        <template slot-scope="scope">
                                            {{ scope.row.applied | formatNumber }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="time_order" label="Ngày yêu cầu">
                                        <template slot-scope="scope">
                                            {{ scope.row.time_order | formatDate }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="expire_date" label="Ngày hết hạn">
                                        <template slot-scope="scope">
                                            {{ scope.row.expire_date | formatDate }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="total_fee" label="Tổng học phí">
                                        <template slot-scope="scope">
                                            {{ scope.row.total_fee | formatNumber }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="paid" label="Học phí đã đóng">
                                        <template slot-scope="scope">
                                            {{ scope.row.paid | formatNumber }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="actions" align="center">
                                        <template slot-scope="scope">
                                            <el-button-group>
                                                <el-button type="primary" icon="el-icon-info" size="small" @click="gotoDetail(scope)"></el-button>
                                                <el-button type="default" icon="el-icon-folder-add" size="small" @click="apply(scope)"></el-button>
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
    export default {
        data() {
            return {
                data: [],
                total: 0,
                meta: {
                    page: 1,
                    per_page: 10
                },
                loading: false
            }
        },
        filters: {
            type(x) {
                return (x==1)? 'G1' : 'G0'
            }
        },
        methods: {
            getData() {
                this.loading = true;
                axios.get(route('api.book.index', this.meta))
                    .then(res=>{
                        this.loading = false;
                        this.data = res.data.data.data;
                        this.total = res.data.data.total
                    })
            },
            apply(scope) {
                this.$router.push({ name: 'book.apply', params: { id: scope.row.id } });
            },
            gotoEdit(scope) {
                this.$router.push({ name: 'book.edit', params: { id: scope.row.id } });
            },
            gotoDetail(scope) {
                this.$router.push({ name: 'book.info', params: { id: scope.row.id } });
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
                axios.post(route('api.book.delete'), {id: scope.row.id})
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

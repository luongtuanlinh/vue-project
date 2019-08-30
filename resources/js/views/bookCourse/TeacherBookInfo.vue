<template>
    <div>
        <div class="content-header">
            <h1>
                Hồ sơ đã nộp
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><router-link :to="{name: 'book.index'}">Danh sách đặt giữ chố</router-link></li>
                <li class="active">Hồ sơ đã nộp</li>
            </ol>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="sc-table">
                                <div class="el-row" style="padding-bottom: 20px;">
                                    <div class="tool-bar action el-col">
                                        <el-form :inline="true" class="demo-form-inline">
                                            <el-form-item label="Nhân viên">
                                                <strong>{{ item.name_user }}</strong>
                                            </el-form-item>
                                            <el-form-item label="Khóa học">
                                                <span>{{ item.name_course }}</span>
                                            </el-form-item>
                                            <el-form-item label="Sl đặt giữ chỗ">
                                                <span>{{ item.quantity }}</span>
                                            </el-form-item>
                                            <el-form-item label="sl hồ sơ đã nộp">
                                                <span>{{ item.applied }}</span>
                                            </el-form-item>
                                            <el-form-item label="Ngày yêu cầu">
                                                <span>{{ item.time_order }}</span>
                                            </el-form-item>
                                            <el-form-item label="Ngày hết hạn yêu cầu">
                                                <span>{{ item.expire_date }}</span>
                                            </el-form-item>
                                        </el-form>
                                    </div>
                                    <div class="actions el-col el-col-8" v-show="item.quantity>item.applied">
                                        <router-link :to="{name: 'book.apply', id: $route.params.id}">
                                            <el-button type="success"><i class="el-icon-edit"></i>
                                                Thêm mới
                                            </el-button>
                                        </router-link>
                                    </div>
                                </div>

                                <el-table
                                        :data="data"
                                        border
                                        style="width: 100%"
                                        v-loading.body="loading">
                                    <el-table-column type="index" width="50"></el-table-column>
                                    <el-table-column prop="name" label="Tên">
                                    </el-table-column>
                                    <el-table-column prop="gender" label="Giới tính">
                                        <template slot-scope="scope">
                                            {{ scope.row.gender | gender }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="birth_day" label="Ngày sinh">
                                        <template slot-scope="scope">
                                            {{ scope.row.birth_day | formatDate }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="actions" align="center">
                                        <template slot-scope="scope">
                                            <el-button-group>
                                                <el-button type="default" icon="el-icon-edit" size="small"></el-button>
                                                <el-button type="danger" icon="el-icon-delete" size="small" @click="confirmDelete(scope)"></el-button>
                                            </el-button-group>
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>

<style scoped>
    .tool-bar {
        border: 1px solid #ccc;
        padding: 5px;
        border-radius: 3px;
        margin-bottom: 10px
    }
</style>
<script>
    export default {
        data() {
            return {
                item: {},
                data: [],
                loading: false,
                oid: this.$route.params.id
            }
        },
        methods: {
            getData() {
                this.loading = true;
                axios.get(route('api.book.info', {id: this.oid}))
                    .then(res=>{
                        this.item = res.data.data;
                    })
                axios.get(route('api.student.all', {oid: this.oid}))
                    .then(res=>{
                        this.loading = false;
                        this.data = res.data.data;
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
                axios.post(route('api.student.delete'), {id: scope.row.id})
                    .then(res => {
                        if (res.data.success) {
                            this.data.splice(scope.$index,1);
                            this.total--;
                            this.item.applied--;
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

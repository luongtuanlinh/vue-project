<template>
    <div>
        <div class="content-header">
            <h1>
                Danh sách học viên
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Danh sách học viên</li>
            </ol>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="sc-table">
                                <div class="tool-bar el-row" style="padding-bottom: 20px;">
                                    <div class="actions el-col">
                                        <el-form :inline="true" class="demo-form-inline form-search">
                                            <el-form-item label="Tên học viên">
                                                <el-input v-model="search.name"></el-input>
                                            </el-form-item>
                                            <el-form-item label="Mã học viên">
                                                <el-input v-model="search.code"></el-input>
                                            </el-form-item>
                                            <el-form-item label="email">
                                                <el-input v-model="search.email"></el-input>
                                            </el-form-item>
                                            <el-form-item label="Số điện thoại">
                                                <el-input v-model="search.mobile"></el-input>
                                            </el-form-item>
                                            <el-form-item label="Khóa học">
                                                <select2 v-model="search.course_id" :options="courses" :settings="{allowClear: true, placeholder: ''}"/>
                                            </el-form-item>
                                            <el-form-item label="Ct đào tạo">
                                                <select2 v-model="search.program" :options="programs" :settings="{allowClear: true, placeholder: ''}"/>
                                            </el-form-item>
                                            <el-form-item>
                                                <el-button type="success" @click="filter">Lọc</el-button>
                                            </el-form-item>
                                        </el-form>
                                    </div>
                                </div>

                                <el-table
                                        :data="data"
                                        border
                                        style="width: 100%"
                                        v-loading.body="loading">
                                    <el-table-column type="index" width="50"></el-table-column>
                                    <el-table-column prop="name" label="Tên học viên">
                                    </el-table-column>
                                    <el-table-column prop="code" label="Mã học viên">
                                    </el-table-column>
                                    <el-table-column prop="mobile" label="Số điện thoại">
                                    </el-table-column>
                                    <el-table-column prop="email" label="Email">
                                    </el-table-column>
                                    <el-table-column prop="birth_day" label="Ngày sinh">
                                        <template slot-scope="scope">
                                            {{ scope.row.birth_day | formatDate }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="course" label="Khóa học">
                                    </el-table-column>
                                    <el-table-column prop="program" label="CT đào tạo">
                                    </el-table-column>
                                    <el-table-column prop="actions" align="center">
                                        <template slot-scope="scope">
                                            <el-button-group>
                                                <el-button type="default" icon="el-icon-info" size="small" @click="gotoEdit(scope)"></el-button>
                                                <!--<el-button type="danger" icon="el-icon-delete" size="small" @click="confirmDelete(scope)"></el-button>-->
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
    import func from '@/utils/functions';
    import _ from 'lodash';

    export default {
        data() {
            return {
                data: [],
                total: 0,
                meta: {
                    page: 1,
                    per_page: 10
                },
                courses: [],
                programs: [
                    {id: 'G0', text:'G0'},
                    {id: 'G1', text:'G1'},
                    {id: 'G2', text:'G2'},
                    {id: 'G3', text:'G3'},
                ],
                search: {},
                loading: false,
            }
        },
        methods: {
            getData() {
                this.loading = true;
                axios.get(route('api.student.index', _.merge(this.meta, this.search)))
                    .then(res=>{
                        this.loading = false;
                        this.data = res.data.data.data;
                        this.total = res.data.data.total
                    })
            },
            filter() {
                this.getData()
            },
            gotoEdit(scope) {
                this.$router.push({ name: 'student.info', params: { id: scope.row.id } });
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
            getAllCourse() {
                axios.get(route('api.course.all'))
                    .then(res => {
                        this.courses = res.data.data;
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
            this.getAllCourse();
        }

    }
</script>

<template>
    <div>
        <div class="content-header">
            <h1>
                Phân lớp quản lí học viên
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active">Phân lớp quản lí học viên</li>
            </ol>
        </div>

        <section class="content">

            <div class="row">
                <div class="col-md-4">
                    <div class="box box-primary">
                        <div class="box-header with-border">

                        </div>

                        <div class="box-body disabled" v-loading="formLoading">
                            <div class="form-group">
                                <label class="control-label">Khóa học</label>
                                <select2 name="course_id" v-model="search.course" :options="courses" @select="selectedCourse"/>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Lớp học</label>
                                <select2 name="course_id" v-model="search.class" :options="classes" @select="selectedClass"/>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Sỹ số hiện tại</label>
                                <input type="text" class="form-control" :value="classSelected.quantity" disabled>
                            </div>
                            <div class="form-group">
                                <label class="control-label">Sỹ số tối đa</label>
                                <input type="text" class="form-control" :value="classSelected.max" disabled>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Danh sách học viên chưa được phân lớp</h3>
                            <div class="tool-bar el-row" style="margin-top: 10px">
                                <div class="actions el-col el-col-8">
                                    <el-button type="success" size="small" :disabled="!studentSelected.length" @click="onSubmit"><i class="el-icon-s-promotion"></i> Phân lớp</el-button>
                                </div>
                            </div>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>

                        <div class="box-body">
                            <el-table
                                    :data="data"
                                    border
                                    style="width: 100%"
                                    @selection-change="handleSelectionChange">
                                    v-loading.body="tableLoading1">
                                <el-table-column
                                        type="selection"
                                        width="55">
                                </el-table-column>
                                <el-table-column prop="name" label="Tên">
                                </el-table-column>
                                <el-table-column prop="gender" label="Giới tính">
                                    <template slot-scope="scope">
                                        {{ scope.row.gender | gender }}
                                    </template>
                                </el-table-column>
                                <el-table-column prop="mobile" label="Sđt">
                                </el-table-column>
                                <el-table-column prop="course" label="Khóa học">
                                </el-table-column>
                            </el-table>
                            <div class="pagination-wrap">
                                <el-pagination
                                        v-if="meta1.total>0"
                                        @size-change="handleSizeChange1"
                                        @current-change="handleCurrentChange1"
                                        :current-page.sync="meta1.page"
                                        :page-sizes="[10, 20, 50, 100]"
                                        :page-size="meta1.per_page"
                                        layout="total, sizes, prev, pager, next"
                                        :total="meta1.total">
                                </el-pagination>
                            </div>
                        </div>

                    </div>
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">Danh sách học viên của lớp</h3>
                            <div class="box-tools pull-right">
                                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                            </div>
                        </div>

                        <div class="box-body">
                            <el-table
                                    :data="students"
                                    border
                                    style="width: 100%"
                                    v-loading.body="tableLoading2">
                                <el-table-column type="index" width="50"></el-table-column>
                                <el-table-column prop="name" label="Tên">
                                </el-table-column>
                                <el-table-column prop="gender" label="Giới tính">
                                    <template slot-scope="scope">
                                        {{ scope.row.gender | gender }}
                                    </template>
                                </el-table-column>
                                <el-table-column prop="mobile" label="Sđt">
                                </el-table-column>
                                <el-table-column prop="course" label="Khóa học">
                                </el-table-column>
                                <el-table-column prop="actions" align="center" width="80px">
                                    <template slot-scope="scope">
                                        <el-button type="danger" icon="el-icon-delete" size="mini" @click.prevent="confirmDelete(scope)"></el-button>
                                    </template>
                                </el-table-column>
                            </el-table>
                            <div class="pagination-wrap">
                                <el-pagination
                                        v-if="meta2.total>0"
                                        @size-change="handleSizeChange2"
                                        @current-change="handleCurrentChange2"
                                        :current-page.sync="meta2.page"
                                        :page-sizes="[10, 20, 50, 100]"
                                        :page-size="meta2.per_page"
                                        layout="total, sizes, prev, pager, next"
                                        :total="meta2.total">
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
    import _ from 'lodash';

    export default {
        data() {
            return {
                data: [],
                students: [],
                meta1: {
                    page: 1,
                    per_page: 10,
                    total: 0,
                },
                meta2: {
                    page: 1,
                    per_page: 10,
                    total: 0,
                },
                search: {
                    course: '',
                    class: ''
                },
                courses: [],
                classes: [],
                formLoading: false,
                tableLoading1: false,
                tableLoading2: false,
                courseSelected: {},
                classSelected: {id: ''},
                studentSelected: []
            }
        },
        methods: {
            handleSelectionChange(val) {
                this.studentSelected = _.map(val, function (o) {
                    return o.id;
                });
            },
            onSubmit() {
                if (!this.classSelected.id) {
                    return this.$notify.error({
                        title: 'Error',
                        message: 'bạn chưa chọn lớp'
                    })
                }

                let $add = this.studentSelected.length;
                if (parseInt($add)+parseInt(this.classSelected.quantity)>parseInt(this.classSelected.max)) {
                    return this.$notify.error({
                        title: 'Error',
                        message: 'Vượt quá sỹ số tối đa của lớp'
                    })
                }

                axios.post(route('api.class.student.add'), {ids: this.studentSelected, class_id: this.classSelected.id})
                    .then(res=> {
                        if (res.data.success) {
                            this.classSelected.quantity++;

                            this.meta1.page = 1;
                            this.tableLoading1 = true;
                            this.getStudents({...this.meta1, course_id: this.courseSelected.id, class_id: -1})
                                .then(res=> {
                                    this.data = res.data.data.data;
                                    this.meta1.total = res.data.data.total;
                                    this.tableLoading1 = false;
                                });

                            this.tableLoading2 = true;
                            this.getStudents({...this.meta2, class_id: this.classSelected.id})
                                .then(res=> {
                                    this.tableLoading2 = false;
                                    this.students = res.data.data.data;
                                    this.meta2.total = res.data.data.total;
                                })
                            this.$message({
                                message: res.data.msg,
                                type: 'success'
                            });
                        }
                    })
            },
            getData() {
                this.formLoading = true;
                axios.get(route('api.course.all'))
                    .then(res=>{
                        this.formLoading = false;
                        this.courses = res.data.data;
                    })

                this.tableLoading1 = true;
                this.getStudents({...this.meta1, class_id: -1})
                    .then(res=> {
                        this.data = res.data.data.data;
                        this.meta1.total = res.data.data.total;
                        this.tableLoading1 = false;
                    })
            },
            getStudents(options={}) {
                return axios.get(route('api.student.index', options));
            },
            confirmDelete(scope) {
                this.$confirm('Xóa học viên khỏi danh sách lớp?', 'Xác nhận', {
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
                axios.post(route('api.class.student.remove'), {id: scope.row.id})
                    .then(res => {
                        if (res.data.success) {
                            this.classSelected.quantity--;
                            this.students.splice(scope.$index, 1);
                            this.meta2.total--;
                            this.meta1.page = 1;
                            this.tableLoading1 = true;
                            this.getStudents({...this.meta1, course_id: this.courseSelected.id})
                                .then(res=> {
                                    this.data = res.data.data.data;
                                    this.meta1.total = res.data.data.total;
                                    this.tableLoading1 = false;
                                })
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
            selectedCourse(course) {
                this.courseSelected = course;
                this.formLoading = true;
                this.getClasses(course.id);

                this.meta1.page = 1;
                this.tableLoading1 = true;
                this.getStudents({...this.meta1, course_id: course.id, class_id: -1})
                    .then(res=> {
                        this.data = res.data.data.data;
                        this.meta2.total = res.data.data.total;
                        this.tableLoading1 = false;
                    })
            },
            selectedClass(class_) {
                this.classSelected = class_;
                this.meta2.page = 1;
                this.tableLoading2 = true;
                this.getStudents({...this.meta2, class_id: class_.id})
                    .then(res=> {
                        this.tableLoading2 = false;
                        this.students = res.data.data.data;
                        this.meta2.total = res.data.data.total;
                    })
            },
            getClasses($id) {
                axios.get(route('api.class.all', {course_id: $id}))
                    .then(res=>{
                        this.formLoading = false;
                        this.classes = res.data;
                    })
            },
            handleSizeChange1(event) {
                this.meta1.per_page = event;
                this.getData();
            },
            handleCurrentChange1(event) {
                this.meta1.page = event;
                this.getData();
            },
            handleSizeChange2(event) {
                this.meta2.per_page = event;
                this.getData();
            },
            handleCurrentChange2(event) {
                this.meta2.page = event;
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

<template>
    <div>
        <div class="content-header">
            <h1>
                Thêm học viên lớp thi <small>{{ exam.code }}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li><router-link :to="{name: 'exam.student', id}">Danh sách lớp thi</router-link></li>
                <li class="active">Thêm học viên lớp thi</li>
            </ol>
        </div>

        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box box-primary">
                        <div class="box-body">
                            <div class="sc-table">
                                <div class="tool-bar el-row">
                                    <div class="actions el-col">
                                        <el-form :inline="true" class="demo-form-inline">
                                            <el-form-item label="Tên lớp thi">
                                                <strong>{{ exam.name }}</strong>
                                            </el-form-item>
                                            <el-form-item label="Mã lớp thi">
                                                <span>{{ exam.code }}</span>
                                            </el-form-item>
                                            <el-form-item label="Hạng xe">
                                                <span>{{ exam.class }}</span>
                                            </el-form-item>
                                            <el-form-item label="Loại lớp thi">
                                                <span>{{ exam.type | typeExam }}</span>
                                            </el-form-item>
                                            <el-form-item label="Số học viên">
                                                <span>{{ exam.students_count }}</span>
                                            </el-form-item>
                                            <el-form-item label="Ngày thi">
                                                <span>{{ exam.date }}</span>
                                            </el-form-item>
                                        </el-form>
                                    </div>
                                    <div class="actions el-col">
                                        <el-form :inline="true" class="demo-form-inline">
                                            <div class="form-group col-sm-3 row">
                                                <label class="el-form-item__label">Chọn học viên từ</label>
                                                <select name="status" class="form-control" v-model="search.exam_1">
                                                    <option value="1">Khóa học đang diễn ra</option>
                                                    <option value="2">Học viên thi lại</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-sm-3" v-if="search.exam_1==1">
                                                <label class="el-form-item__label">Khóa học đang diễn ra</label>
                                                <select2 v-model="search.course_id" :options="courses" :settings="{allowClear: true, placeholder: ''}"/>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label style="height: 40px; display: block"></label>
                                                <el-button size="small" type="primary" @click="filter">Tìm kiếm</el-button>
                                            </div>
                                        </el-form>
                                    </div>
                                </div>
                                <div class="tool-bar el-row" style="padding-bottom: 20px;">
                                    <div class="actions el-col el-col-8">
                                            <el-button type="success" @click="add" :disabled="this.students.length===0">
                                                <i class="el-icon-check"></i>Thêm</el-button>
                                            <el-button plain @click="add(1)" v-if="search.course_id && data.length!==0">
                                                <i class="el-icon-check"></i>Thêm tất cả từ khóa học</el-button>
                                    </div>
                                </div>


                                <el-table
                                        :data="data"
                                        border
                                        style="width: 100%"
                                        @selection-change="handleSelectionChange"
                                        v-loading.body="loading">
                                    <el-table-column type="selection" width="50"></el-table-column>
                                    <el-table-column prop="name" label="Tên học viên">
                                    </el-table-column>
                                    <el-table-column prop="code" label="Mã học viên">
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
    import _ from 'lodash';

    export default {
        data() {
            return {
                exam: {},
                data: [],
                total: 0,
                meta: {
                    page: 1,
                    per_page: 10
                },
                id: this.$route.params.id,
                className: '',
                loading: false,
                search: {
                    exam_id: this.$route.params.id
                },
                courses: [],
                students: [],
            }
        },
        methods: {
            getData() {
                axios.get(route('api.course.all'))
                    .then(res => {
                        this.courses = res.data.data;
                    })
                axios.get(route('api.exam.detail', this.id))
                    .then(res=>{
                        this.exam = res.data.data;
                    })

            },
            filter() {
                this.loading = true;
                if (this.search.exam_1==2) {
                    this.search.course_id=null
                }
                this.getStudents();
            },
            getStudents() {
                axios.get(route('api.student.index', _.merge(this.meta, this.search, {exam: true})))
                    .then(res=>{
                        this.loading = false;
                        this.data = res.data.data.data;
                        this.total = res.data.data.total
                    })
            },
            handleSelectionChange(val) {
                this.students = [];
                _.forEach(val, e => {
                    this.students.push(e.id)
                })
            },
            add(all) {
                axios.post(route('api.exam.add.student'), {id: this.id, students: this.students})
                .then(res => {
                    if (res.data.success) {
                       this.getStudents();
                       this.exam.students_count = this.exam.students_count + this.students.length;
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

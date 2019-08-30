<template>
    <div>
        <div class="content-header">
            <h1>
                Danh sách học viên lớp thi <small>{{ exam.code }}</small>
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li><router-link :to="{name: 'exam.index'}">Danh sách lớp thi</router-link></li>
                <li class="active">Danh sách học viên lớp thi</li>
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
                                                <span>{{ exam.date  }}</span>
                                            </el-form-item>
                                        </el-form>
                                    </div>
                                </div>
                                <div class="tool-bar el-row" style="padding-bottom: 20px;">
                                    <div class="actions el-col el-col-8">
                                        <router-link :to="{name: 'exam.add.student'}">
                                            <el-button type="success"><i class="el-icon-edit"></i> Thêm học viên</el-button>
                                        </router-link>
                                        <el-button type="primary" @click="exportCSV()"><i class="el-icon-download"></i> Export danh sách</el-button>
                                    </div>
                                </div>

                                <div class="tool-bar el-row" style="padding-bottom: 20px;">
                                    <div class="actions">
                                        <div class="el-col el-col-5">
                                            <select name="update" v-model="action" class="form-control">
                                                <option value=""></option>
                                                <option value="1">đánh dấu đã thu lệ phí</option>
                                                <option value="2">xóa khỏi danh sách lớp</option>
                                            </select>
                                        </div>
                                        <div class="el-col el-col-3">
                                            <button class="btn btn-default" @click="update()">cập nhật</button>
                                        </div>
                                    </div>
                                </div>
                                <el-table
                                        :data="data"
                                        border
                                        style="width: 100%"
                                        @selection-change="handleSelectionChange"
                                        v-loading.body="loading">
                                    <el-table-column type="selection" width="50"></el-table-column>
                                    <el-table-column prop="name" label="Tên học viên"></el-table-column>
                                    <el-table-column prop="code" label="Mã học viên"></el-table-column>
                                    <el-table-column prop="birth_day" label="Ngày sinh">
                                        <template slot-scope="scope">
                                            {{ scope.row.birth_day | formatDate }}
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="course" label="Khóa học"></el-table-column>
                                    <el-table-column prop="program" label="CT đào tạo"></el-table-column>
                                    <el-table-column prop="fee" label="Lệ phí thi">
                                        <template slot-scope="scope">
                                            <span class="label"
                                                  :class="{'label-success': scope.row.fee==1, 'label-danger': scope.row.fee!=1}">
                                                {{ scope.row.fee | feeExam }}
                                            </span>
                                        </template>
                                    </el-table-column>
                                    <el-table-column prop="actions" align="center">
                                        <template slot-scope="scope">
                                            <el-button-group>
                                                <el-button type="danger" icon="el-icon-delete" size="small" @click="confirmDelete(scope)" v-if="scope.row.fee!=1"></el-button>
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
    import _ from 'lodash';
    import f from '@/utils/functions.js';
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
                loading: false,
                id: this.$route.params.id,
                students: [],
                action: ''
            }
        },
        methods: {
            getData() {
                this.loading = true;
                axios.get(route('api.exam.detail', this.id))
                    .then(res=>{
                        this.exam = res.data.data;
                    })
                axios.get(route('api.exam.students', _.merge(this.meta, {id: this.id})))
                    .then(res=>{
                        this.loading = false;
                        this.data = res.data.data.data;
                        this.total = res.data.data.total;
                    })
            },
            gotoEdit(scope) {
                this.$router.push({ name: 'exam.edit', params: { id: scope.row.id } });
            },
            confirmDelete(scope){
                this.$confirm('Xóa học viên khỏi danh sách thi?', 'Xác nhận', {
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
                axios.post(route('api.exam.delete.student', this.id), {studentID: scope.row.id})
                    .then(res => {
                        if (res.data.success) {
                            this.data.splice(scope.$index,1);
                            this.total--;
                            this.exam.students_count--;
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
            update() {
                if (!this.action || this.students.length===0) return;
                axios.post(route('api.exam.update.student', this.id), {action: this.action, student_ids: this.students})
                    .then(res=> {
                        if (res.data.success) {
                            this.getData();
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
            exportCSV() {
                axios.post(route('api.exam.export', this.id), {}, {responseType: 'arraybuffer'})
                    .then(res=>{
                        f.download(res, `lop-thi-${this.exam.code}`);
                    })
            },
            handleSelectionChange(val) {
                this.students = [];
                _.forEach(val, e => {
                    this.students.push(e.id)
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

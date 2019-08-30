<template>
    <div>
        <div class="content-header">
            <h1>
                Thêm lớp
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><router-link :to="{name: 'class.index'}">Danh sách lớp</router-link></li>
                <li class="active">Thêm lớp</li>
            </ol>
        </div>

        <section class="content">
            <el-form ref="form"
                     :model="item"
                     label-width="120px"
                     label-position="top">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="form-group" :class="{ 'has-error': form.errors.has('course_id') }">
                                    <label class="control-label">Khóa học</label>
                                    <select2 name="course_id" v-model="item.course_id" :options="courses" @change="form.errors.clear('course_id')"/>
                                    <span class="help-block" v-if="form.errors.has('course_id')">{{ form.errors.first('course_id') }}</span>
                                </div>
                                <div class="form-group" :class="{ 'has-error': form.errors.has('name') }">
                                    <label class="control-label">Tên lớp</label>
                                    <input type="text" name="name" v-model="item.name" class="form-control" @keydown="keydown">
                                    <span class="help-block" v-if="form.errors.has('name')">{{ form.errors.first('name') }}</span>
                                </div>
                                <div class="form-group" :class="{ 'has-error': form.errors.has('code') }">
                                    <label class="control-label">Mã lớp</label>
                                    <input type="text" name="code" v-model="item.code" class="form-control" @keydown="keydown">
                                    <span class="help-block" v-if="form.errors.has('code')">{{ form.errors.first('code') }}</span>
                                </div>
                                <div class="form-group" :class="{ 'has-error': form.errors.has('teacher_id') }">
                                    <label class="control-label">Giáo viên chủ nhiệm</label>
                                    <select2 name="teacher_id" v-model="item.teacher_id" :options="teachers" @change="form.errors.clear('teacher_id')"/>
                                    <span class="help-block" v-if="form.errors.has('teacher_id')">{{ form.errors.first('teacher_id') }}</span>
                                </div>
                                <div class="form-group" :class="{ 'has-error': form.errors.has('max') }">
                                    <label class="control-label">Sỹ số tối đa</label>
                                    <money name="max" class="form-control" v-model="item.max"></money>
                                    <span class="help-block" v-if="form.errors.has('max')">{{ form.errors.first('max') }}</span>
                                </div>
                            </div>
                            <div class="box-footer">
                                <el-form-item>
                                    <el-button type="success" @click="onSubmit()" :loading="loading">
                                        Submit
                                    </el-button>
                                </el-form-item>
                            </div>
                        </div>
                    </div>
                </div>
            </el-form>
        </section>
    </div>
</template>

<script>
    import Form from 'form-backend-validation';

    export default {
        data() {
            return {
                item: {},
                courses: [],
                teachers: [],
                form: new Form(),
                loading: false,
            }
        },
        watch: {
            'item.max': function (val) {
                this.form.errors.clear('max')
            }
        },
        methods: {
            onSubmit() {
                this.loading = true;
                this.form = new Form(this.item);

                this.form.post(route('api.class.store'))
                    .then(res=> {
                        this.loading = false;
                        if (res.success) {
                            this.$router.push({ name: 'class.index' });
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
                        this.$notify.error({
                            title: 'Error',
                            message: 'đã có lỗi xảy ra, vui lòng thử lai.'
                        });
                    })
            },
            init() {
                axios.get(route('api.course.all'))
                    .then(res=>{
                        this.courses = res.data.data;
                    })
                axios.get(route('api.teacher.all'))
                    .then(res=>{
                        this.teachers = res.data.data;
                    })
            },
            keydown(event){
                this.form.errors.clear(event.target.name);
            },
        },
        mounted() {
            this.init();
        }
    }
</script>

<template>
    <div>
        <div class="content-header">
            <h1>
                Thêm khóa học
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><router-link :to="{name: 'course.index'}">Danh sách khóa học</router-link></li>
                <li class="active">Thêm khóa học</li>
            </ol>
        </div>

        <section class="content">
            <el-form ref="form"
                     :model="course"
                     label-width="120px"
                     class="form-inline-custom-style"
                     label-position="top">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="col-sm-12">
                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('car_class_id') }">
                                        <label class="control-label">Hạng giấy phép <span class="required">*</span></label>
                                        <select2 name="car_class_id" v-model="course.car_class_id" :options="classes" @change="form.errors.clear('car_class_id')"/>
                                        <span class="help-block" v-if="form.errors.has('car_class_id')">{{ form.errors.first('car_class_id') }}</span>
                                    </div>
                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('name') }">
                                        <label class="control-label">Tên khóa học <span class="required">*</span></label>
                                        <input type="text" name="name" v-model="course.name" class="form-control" @input="inputChange">
                                        <span class="help-block" v-if="form.errors.has('name')">{{ form.errors.first('name') }}</span>
                                    </div>
                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('status') }">
                                        <label class="control-label">Trạng thái <span class="required">*</span></label>
                                        <select name="status" class="form-control" v-model="course.status" @change="inputChange">
                                            <option v-for="st in status" :value="st.value">{{st.text}}</option>
                                        </select>
                                        <span class="help-block" v-if="form.errors.has('status')">{{ form.errors.first('status') }}</span>
                                    </div>
                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('max') }">
                                        <label class="control-label">Sỹ số tối đa <span class="required">*</span></label>
                                        <money name="max" class="form-control" v-model="course.max"></money>
                                        <span class="help-block" v-if="form.errors.has('max')">{{ form.errors.first('max') }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('start_time') }">
                                        <label class="control-label">Ngày khai giảng <span class="required">*</span></label>
                                        <el-date-picker
                                                v-model="course.start_time"
                                                type="date"
                                                format="dd/MM/yyyy"
                                                @change="form.errors.clear('start_time')"
                                                value-format="dd/MM/yyyy"
                                                placeholder="">
                                        </el-date-picker>
                                        <span class="help-block" v-if="form.errors.has('start_time')">{{ form.errors.first('start_time') }}</span>
                                    </div>
                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('end_time') }">
                                        <label class="control-label">Ngày bế giảng <span class="required">*</span></label>
                                        <el-date-picker
                                                v-model="course.end_time"
                                                type="date"
                                                format="dd/MM/yyyy"
                                                @change="form.errors.clear('end_time')"
                                                value-format="dd/MM/yyyy"
                                                placeholder="">
                                        </el-date-picker>
                                        <span class="help-block" v-if="form.errors.has('end_time')">{{ form.errors.first('end_time') }}</span>
                                    </div>
                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('exam_end_time') }">
                                        <label class="control-label">Ngày thi tốt nghiệp <span class="required">*</span></label>
                                        <el-date-picker
                                                v-model="course.exam_end_time"
                                                type="date"
                                                format="dd/MM/yyyy"
                                                @change="form.errors.clear('exam_end_time')"
                                                value-format="dd/MM/yyyy"
                                                placeholder="">
                                        </el-date-picker>
                                        <span class="help-block" v-if="form.errors.has('exam_end_time')">{{ form.errors.first('exam_end_time') }}</span>
                                    </div>
                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('exam_time') }">
                                        <label class="control-label">Ngày thi sát hạch <span class="required">*</span></label>
                                        <el-date-picker
                                                v-model="course.exam_time"
                                                type="date"
                                                format="dd/MM/yyyy"
                                                @change="form.errors.clear('exam_time')"
                                                value-format="dd/MM/yyyy"
                                                placeholder="">
                                        </el-date-picker>
                                        <span class="help-block" v-if="form.errors.has('exam_time')">{{ form.errors.first('exam_time') }}</span>
                                    </div>
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
    import constants from '@/utils/constants';

    export default {
        data() {
            return {
                course: {},
                classes: [],
                status: [
                    {value: constants.COURSE_DANG_TUYEN, text: 'Đang tuyển sinh'},
                    {value: constants.COURSE_NGUNG_TUYEN, text: 'Ngừng tuyển sinh'},
                    {value: constants.COURSE_KET_THUC, text: 'Kết thúc'}
                ],
                form: new Form(),
                loading: false,
            }
        },
        watch: {
            'course.max': function(newVal, oldVal) {
                this.form.errors.clear('max')
            },
            'course.quantity': function(newVal, oldVal) {
                this.form.errors.clear('quantity')
            },
        },
        methods: {
            onSubmit() {
                this.loading = true;
                this.form = new Form(this.course);
                this.form.post(route('api.course.store'))
                    .then(res=> {
                        this.loading = false;
                        if (res.success) {
                            this.$router.push({ name: 'course.index' });
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
            getAllClass() {
                axios.get(route('api.item.class.all'))
                    .then(res=>{
                        this.classes = res.data.data;
                    })
            },
            inputChange(event){
                this.form.errors.clear(event.target.name);
            },
        },
        mounted() {
            this.getAllClass();
        }
    }
</script>

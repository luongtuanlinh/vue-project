<template>
    <div>
        <div class="content-header">
            <h1>
                Thông tin học viên
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><router-link :to="{name: 'student.index'}">Danh sách học viên</router-link></li>
                <li class="active">Thông tin học viên</li>
            </ol>
        </div>

        <section class="content">
            <el-form ref="form"
                     :model="student"
                     label-width="120px"
                     class="form-inline-custom-style"
                     label-position="top">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-primary"  v-loading.body="loading">
                            <div class="box-body">
                                <el-tabs v-model="activeTab" @tab-click="handleClick">
                                    <el-tab-pane label="Thông tin cá nhân" name="profile" class="disabled">
                                        <div class="col-sm-12">
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('name') }">
                                                <label class="control-label">Tên học viên</label>
                                                <input type="text" name="name" v-model="student.name" class="form-control" disabled>
                                                <span class="help-block" v-if="form.errors.has('name')">{{ form.errors.first('name') }}</span>
                                            </div>
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('code') }">
                                                <label class="control-label">Mã học viên</label>
                                                <input type="text" name="code" v-model="student.code" class="form-control" disabled>
                                                <span class="help-block" v-if="form.errors.has('code')">{{ form.errors.first('code') }}</span>
                                            </div>
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('birth_day') }">
                                                <label class="control-label">Ngày sinh</label>
                                                <el-date-picker
                                                        disabled
                                                        v-model="student.birth_day"
                                                        type="date"
                                                        format="dd/MM/yyyy"
                                                        @change="form.errors.clear('birth_day')"
                                                        value-format="dd/MM/yyyy"
                                                        placeholder="">
                                                </el-date-picker>
                                                <span class="help-block" v-if="form.errors.has('birth_day')">{{ form.errors.first('birth_day') }}</span>
                                            </div>
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('gender') }">
                                                <label class="control-label">Giới tính</label>
                                                <select name="gender" class="form-control" v-model="student.gender" disabled>
                                                    <option v-for="st in genders" :value="st.value">{{st.text}}</option>
                                                </select>
                                                <span class="help-block" v-if="form.errors.has('gender')">{{ form.errors.first('gender') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('mobile') }">
                                                <label class="control-label">Số điện thoại</label>
                                                <input type="text" name="mobile" class="form-control" v-model="student.mobile" disabled>
                                                <span class="help-block" v-if="form.errors.has('mobile')">{{ form.errors.first('mobile') }}</span>
                                            </div>
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('email') }">
                                                <label class="control-label">Email</label>
                                                <input type="text" name="email" class="form-control" v-model="student.email" disabled>
                                                <span class="help-block" v-if="form.errors.has('email')">{{ form.errors.first('email') }}</span>
                                            </div>
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('passport') }">
                                                <label class="control-label">Số CMT/căn cước</label>
                                                <input type="text" name="passport" class="form-control" v-model="student.passport" disabled>
                                                <span class="help-block" v-if="form.errors.has('passport')">{{ form.errors.first('passport') }}</span>
                                            </div>
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('passport_date') }">
                                                <label class="control-label">Ngày cấp CMT</label>
                                                <el-date-picker
                                                        disabled
                                                        v-model="student.passport_date"
                                                        type="date"
                                                        format="dd/MM/yyyy"
                                                        @change="form.errors.clear('passport_date')"
                                                        value-format="dd/MM/yyyy"
                                                        placeholder="">
                                                </el-date-picker>
                                                <span class="help-block" v-if="form.errors.has('passport_date')">{{ form.errors.first('passport_date') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('passport_address') }">
                                                <label class="control-label">Nơi cấp CMT/căn cước</label>
                                                <select2 name="passport_address" v-model="student.passport_address" :options="provinces" disabled/>
                                                <span class="help-block" v-if="form.errors.has('passport_address')">{{ form.errors.first('passport_address') }}</span>
                                            </div>
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('phone') }">
                                                <label class="control-label">SĐT người thân/bạn bè</label>
                                                <input type="text" name="phone" class="form-control" v-model="student.phone" disabled/>
                                                <span class="help-block" v-if="form.errors.has('phone')">{{ form.errors.first('phone') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('driver_no') }">
                                                <label class="control-label">Số GPLX</label>
                                                <input type="text" name="driver_no" class="form-control" v-model="student.driver_no" disabled>
                                                <span class="help-block" v-if="form.errors.has('driver_no')">{{ form.errors.first('driver_no') }}</span>
                                            </div>
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('driver_class') }">
                                                <label class="control-label">Hạng GPLX</label>
                                                <select2 name="driver_class" v-model="student.driver_class" :options="classes" @change="form.errors.clear('driver_class')" disabled/>
                                                <span class="help-block" v-if="form.errors.has('driver_class')">{{ form.errors.first('driver_class') }}</span>
                                            </div>
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('driver_date') }">
                                                <label class="control-label">Ngày cấp GPLX</label>
                                                <el-date-picker
                                                        disabled
                                                        v-model="student.driver_date"
                                                        type="date"
                                                        format="dd/MM/yyyy"
                                                        @change="form.errors.clear('driver_date')"
                                                        value-format="dd/MM/yyyy"
                                                        placeholder="">
                                                </el-date-picker>
                                                <span class="help-block" v-if="form.errors.has('driver_date')">{{ form.errors.first('driver_date') }}</span>
                                            </div>
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('driver_address') }">
                                                <label class="control-label">Nơi cấp GPLX</label>
                                                <select2 name="driver_address" v-model="student.driver_address" :options="provinces" disabled/>
                                                <span class="help-block" v-if="form.errors.has('driver_address')">{{ form.errors.first('driver_address') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('course_id') }">
                                                <label class="control-label">Khóa học</label>
                                                <select2 name="course_id" v-model="student.course_id" :options="courses" disabled/>
                                                <span class="help-block" v-if="form.errors.has('course_id')">{{ form.errors.first('course_id') }}</span>
                                            </div>
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('program_id') }">
                                                <label class="control-label">Chương trình đào tạo</label>
                                                <select2 name="program_id" v-model="student.program_id" :options="programs" @select="selected" disabled/>
                                                <span class="help-block" v-if="form.errors.has('program_id')">{{ form.errors.first('program_id') }}</span>
                                            </div>
                                            <div class="form-group col-sm-3" v-if="student.teacher_id">
                                                <label class="control-label">Giáo viên</label>
                                                <input type="text" disabled :value="student.teacher" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('fee') }">
                                                <label class="control-label">Học phí</label>
                                                <input type="text" name="fee" class="form-control" v-model="student.fee" @input="inputChange" disabled v-price>
                                                <span class="help-block" v-if="form.errors.has('fee')">{{ form.errors.first('fee') }}</span>
                                            </div>
<!--                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('sub_fee') }">-->
<!--                                                <label class="control-label">Chi phí khác</label>-->
<!--                                                <input type="text" name="sub_fee" class="form-control" v-model="student.sub_fee" disabled>-->
<!--                                                <span class="help-block" v-if="form.errors.has('sub_fee')">{{ form.errors.first('sub_fee') }}</span>-->
<!--                                            </div>-->
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('discount') }">
                                                <label class="control-label">Giảm trừ</label>
                                                <input type="text" name="discount" class="form-control" v-model="student.discount" disabled v-price>
                                                <span class="help-block" v-if="form.errors.has('discount')">{{ form.errors.first('discount') }}</span>
                                            </div>
                                            <div class="form-group col-sm-3">
                                                <label class="control-label">Tổng phải nộp</label>
                                                <input type="text" name="" class="form-control" v-model="total" disabled  v-price>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('has_picture') }">
                                                <label class="control-label">Ảnh thẻ 3x4</label>
                                                <select name="has_picture" class="form-control" v-model="student.has_picture" disabled>
                                                    <option v-for="st in options" :value="st.value">{{st.text}}</option>
                                                </select>
                                                <span class="help-block" v-if="form.errors.has('has_picture')">{{ form.errors.first('has_picture') }}</span>
                                            </div>
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('has_passport') }">
                                                <label class="control-label">Giấy CMND</label>
                                                <select name="has_passport" class="form-control" v-model="student.has_passport" disabled>
                                                    <option v-for="st in options" :value="st.value">{{st.text}}</option>
                                                </select>
                                                <span class="help-block" v-if="form.errors.has('has_passport')">{{ form.errors.first('has_passport') }}</span>
                                            </div>
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('has_apply') }">
                                                <label class="control-label">Đơn</label>
                                                <select name="has_apply" class="form-control" v-model="student.has_apply" disabled>
                                                    <option v-for="st in options" :value="st.value">{{st.text}}</option>
                                                </select>
                                                <span class="help-block" v-if="form.errors.has('has_apply')">{{ form.errors.first('has_apply') }}</span>
                                            </div>
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('health_certification') }">
                                                <label class="control-label">Giấy khám sức khỏe</label>
                                                <select name="health_certification" class="form-control" v-model="student.health_certification" disabled>
                                                    <option v-for="st in options" :value="st.value">{{st.text}}</option>
                                                </select>
                                                <span class="help-block" v-if="form.errors.has('health_certification')">{{ form.errors.first('health_certification') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('health_certification_date') }">
                                                <label class="control-label">Ngày cấp GKSK</label>
                                                <el-date-picker
                                                        disabled
                                                        v-model="student.health_certification_date"
                                                        type="date"
                                                        format="dd/MM/yyyy"
                                                        @change="form.errors.clear('health_certification_date')"
                                                        value-format="dd/MM/yyyy"
                                                        placeholder="">
                                                </el-date-picker>
                                                <span class="help-block" v-if="form.errors.has('health_certification_date')">{{ form.errors.first('health_certification_date') }}</span>
                                            </div>
                                        </div>
                                        <div class="col-sm-12">
                                            <div class="form-group col-sm-6">
                                                <label class="control-label">Ghi chú</label>
                                                <textarea name="note" v-model="student.note" class="form-control" disabled></textarea>
                                            </div>
                                        </div>
                                    </el-tab-pane>
                                    <el-tab-pane label="Thông tin đăng nhập" name="login_info">Config</el-tab-pane>
                                </el-tabs>

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
    import func from '@/utils/functions';
    import provinces from '@/assets/provinces.json';

    export default {
        data() {
            return {
                student: {
                    health_certification_date: '',
                    driver_date: ''
                },
                classes: [],
                courses: [],
                sources: [],
                programs: [],
                provinces: provinces,
                genders: [
                    {value: 1, text: 'Nam'},
                    {value: 0, text: 'Nữ'},
                ],
                options: [
                    {value: 1, text: 'Có'},
                    {value: 0, text: 'Không'},
                ],
                activeTab: 'profile',
                total: null,
                form: new Form(),
                loading: false,
                id: this.$route.params.id
            }
        },
        directives: {
            price: {
                update: function(el) {
                    el.value = func.formatNumber(el.value);
                }
            }
        },
        methods: {
            onSubmit() {
                this.loading = true;
                this.form = new Form(this.student);
                this.form.post(route('api.student.store'))
                    .then(res=> {
                        this.loading = false;
                        if (res.success) {
                            this.$router.push({ name: 'student.index' });
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
                    .then(res => {
                        this.courses = res.data.data;
                    })
                axios.get(route('api.source.all'))
                    .then(res => {
                        this.sources = res.data.data;
                    })
                axios.get(route('api.program.all'))
                    .then(res => {
                        this.programs = res.data.data;
                    })
                axios.get(route('api.item.class.all'))
                    .then(res => {
                        this.classes = res.data.data;
                    })
            },
            getInfo(){
                this.loading = true;
                axios.get(route('api.student.detail', {id: this.id}))
                    .then(res=> {
                        this.loading = false;
                        this.student = res.data.data;
                        this.fee();
                    })
            },
            selected(event) {
                this.student.fee = event.fee;
            },
            handleClick(tab, event) {
                console.log(tab, event);
            },
            inputChange(event){
                this.form.errors.clear(event.target.name);
            },
            fee() {
                let {fee, discount} = this.student;
                this.total = parseInt(fee) - parseInt(discount)
            },
        },
        mounted() {
            this.init();
            this.getInfo();
        }
    }
</script>

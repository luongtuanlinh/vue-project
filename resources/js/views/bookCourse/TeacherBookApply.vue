<template>
    <div>
        <div class="content-header">
            <h1>
                Nộp hồ sơ
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><router-link :to="{name: 'book.index'}">Danh sách đặt giữ chỗ</router-link></li>
                <li class="active">Nộp hồ sơ</li>
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
                                </div>
                                <el-form ref="form"
                                         :model="student"
                                         label-width="120px"
                                         class="form-inline-custom-style"
                                         label-position="top">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="box-body row">
                                                <div class="col-sm-12">
                                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('name') }">
                                                        <label class="control-label">Tên học viên <span class="required">*</span></label>
                                                        <input type="text" name="name" v-model="student.name" class="form-control" @input="inputChange">
                                                        <span class="help-block" v-if="form.errors.has('name')">{{ form.errors.first('name') }}</span>
                                                    </div>
                                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('birth_day') }">
                                                        <label class="control-label">Ngày sinh <span class="required">*</span></label>
                                                        <el-date-picker
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
                                                        <label class="control-label">Giới tính <span class="required">*</span></label>
                                                        <select name="gender" class="form-control" v-model="student.gender" @change="inputChange">
                                                            <option v-for="st in genders" :value="st.value">{{st.text}}</option>
                                                        </select>
                                                        <span class="help-block" v-if="form.errors.has('gender')">{{ form.errors.first('gender') }}</span>
                                                    </div>
                                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('passport') }">
                                                        <label class="control-label">Số CMT/căn cước <span class="required">*</span></label>
                                                        <input type="text" name="passport" class="form-control" v-model="student.passport" @input="inputChange">
                                                        <span class="help-block" v-if="form.errors.has('passport')">{{ form.errors.first('passport') }}</span>
                                                    </div>
<!--                                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('mobile') }">-->
<!--                                                        <label class="control-label">Số điện thoại <span class="required">*</span></label>-->
<!--                                                        <input type="text" name="mobile" class="form-control" v-model="student.mobile" @input="inputChange">-->
<!--                                                        <span class="help-block" v-if="form.errors.has('mobile')">{{ form.errors.first('mobile') }}</span>-->
<!--                                                    </div>-->
                                                </div>
                                                <div class="col-sm-12">
<!--                                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('email') }">-->
<!--                                                        <label class="control-label">Email <span class="required">*</span></label>-->
<!--                                                        <input type="text" name="email" class="form-control" v-model="student.email" @input="inputChange">-->
<!--                                                        <span class="help-block" v-if="form.errors.has('email')">{{ form.errors.first('email') }}</span>-->
<!--                                                    </div>-->
                                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('passport_date') }">
                                                        <label class="control-label">Ngày cấp CMT <span class="required">*</span></label>
                                                        <el-date-picker
                                                                v-model="student.passport_date"
                                                                type="date"
                                                                format="dd/MM/yyyy"
                                                                @change="form.errors.clear('passport_date')"
                                                                value-format="dd/MM/yyyy"
                                                                placeholder="">
                                                        </el-date-picker>
                                                        <span class="help-block" v-if="form.errors.has('passport_date')">{{ form.errors.first('passport_date') }}</span>
                                                    </div>
                                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('passport_address') }">
                                                        <label class="control-label">Nơi cấp CMT/căn cước <span class="required">*</span></label>
                                                        <select2 name="passport_address" v-model="student.passport_address" :options="provinces" @change="form.errors.clear('passport_address')"/>
                                                        <span class="help-block" v-if="form.errors.has('passport_address')">{{ form.errors.first('passport_address') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('driver_no') }">
                                                        <label class="control-label">Số GPLX</label>
                                                        <input type="text" name="driver_no" class="form-control" v-model="student.driver_no" @input="inputChange">
                                                        <span class="help-block" v-if="form.errors.has('driver_no')">{{ form.errors.first('driver_no') }}</span>
                                                    </div>
                                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('driver_class') }">
                                                        <label class="control-label">Hạng GPLX</label>
                                                        <select2 name="driver_class" v-model="student.driver_class" :options="classes" @change="form.errors.clear('driver_class')"/>
                                                        <span class="help-block" v-if="form.errors.has('driver_class')">{{ form.errors.first('driver_class') }}</span>
                                                    </div>
                                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('driver_date') }">
                                                        <label class="control-label">Ngày cấp GPLX</label>
                                                        <el-date-picker
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
                                                        <select2 name="driver_address" v-model="student.driver_address" :options="provinces" @change="form.errors.clear('driver_address')"/>
                                                        <span class="help-block" v-if="form.errors.has('driver_address')">{{ form.errors.first('driver_address') }}</span>
                                                    </div>
                                                </div>

                                                <div class="col-sm-12">
                                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('has_picture') }">
                                                        <label class="control-label">Ảnh thẻ 3x4</label>
                                                        <select name="has_picture" class="form-control" v-model="student.has_picture" @change="inputChange">
                                                            <option v-for="st in options" :value="st.value">{{st.text}}</option>
                                                        </select>
                                                        <span class="help-block" v-if="form.errors.has('has_picture')">{{ form.errors.first('has_picture') }}</span>
                                                    </div>
                                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('has_passport') }">
                                                        <label class="control-label">Giấy CMND</label>
                                                        <select name="has_passport" class="form-control" v-model="student.has_passport" @change="inputChange">
                                                            <option v-for="st in options" :value="st.value">{{st.text}}</option>
                                                        </select>
                                                        <span class="help-block" v-if="form.errors.has('has_passport')">{{ form.errors.first('has_passport') }}</span>
                                                    </div>
                                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('has_apply') }">
                                                        <label class="control-label">Đơn</label>
                                                        <select name="has_apply" class="form-control" v-model="student.has_apply" @change="inputChange">
                                                            <option v-for="st in options" :value="st.value">{{st.text}}</option>
                                                        </select>
                                                        <span class="help-block" v-if="form.errors.has('has_apply')">{{ form.errors.first('has_apply') }}</span>
                                                    </div>
                                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('health_certification') }">
                                                        <label class="control-label">Giấy khám sức khỏe</label>
                                                        <select name="health_certification" class="form-control" v-model="student.health_certification" @change="inputChange">
                                                            <option v-for="st in options" :value="st.value">{{st.text}}</option>
                                                        </select>
                                                        <span class="help-block" v-if="form.errors.has('health_certification')">{{ form.errors.first('health_certification') }}</span>
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <div class="form-group col-sm-3" :class="{ 'has-error': form.errors.has('health_certification_date') }">
                                                        <label class="control-label">Ngày cấp GKSK</label>
                                                        <el-date-picker
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
                                                        <textarea name="note" v-model="student.note" class="form-control"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="box-footer">
                                                <el-form-item>
                                                    <el-button type="success" @click="onSubmit(false)" :loading="loading">Lưu và thêm tiếp</el-button>
                                                    <el-button type="success" @click="onSubmit(true)" :loading="loading">Xong</el-button>
                                                </el-form-item>
                                            </div>
                                        </div>

                                    </div>
                                </el-form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                item: {},
                student: {
                    health_certification_date: '',
                    driver_date: '',
                    oid: this.$route.params.id
                },
                student_0: {
                    health_certification_date: '',
                    driver_date: '',
                    oid: this.$route.params.id
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
            onSubmit(finish) {
                this.loading = true;
                this.form = new Form(this.student);
                this.form.post(route('api.student.apply'))
                    .then(res=> {
                        this.loading = false;
                        this.item.applied++;
                        if (res.success) {
                            this.student = this.student_0;
                            if (finish || this.item.applied==this.item.quantity) {
                                this.$router.back();
                            }
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
                axios.get(route('api.book.info', {id: this.id}))
                    .then(res=>{
                        this.item = res.data.data;
                    })
                axios.get(route('api.course.all'))
                    .then(res => {
                        this.courses = res.data.data;
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
            selected(event) {
                this.student.fee = event.fee;
                this.fee()
            },
            inputChange(event){
                this.form.errors.clear(event.target.name);
                this.fee();
            },
            fee() {
                let {fee, sub_fee, discount} = this.student;
                this.total = parseInt(fee) + parseInt(sub_fee) - parseInt(discount)
            },
        },
        mounted() {
            this.init();
        }
    }
</script>

<style scoped>
    .tool-bar {
        border: 1px solid #ccc;
        padding: 5px;
        border-radius: 3px
    }
</style>
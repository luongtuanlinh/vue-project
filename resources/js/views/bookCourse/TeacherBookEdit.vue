<template>
    <div>
        <div class="content-header">
            <h1>
                Sửa yêu cầu
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><router-link :to="{name: 'book.index'}">Danh sách đặt giữ chố</router-link></li>
                <li class="active">Thêm yêu cầu</li>
            </ol>
        </div>

        <section class="content">
            <el-form ref="form"
                     :model="item"
                     v-loading="loading"
                     label-width="120px"
                     label-position="top">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="col-sm-6">
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('time_order') }">
                                        <label class="control-label">Ngày đặt <span class="required">*</span></label>
                                        <el-date-picker
                                                v-model="item.time_order"
                                                type="date"
                                                format="dd/MM/yyyy"
                                                @change="form.errors.clear('time_order')"
                                                value-format="dd/MM/yyyy"
                                                placeholder="">
                                        </el-date-picker>
                                        <span class="help-block" v-if="form.errors.has('time_order')">{{ form.errors.first('time_order') }}</span>
                                    </div>
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('expire_date') }">
                                        <label class="control-label">Giữ chỗ đến ngày <span class="required">*</span></label>
                                        <el-date-picker
                                                v-model="item.expire_date"
                                                type="date"
                                                format="dd/MM/yyyy"
                                                @change="form.errors.clear('expire_date')"
                                                value-format="dd/MM/yyyy"
                                                placeholder="">
                                        </el-date-picker>
                                        <span class="help-block" v-if="form.errors.has('expire_date')">{{ form.errors.first('expire_date') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Chương trình đào tạo</label>
                                        <select class="form-control" v-model="item.type" @change="changed();getHocphi()">
                                            <option v-for="t in types" :value="t.value">{{t.text}}</option>
                                        </select>
                                    </div>
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('user_id') }">
                                        <label class="control-label">Người yêu cầu <span class="required">*</span></label>
                                        <select2 name="user_id" v-model="item.user_id" :options="selects" @change="form.errors.clear('user_id')"/>
                                        <span class="help-block" v-if="form.errors.has('user_id')">{{ form.errors.first('user_id') }}</span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('course_id') }">
                                        <label class="control-label">Chọn khóa học <span class="required">*</span></label>
                                        <select2 name="course_id" v-model="item.course_id" :options="courses" @change="form.errors.clear('course_id');getHocphi()"/>
                                        <span class="help-block" v-if="form.errors.has('course_id')">{{ form.errors.first('course_id') }}</span>
                                    </div>
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('quantity') }">
                                        <label class="control-label">Số lượng học viên <span class="required">*</span></label>
                                        <money name="quantity" class="form-control" v-model="item.quantity"></money>
                                        <span class="help-block" v-if="form.errors.has('quantity')">{{ form.errors.first('quantity') }}</span>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Tổng học phí <span class="required">*</span></label>
                                        <money name="total_fee" class="form-control" v-model="item.total_fee" disabled></money>
                                    </div>
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('paid') }">
                                        <label class="control-label">Học phí đã đóng</label>
                                        <money name="paid" class="form-control" v-model="item.paid"></money>
                                        <span class="help-block" v-if="form.errors.has('paid')">{{ form.errors.first('paid') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <div class="col-sm-12">
                                    <el-button type="success" @click="onSubmit()" :loading="loading">Submit</el-button>
                                </div>
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
                item: {},
                teachers: [],
                users: [],
                selects: [],
                courses: [],
                form: new Form(),
                price: null,
                loading: false,
                id: this.$route.params.id,
                types: [
                    {value: 2, text: 'G0 - giáo viên'},
                    {value: 1, text: 'G1 - nhân viên'},
                ]
            }
        },
        watch: {
            'item.quantity': function (val) {
                this.updatePrice();
                this.form.errors.clear('quantity')
            }
        },
        methods: {
            initData() {
                axios.get(route('api.course.all', {course: constants.COURSE_DANG_TUYEN}))
                    .then(res=> {
                        this.courses = res.data.data;
                    });
            },
            getData() {
                this.loading = true;
              axios.get(route('api.book.detail', {id: this.id}))
                  .then(res=>{
                      this.item = res.data.data;
                      if (this.item.type==1) {
                          axios.get(route('api.user.all'))
                              .then(res=> {
                                  this.loading = false;
                                  this.users = res.data.data;
                                  this.selects = this.users;
                              });
                      } else {
                          axios.get(route('api.teacher.all'))
                              .then(res=> {
                                  this.loading = false;
                                  this.teachers = res.data.data;
                                  this.selects = this.teachers;
                              });
                      }
                      this.getHocphi()
                  })
            },
            getHocphi() {
                this.bodyLoading=true;
                axios.get(route('api.fee.getFee', {course_id: this.item.course_id, program_id: this.item.type}))
                    .then(res => {
                        this.bodyLoading=false;
                        if(res.data.success){
                            this.price = res.data.data.fee;
                        } else {
                            this.price = null
                            this.$notify.error({
                                title: 'Error',
                                message: res.data.msg
                            });
                        }
                        this.updatePrice();
                    })
            },
            changed() {
                if (this.item.type==2) {
                    this.selects = this.teachers;
                } else {
                    if (this.users.length==0) {
                        axios.get(route('api.user.all'))
                            .then(res=> {
                                this.users = res.data.data;
                                this.selects = this.users;
                            });
                    } else {
                        this.selects = this.users;
                    }
                }
            },
            updatePrice() {
                if ( this.price==null ) return;
                this.item.total_fee = this.price* this.item.quantity;
            },
            onSubmit() {
                this.loading = true;
                this.form = new Form(this.item);

                this.form.post(route('api.book.update', {id: this.id}))
                    .then(res=> {
                        this.loading = false;
                        if (res.success) {
                            this.$router.push({ name: 'book.index' });
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
                    })
            },
            keydown(event){
                this.form.errors.clear(event.target.name);
            }
        },
        mounted() {
            this.initData();
            this.getData();
        }

    }
</script>
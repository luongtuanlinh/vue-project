<template>
    <div>
        <div class="content-header">
            <h1>
                Sửa lớp thi
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><router-link :to="{name: 'exam.index'}">Danh sách lớp thi</router-link></li>
                <li class="active">Sửa lớp thi</li>
            </ol>
        </div>

        <section class="content">
            <el-form ref="form"
                     :model="exam"
                     label-width="120px"
                     v-loading="loading"
                     label-position="top">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="row">
                                    <div class="form-group col-sm-6" :class="{ 'has-error': form.errors.has('type') }">
                                        <label class="control-label">Loại lớp thi <span class="required">*</span></label>
                                        <select name="type" :value="exam.type" class="form-control" @change="form.errors.clear('type')" disabled>
                                            <option :value="t.value" v-for="t in types">{{ t.label }}</option>
                                        </select>
                                        <span class="help-block" v-if="form.errors.has('type')">{{ form.errors.first('type') }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6" :class="{ 'has-error': form.errors.has('class_id') }">
                                        <label class="control-label">Hạng xe <span class="required">*</span></label>
                                        <select2 name="car_class_id" v-model="exam.class_id" :options="classes" @change="form.errors.clear('class_id')"/>
                                        <span class="help-block" v-if="form.errors.has('class_id')">{{ form.errors.first('class_id') }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6" :class="{ 'has-error': form.errors.has('name') }">
                                        <label class="control-label">Tên lớp thi <span class="required">*</span></label>
                                        <input type="text" name="name" v-model="exam.name" class="form-control" @change="inputChange">
                                        <span class="help-block" v-if="form.errors.has('name')">{{ form.errors.first('name') }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6" :class="{ 'has-error': form.errors.has('code') }">
                                        <label class="control-label">Mã lớp thi <span class="required">*</span></label>
                                        <input type="text" name="code" :value="exam.code" class="form-control" @change="inputChange" disabled>
                                        <span class="help-block" v-if="form.errors.has('code')">{{ form.errors.first('code') }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6" :class="{ 'has-error': form.errors.has('date') }">
                                        <label class="control-label">Ngày thi <span class="required">*</span></label>
                                        <el-date-picker
                                                v-model="exam.date"
                                                type="date"
                                                format="dd/MM/yyyy"
                                                @change="form.errors.clear('date')"
                                                value-format="dd/MM/yyyy"
                                                placeholder="">
                                        </el-date-picker>
                                        <span class="help-block" v-if="form.errors.has('date')">{{ form.errors.first('date') }}</span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-sm-6" :class="{ 'has-error': form.errors.has('fee') }">
                                        <label class="control-label">Lệ phí thi <span class="required">*</span></label>
                                        <money name="fee" class="form-control" v-model="exam.fee"></money>
                                        <span class="help-block" v-if="form.errors.has('fee')">{{ form.errors.first('fee') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="box-footer">
                                <el-form-item>
                                    <el-button type="success" @click="onSubmit()" :loading="loading">
                                        Cập nhật
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
    import constant from '@/utils/constants';

    export default {
        data() {
            return {
                exam: {},
                form: new Form(),
                loading: false,
                types: [
                    {value: 1, label: 'Thi nghề'},
                    {value: 2, label: 'Thi sát hạch'},
                ],
                classes: [],
                id: this.$route.params.id
            }
        },
        watch: {
            'exam.fee': function (val) {
                this.form.errors.clear('fee')
            }
        },
        methods: {
            getData() {
                this.loading = true;
                axios.get(route('api.exam.detail', this.id))
                    .then(res=>{
                        this.loading = false;
                        this.exam = res.data.data;
                    })
            },
            getAllClass() {
                axios.get(route('api.item.class.all'))
                    .then(res=>{
                        this.classes = res.data.data;
                    })
            },
            onSubmit() {
                this.loading = true;
                this.form = new Form(this.exam);
                this.form.post(route('api.exam.update', {id: this.id}))
                    .then(res=> {
                        this.loading = false;
                        if (res.success) {
                            this.$router.push({ name: 'exam.index' });
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
            inputChange(event){
                this.form.errors.clear(event.target.name);
            },
        },
        mounted() {
            this.getData();
            this.getAllClass();
        }
    }
</script>

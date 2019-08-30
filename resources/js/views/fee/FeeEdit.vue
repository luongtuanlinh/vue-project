<template>
    <div>
        <div class="content-header">
            <h1>
                Chỉnh sửa học phí
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><router-link :to="{name: 'fee.index'}">Học phí</router-link></li>
                <li class="active">Chỉnh sửa</li>
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
                                <div class="form-group" :class="{ 'has-error': form.errors.has('class_id') }">
                                    <label class="control-label">Hạng giấy phép <span class="required">*</span></label>
                                    <select2 name="type" v-model="item.class_id" :options="classes" @change="form.errors.clear('class_id')" disabled/>
                                    <span class="help-block" v-if="form.errors.has('class_id')">{{ form.errors.first('class_id') }}</span>
                                </div>
                                <div class="form-group" :class="{ 'has-error': form.errors.has('program_id') }">
                                    <label class="control-label">Chương trình đào tạo <span class="required">*</span></label>
                                    <select2 name="program_id" v-model="item.program_id" :options="programs" @change="form.errors.clear('program_id')" disabled/>
                                    <span class="help-block" v-if="form.errors.has('program_id')">{{ form.errors.first('program_id') }}</span>
                                </div>
                                <div class="form-group" :class="{ 'has-error': form.errors.has('fee') }">
                                    <label class="control-label">Học phí <span class="required">*</span></label>
                                    <money name="fee" class="form-control" v-model="item.fee"></money>
                                    <span class="help-block" v-if="form.errors.has('fee')">{{ form.errors.first('fee') }}</span>
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
                item: {
                    fee: '',
                    class_id: null,
                    program_id: null,
                },
                classes: [],
                programs: [],
                form: new Form(),
                loading: false,
                id: this.$route.params.id
            }
        },
        watch: {
            'item.fee': function (newVal, oldVal) {
                this.form.errors.clear('fee')
            }
        },
        methods: {
            onSubmit() {
                this.loading = true;
                this.form = new Form(this.item);

                this.form.post(route('api.fee.update', {id: this.id}))
                    .then(res=> {
                        this.loading = false;
                        if (res.success) {
                            this.$router.push({ name: 'fee.index' });
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
                axios.get(route('api.item.class.all'))
                    .then(res=>{
                        this.classes = res.data.data;
                    })
                axios.get(route('api.program.all'))
                    .then(res => {
                        this.programs = res.data.data;
                    })
                axios.get(route('api.fee.detail', {id: this.id}))
                    .then(res => {
                        this.item = res.data.data;
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

<template>
    <div>
        <div class="content-header">
            <h1>
                Sửa chương trình đào tạo
            </h1>
            <ol class="breadcrumb">
                <li><a><i class="fa fa-dashboard"></i> Home</a></li>
                <li class="active"><router-link :to="{name: 'program.index'}">chương trình đào tạo</router-link></li>
                <li class="active">Sửa chương trình đào tạo</li>
            </ol>
        </div>

        <section class="content">
            <el-form ref="form"
                     :model="program"
                     label-width="120px"
                     label-position="top">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="box box-primary">
                            <div class="box-body">
                                <div class="box-body">
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('name') }">
                                        <label class="control-label">Tên chương  trình đào tạo <span class="required">*</span></label>
                                        <input type="text" name="name" v-model="program.name" class="form-control" @keydown="keydown">
                                        <span class="help-block" v-if="form.errors.has('name')">{{ form.errors.first('name') }}</span>
                                    </div>
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('time') }">
                                        <label class="control-label">Số giờ học <span class="required">*</span></label>
                                        <money name="time" class="form-control" v-model="program.time"></money>
                                        <span class="help-block" v-if="form.errors.has('time')">{{ form.errors.first('time') }}</span>
                                    </div>
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('fee') }">
                                        <label class="control-label">Học phí <span class="required">*</span></label>
                                        <money name="fee" class="form-control" v-model="program.fee"></money>
                                        <span class="help-block" v-if="form.errors.has('fee')">{{ form.errors.first('fee') }}</span>
                                    </div>
                                    <div class="form-group" :class="{ 'has-error': form.errors.has('description') }">
                                        <label class="control-label">Mô tả</label>
                                        <textarea name="note" v-model="program.description" class="form-control"></textarea>
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
    import axios from 'axios'
    import Form from 'form-backend-validation';

    export default {
        data() {
            return {
                program: {},
                form: new Form(),
                loading: false,
                id: this.$route.params.id
            }
        },
        watch: {
            'program.fee': function (val) {
                this.form.errors.clear('fee')
            }
        },
        methods: {
            getData() {
              axios.get(route('api.program.detail', {id: this.id}))
                  .then(res=>{
                      this.program = res.data.data;
                  })
            },
            onSubmit() {
                this.loading = true;
                this.form = new Form(this.program);

                this.form.post(route('api.program.update', {id: this.id}))
                    .then(res=> {
                        this.loading = false;
                        if (res.success) {
                            this.$router.push({ name: 'program.index' });
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
            this.getData();
        }

    }
</script>